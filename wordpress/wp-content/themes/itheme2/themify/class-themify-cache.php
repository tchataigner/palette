<?php

/**
 * Class to work with  post cache
 * 
 * @package default
 */
class TFCache {

    private static $upload_dir = false;
    private static $cache = array();
    public static $turnoff_cache = NULL;
    private static $header_scripts = array();
    private static $footer_scripts = array();
    private static $header_styles = array();
    private static $footer_styles = array();
    private static $is_footer = false;
    private static $footer_file = array();
    private static $id = false;
    private static $started = 0;

    /**
     * Start Caching
     * 
     * @param string $tag 
     * @param integer $post_id  
     * @param array $args 
     * @param integer $time 
     * 
     * return boolean
     */
    public static function start_cache($tag, $post_id = false, array $args = array(), $time = 30) {
        if (self::$turnoff_cache === NULL) {
            self::$turnoff_cache = self::is_cache_activate();
        }
        if (!self::$turnoff_cache) {
            self::$started++;
            if (self::$started == 1) {
                $dir = self::get_tag_cache_dir($tag, $post_id, $args);
                self::$cache = array('cache_dir' => $dir, 'time' => $time);
                if (!self::check_cache($dir, $time)) {
                    ob_start();
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    /**
     * End Caching
     * 
     * return void
     */
    public static function end_cache() {
        if (!self::$turnoff_cache && !empty(self::$cache)) {
            self::$started--;
            if (self::$started == 0) {
                $content = '';
                if (!self::check_cache(self::$cache['cache_dir'], self::$cache['time'])) {
                    $content = ob_get_contents();
                    ob_end_clean();
                    $dir = pathinfo(self::$cache['cache_dir'], PATHINFO_DIRNAME);
                    if (!is_dir($dir)) {
                        wp_mkdir_p( $dir );
                    }
                    unset($dir);
                    $wp_filesystem = self::InitWpFile();
                    self::$turnoff_cache = !$wp_filesystem->put_contents(self::$cache['cache_dir'], self::minify_html($content));
                }
                if (!self::$turnoff_cache) {
                    readfile(self::$cache['cache_dir']);
                } else {
                    echo $content;
                    self::removeDirectory(self::get_cache_dir());
                    $data = themify_get_data();
                    $data['setting-page_builder_cache'] = 'on';
                    themify_set_data($data);
                    self::$turnoff_cache = true;
                }
                self::$cache = 0;
            }
        }
    }

    /**
     * Check cache is disabled or builder is active or in admin
     * 
     * return boolean
     */
    public static function is_cache_activate() {
        $active = (is_user_logged_in() && current_user_can('manage_options')) || themify_get('setting-page_builder_cache') || is_admin() ? true : (class_exists('Themify_Builder') ? Themify_Builder::is_front_builder_activate() : FALSE);
		return apply_filters( 'themify_builder_is_cache_active', $active );
    }

    /**
     * Get tag cached directory
     * 
     * @param string $tag 
     * @param integer $post_id  
     * @param array $args 
     * 
     * return string
     */
    public static function get_tag_cache_dir($tag, $post_id = false, array $args = array()) {
        $cache_dir = self::get_cache_dir();
        if ($post_id) {
            $cache_dir.=$post_id . '/';
        }
        if ($tag) {
            $tag = trim($tag);
            $cache_dir.=$tag . '/';
            $tag = !empty($args) ? sprintf("%u", crc32(serialize(array_change_key_case($args, CASE_LOWER)))) : 'default';
            $cache_dir.=$tag . '.html';
        }
        return $cache_dir;
    }

    /**
     * Get cached directory
     * 
     * return string
     */
    public static function get_cache_dir($base = false) {
        $upload_dir = !self::$upload_dir ? wp_upload_dir() : self::$upload_dir;
        $dir_info = $base ? $upload_dir['baseurl'] : $upload_dir['basedir'];
        $dir_info.='/themify-builder/cache/' . get_template() . '/';
        if (!$base && !is_dir($dir_info)) {
            wp_mkdir_p( $dir_info );
        }
        return $dir_info;
    }

    /**
     * Check if cache time
     * 
     * return boolean
     */
    public static function check_cache($cache_dir, $time = 30) {

        if (!is_file($cache_dir)) {
            return false;
        } else {
            $last = (strtotime('now') - filemtime($cache_dir)) / 60;
            if ($last >= $time) {
                return false;
            }
        }
        return true;
    }

    /**
     * Remove cache by params
     * 
     * @param string $tag 
     * @param integer $post_id  
     * @param array $args
     * 
     * return boolean
     */
    public static function remove_cache($tag = '', $post_id = false, array $args = array()) {
        $cache_dir = self::get_tag_cache_dir($tag, $post_id, $args);
        $wp_filesystem = self::InitWpFile();
        $remove = $wp_filesystem->exists($cache_dir) ? $wp_filesystem->delete($cache_dir, true) : true;
        if ($remove) {
            $dir = self::get_cache_dir();
            $styles = $dir . 'styles/' . $post_id . '/';
            $scripts = $dir . 'scripts/' . $post_id . '/';
            self::removeDirectory($styles);
            self::removeDirectory($scripts);
            return true;
        }
        return false;
    }

    /**
     * Remove directory recursively
     * 
     * return boolean
     */
    public static function removeDirectory($path) {
        $wp_filesystem = self::InitWpFile();
        return $wp_filesystem->exists($path) ? $wp_filesystem->rmdir($path, true) : true;
    }

    private static function InitWpFile() {
        global $wp_filesystem;
        if (!isset($wp_filesystem)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
        return $wp_filesystem;
    }

    public static function minify_css_callback($matches) {
        return self::minify_css($matches[0]);
    }

    /**
     * Minify html
     * 
     * @param string $input 
     * 
     * return string
     */
    public static function minify_html($input) {
        if (trim($input) === "")
            return $input;

        // Minify Inline <style> Tag CSS.
        $input = preg_replace_callback('|<style\b[^>]*>(.*?)</style>|s', array('TFCache', 'minify_css_callback'), $input);
        return Minify_HTML::minify($input, array('jsCleanComments' => false));
    }

    /**
     * Minify Css
     * 
     * @param string $input 
     * 
     * return string
     */
    public static function minify_css($input) {

        return preg_replace(
                array(
            // Remove comments
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)#s',
            // Remove unused white-spaces
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[:\s])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `-`, `,` or a white-space
            '#(?<=[:\-,\s])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[:\-,\s]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Remove empty selectors
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
                ), array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1$2'
                ), trim($input));
    }

    public static function get_current_id() {
        if (self::$id) {
            return self::$id;
        }
        if (is_singular()) {
            self::$id = array('single' => get_the_ID());
        } elseif (is_archive()) {
            $cat = get_queried_object();
            self::$id = array('loop' => $cat->term_id);
        } elseif (is_front_page()) {
            self::$id = get_option('page_on_front');
            self::$id = self::$id > 0 ? array('single' => self::$id) : array('' => 'home');
        } elseif (is_home()) {
            self::$id = get_option('page_for_posts');
            self::$id = self::$id > 0 ? array('single' => self::$id) : array('' => 'posts');
        }
        return self::$id ? self::$id : false;
    }

    /**
     * Check if ajax request
     * 
     * @param void
     * 
     * return boolean
     */
    public static function is_ajax() {
        return defined('DOING_AJAX') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * actions for change/print styles and javascript
     * 
     * @param void
     * 
     * return void
     */
    public static function wp_enque_scripts() {

        add_filter('print_scripts_array', array(__CLASS__, 'scripts'), 9999, 1);
        add_action('wp_head', array(__CLASS__, 'header_scripts'));
        add_filter('print_styles_array', array(__CLASS__, 'styles'), 9999, 1);
        add_filter('wp_print_footer_scripts', array(__CLASS__, 'is_footer'));
    }

    public static function header_scripts() {
        self::$is_footer = 1;
    }

    public static function is_footer() {
        if (!empty(self::$footer_file)) {

            if (isset(self::$footer_file['css'])) {
                $path = pathinfo(self::$footer_file['css']['dir']);
                $name = str_replace('-tmp', '', $path['basename']);
                rename(self::$footer_file['css']['dir'], $path['dirname'] . '/' . $name);
                $fullpath = str_replace('-tmp', '', self::$footer_file['css']['path']);
                echo '<link rel="stylesheet" href="' . $fullpath . '?ver=' . THEMIFY_VERSION . '" type="tex/css" media="all" />';
            }
            if (isset(self::$footer_file['js'])) {
                $path = pathinfo(self::$footer_file['js']['dir']);
                $name = str_replace('-tmp', '', $path['basename']);
                rename(self::$footer_file['js']['dir'], $path['dirname'] . '/' . $name);
                $fullpath = str_replace('-tmp', '', self::$footer_file['js']['path']);
                echo '<script type="text/javascript" src="' . $fullpath . '?ver=' . THEMIFY_VERSION . '"></script>';
            }
        }
    }

    /**
     * check if file is load from wp core or not
     * 
     * @param string
     * 
     * return boolean
     */
    public static function is_local($file) {
        return strpos($file, 'http://') === 0 || strpos($file, 'https://') === 0 || strpos($file, '//') === 0;
    }

    /**
     * check if file is load from remote url
     * 
     * @param string
     * 
     * return boolean
     */
    public static function is_remote($file) {
        if (self::is_local($file)) {
            $url = parse_url($file);
            return $url['scheme'] . '://' . $url['host'] != get_site_url() && strpos($file, get_site_url()) !== 0;
        } else {
            false;
        }
    }

    /**
     * get full path of file
     * 
     * @param string
     * 
     * return string
     */
    public static function get_full_path($file) {
        if (self::is_local($file)) {
            $url = parse_url($file);
            $siteurl = get_site_url();
            if ($url['scheme'] . '://' . $url['host'] == $siteurl || strpos($file, $siteurl) === 0) {
                if (is_multisite()) {
                    $details = get_blog_details();
                    $url['path'] = str_replace($details->siteurl, '', $file);
                }
                else{
                    $url['path'] = str_replace(get_site_url(),'',$file);
                }
                return ABSPATH . trim($url['path'], '/');
            } else {
                return $file;
            }
        } else {
            return ABSPATH . trim($file, '/');
        }
    }

    /**
     * Scripts output, if cache exsists will return cached file, else will cache then return cached file
     * 
     * @param array
     * 
     * return array
     */
    public static function scripts($todo) {
        if (!empty($todo)) {
            $dir = self::get_page_cache_dir();
            if (!$dir) {
                return $todo;
            }
            global $wp_scripts;

            $fname = self::$is_footer ? 'footer' : 'header';
            $cache_dir = self::create_scripts_dir('scripts', $fname);
            $file_path = self::get_cache_dir(true);
            $file_path.= $dir . pathinfo($cache_dir, PATHINFO_BASENAME);
            if (is_file($cache_dir)) {
                $wp_scripts->groups['themify_cache_' . $fname] = self::$is_footer ;
                wp_enqueue_script('themify_cache_' . $fname, $file_path, array(), THEMIFY_VERSION,self::$is_footer);
                return array('themify_cache_' . $fname);
            }
            foreach ($todo as $handler) {
                if (isset($wp_scripts->registered[$handler]) && $wp_scripts->registered[$handler]->src && !isset(self::$header_scripts[$handler])) {
                    if (self::$is_footer || $wp_scripts->groups[$handler] == 1) {
                        self::$footer_scripts[$handler]['src'] = $wp_scripts->registered[$handler]->src;
                        if (isset($wp_scripts->registered[$handler]->extra['data'])) {
                            self::$footer_scripts[$handler]['data'] = $wp_scripts->registered[$handler]->extra['data'];
                            ;
                        }
                    } else {
                        self::$header_scripts[$handler]['src'] = $wp_scripts->registered[$handler]->src;
                        if (isset($wp_scripts->registered[$handler]->extra['data'])) {
                            self::$header_scripts[$handler]['data'] = $wp_scripts->registered[$handler]->extra['data'];
                            ;
                        }
                    }
                }
            }
            $scripts = self::$is_footer ? self::$footer_scripts : self::$header_scripts;
            if (!self::$is_footer) {
                $wp_scripts->groups['themify_cache_' . $fname] = 0;
                wp_enqueue_script('themify_cache_' . $fname, $file_path, array(),THEMIFY_VERSION, false);
            }
            $js = '';
            foreach ($scripts as $value) {
                if (isset($value['data'])) {
                    $js.=$value['data'] . PHP_EOL;
                }
                $data = '';
                if (self::is_remote($value['src'])) {
                    $response = wp_remote_get($value['src'], array('timeout' => 4, 'sslverify' => false));
                    if (is_array($response)) {
                        $data = $response['body'];
                    }
                } else {
                    $path = self::get_full_path($value['src']);
                    if (!file_exists($path)) {
                        $response = wp_remote_get($value['src'], array('timeout' => 4, 'sslverify' => false));
                        if (is_array($response)) {
                            $data = $response['body'];
                        }
                    } 
                    else {
                        $data = file_get_contents($path);
                    }
                }
                if ($data) {
                    $js.=PHP_EOL . '/* ' . $value['src'] . ' */' . PHP_EOL;
                    $js.=$data;
                    $js.="\n";
                }
            }
            if (self::$is_footer) {
                $temp_dir = pathinfo($cache_dir);
                $cache_dir = $temp_dir['dirname'] . '/' . $temp_dir['filename'] . '-tmp.js';
                self::$footer_file['js'] = array('dir' => $cache_dir, 'path' => $file_path);
            }
            if (file_put_contents($cache_dir, $js, FILE_APPEND)) {
                return !self::$is_footer ? array('themify_cache_' . $fname) : array();
            }
        }
        return $todo;
    }

    /**
     * check if cache directory doesn't exists, create it
     * 
     * @param $type string
     * @param $filename string
     * 
     * return string
     */
    private static function create_scripts_dir($type = 'scripts', $filename = false) {
        $dir = self::get_page_cache_dir($type);
        $cache_dir = self::get_cache_dir();
        $cache_dir.=trim($dir, '/') . '/';
        if (!is_dir($cache_dir)) {
            wp_mkdir_p( $cache_dir );
        }
        if ($filename) {
            $current_user = wp_get_current_user();
            $roles = ($current_user instanceof WP_User) ? sprintf("-%u", crc32(serialize(array_keys(array_change_key_case($current_user->roles, CASE_LOWER))))) : '';
            $ext = $type == 'scripts' ? 'js' : 'css';
            $cache_dir.=$filename . $roles . '.' . $ext;
        }
        return $cache_dir;
    }

    /**
     * return cached directory of page 
     * 
     * @param string
     * 
     * return string|boolean
     */
    private static function get_page_cache_dir($type = 'scripts') {
        $dir = self::get_current_id();
        if (!$dir) {
            return false;
        }
        $cache_dir = $type . '/' . current($dir) . '/';
        if (key($dir)) {
            $cache_dir.=key($dir) . '/';
        }
        return $cache_dir;
    }

    /**
     * Styles output, if cache exsists will return cached file, else will cache then return cached file
     * 
     * @param array
     * 
     * return array
     */
    public static function styles($todo) {

        if (!empty($todo)) {
            $dir = self::get_page_cache_dir('styles');
            if (!$dir) {
                return $todo;
            }
            $enque_styles = array();

            $fname = self::$is_footer ? 'footer' : 'header';
            $cache_dir = self::create_scripts_dir('styles', $fname);
            $file_path = self::get_cache_dir(true);
            $file_path.= $dir . pathinfo($cache_dir, PATHINFO_BASENAME);
            if (!self::$is_footer || is_file($cache_dir)) {
                $enque_styles[] = 'themify_cache_' . $fname;
                wp_enqueue_style('themify_cache_' . $fname, $file_path, array(), THEMIFY_VERSION);
            }
            if (is_file($cache_dir)) {
                return $enque_styles;
            }
            global $wp_styles;
            foreach ($todo as $handler) {
                if (isset($wp_styles->registered[$handler]) && $wp_styles->registered[$handler]->src && !isset(self::$header_styles[$handler]) && !isset(self::$footer_styles[$handler])) {
                    if (!isset($wp_styles->registered[$handler]->extra['conditional']) || !$wp_styles->registered[$handler]->extra['conditional'] || !$wp_styles->registered[$handler]['args'] || $wp_styles->registered[$handler]['args'] == 'screen' || $wp_styles->registered[$handler]['args'] == 'all') {
                        if (self::$is_footer || $wp_styles->groups[$handler] == 1) {
                            self::$footer_styles[$handler]['src'] = $wp_styles->registered[$handler]->src;
                        } else {
                            self::$header_styles[$handler]['src'] = $wp_styles->registered[$handler]->src;
                        }
                    } else {
                        $enque_styles[] = $handler;
                    }
                }
            }
            $styles = self::$is_footer ? self::$footer_styles : self::$header_styles;
            $minifier = new CSS();
            foreach ($styles as $value) {
                if (self::is_remote($value['src'])) {
                    if (strpos($value['src'], '//') === 0) {
                        $value['src'] = 'http:' . $value['src'];
                    }
                    $response = wp_remote_get($value['src'], array('timeout' => 4, 'sslverify' => false));
                    if (is_array($response)) {
                        $minifier->add($response['body']);
                    }
                } else {
                    $path = self::get_full_path($value['src']);
                    if (!file_exists($path)) {
                        $response = wp_remote_get($value['src'], array('timeout' => 4, 'sslverify' => false));
                        if (is_array($response)) {
                            $minifier->add($response['body']);
                        }
                    } else {
                        $minifier->add($path);
                    }
                }
            }

            if (self::$is_footer) {
                $temp_dir = pathinfo($cache_dir);
                $cache_dir = $temp_dir['dirname'] . '/' . $temp_dir['filename'] . '-tmp.css';
                $content = $minifier->minify();
                self::$footer_file['css'] = array('dir' => $cache_dir, 'path' => $file_path);
                if (file_put_contents($cache_dir, $content, FILE_APPEND)) {
                    return $enque_styles;
                }
            } else {
                $minifier->minify($cache_dir);
                return $enque_styles;
            }
        }
        return $todo;
    }

    public static function cache_update($post_id, $post, $update) {
        if ($post->post_status != 'publish' || in_array($post->post_type, array('attachment', 'page', 'nav_menu_item', 'tbuilder_layout_part', 'tbuilder_layout')) || wp_is_post_revision($post) || wp_is_post_autosave($post)) {
            return;
        }
        $cache_dir = self::get_cache_dir();
        if (is_dir($cache_dir) && $dh = opendir($cache_dir)) {
            while (($dir = readdir($dh)) !== false) {
                if (!in_array($dir, array('.', '..', 'scripts', 'styles', "$post_id"))) {
                    self::removeDirectory($cache_dir . '/' . $dir);
                }
            }
            closedir($dh);
        }
    }
    
    public static function check_version(){
        return version_compare(PHP_VERSION, '5.4', '>=');
    }
}
if(TFCache::check_version() &&  themify_get('setting-page_builder_is_active')=='enable'){
    if (!is_admin() && !TFCache::is_ajax()) {
        TFCache::$turnoff_cache = TFCache::is_cache_activate();
        if (!TFCache::$turnoff_cache) {
            $dirname = dirname(__FILE__);
            require_once $dirname . '/minify/minify.php';
            require_once $dirname . '/minify/css.php';
            require_once $dirname . '/minify/html.php';
            require_once $dirname . '/minify/converter.php';
            TFCache::wp_enque_scripts();
        }
    } elseif (is_admin()) {
        add_action('save_post', array('TFCache', 'cache_update'), 10, 3);
    }
}
else{
     TFCache::$turnoff_cache = true;
}
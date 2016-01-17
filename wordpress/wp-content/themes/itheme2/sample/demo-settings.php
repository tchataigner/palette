<?php
$widgets = get_option( "widget_themify-feature-posts" );
$widgets[1002] = array (
  'title' => 'Recent Posts',
  'category' => '5',
  'show_count' => '3',
  'show_date' => 'on',
  'show_thumb' => 'on',
  'show_excerpt' => NULL,
  'hide_title' => NULL,
  'thumb_width' => '40',
  'thumb_height' => '40',
  'excerpt_length' => '55',
);
update_option( "widget_themify-feature-posts", $widgets );

$widgets = get_option( "widget_themify-twitter" );
$widgets[1003] = array (
  'title' => 'Latest Tweets',
  'username' => 'themify',
  'show_count' => '3',
  'hide_timestamp' => NULL,
  'hide_url' => 'on',
  'show_follow' => 'on',
  'follow_text' => '&rarr; Follow me',
);
update_option( "widget_themify-twitter", $widgets );

$widgets = get_option( "widget_themify-flickr" );
$widgets[1004] = array (
  'title' => 'Flickr Photos',
  'username' => '52839779@N02',
  'show_count' => '9',
);
update_option( "widget_themify-flickr", $widgets );

$widgets = get_option( "widget_themify-social-links" );
$widgets[1005] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'icon_size' => 'icon-medium',
  'orientation' => 'horizontal',
);
update_option( "widget_themify-social-links", $widgets );



$sidebars_widgets = array (
  'sidebar-main' => 
  array (
    0 => 'themify-feature-posts-1002',
    1 => 'themify-twitter-1003',
    2 => 'themify-flickr-1004',
  ),
  'social-widget' => 
  array (
    0 => 'themify-social-links-1005',
  ),
); 
update_option( "sidebars_widgets", $sidebars_widgets );

$homepage = get_posts( array( 'name' => 'home', 'post_type' => 'page' ) );
	if( is_array( $homepage ) && ! empty( $homepage ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $homepage[0]->ID );
	}
	ob_start(); ?>a:112:{s:15:"setting-favicon";s:0:"";s:23:"setting-custom_feed_url";s:0:"";s:19:"setting-header_html";s:0:"";s:19:"setting-footer_html";s:0:"";s:23:"setting-search_settings";s:0:"";s:21:"setting-feed_settings";s:0:"";s:24:"setting-webfonts_subsets";s:0:"";s:21:"setting-editor-gfonts";s:131:"["Cinzel","EB Garamond","Istok Web","Jura","Kameron","Lato","Lustria","Muli","Nunito","Open Sans","Oranienbaum","Oswald","PT Sans"]";s:22:"setting-default_layout";s:8:"sidebar1";s:27:"setting-default_post_layout";s:9:"list-post";s:30:"setting-default_layout_display";s:7:"excerpt";s:25:"setting-default_more_text";s:4:"More";s:21:"setting-index_orderby";s:4:"date";s:19:"setting-index_order";s:4:"DESC";s:26:"setting-default_post_title";s:0:"";s:33:"setting-default_unlink_post_title";s:0:"";s:25:"setting-default_post_meta";s:0:"";s:25:"setting-default_post_date";s:0:"";s:26:"setting-default_post_image";s:0:"";s:33:"setting-default_unlink_post_image";s:0:"";s:31:"setting-image_post_feature_size";s:5:"blank";s:24:"setting-image_post_width";s:0:"";s:25:"setting-image_post_height";s:0:"";s:24:"setting-image_post_align";s:0:"";s:32:"setting-default_page_post_layout";s:8:"sidebar1";s:31:"setting-default_page_post_title";s:0:"";s:38:"setting-default_page_unlink_post_title";s:0:"";s:30:"setting-default_page_post_meta";s:0:"";s:30:"setting-default_page_post_date";s:0:"";s:31:"setting-default_page_post_image";s:0:"";s:38:"setting-default_page_unlink_post_image";s:0:"";s:38:"setting-image_post_single_feature_size";s:5:"blank";s:31:"setting-image_post_single_width";s:0:"";s:32:"setting-image_post_single_height";s:0:"";s:31:"setting-image_post_single_align";s:0:"";s:27:"setting-default_page_layout";s:8:"sidebar1";s:23:"setting-hide_page_title";s:0:"";s:24:"setting-gallery_lightbox";s:8:"lightbox";s:19:"setting-entries_nav";s:8:"numbered";s:29:"setting-header_slider_enabled";s:2:"on";s:29:"setting-header_slider_display";s:5:"posts";s:36:"setting-header_slider_posts_category";s:1:"0";s:34:"setting-header_slider_posts_slides";s:1:"8";s:37:"setting-header_slider_default_display";s:4:"none";s:32:"setting-header_slider_hide_title";s:0:"";s:38:"setting-header_slider_images_one_title";s:0:"";s:37:"setting-header_slider_images_one_link";s:0:"";s:38:"setting-header_slider_images_one_image";s:0:"";s:38:"setting-header_slider_images_two_title";s:0:"";s:37:"setting-header_slider_images_two_link";s:0:"";s:38:"setting-header_slider_images_two_image";s:0:"";s:40:"setting-header_slider_images_three_title";s:0:"";s:39:"setting-header_slider_images_three_link";s:0:"";s:40:"setting-header_slider_images_three_image";s:0:"";s:39:"setting-header_slider_images_four_title";s:0:"";s:38:"setting-header_slider_images_four_link";s:0:"";s:39:"setting-header_slider_images_four_image";s:0:"";s:39:"setting-header_slider_images_five_title";s:0:"";s:38:"setting-header_slider_images_five_link";s:0:"";s:39:"setting-header_slider_images_five_image";s:0:"";s:38:"setting-header_slider_images_six_title";s:0:"";s:37:"setting-header_slider_images_six_link";s:0:"";s:38:"setting-header_slider_images_six_image";s:0:"";s:40:"setting-header_slider_images_seven_title";s:0:"";s:39:"setting-header_slider_images_seven_link";s:0:"";s:40:"setting-header_slider_images_seven_image";s:0:"";s:40:"setting-header_slider_images_eight_title";s:0:"";s:39:"setting-header_slider_images_eight_link";s:0:"";s:40:"setting-header_slider_images_eight_image";s:0:"";s:39:"setting-header_slider_images_nine_title";s:0:"";s:38:"setting-header_slider_images_nine_link";s:0:"";s:39:"setting-header_slider_images_nine_image";s:0:"";s:38:"setting-header_slider_images_ten_title";s:0:"";s:37:"setting-header_slider_images_ten_link";s:0:"";s:38:"setting-header_slider_images_ten_image";s:0:"";s:29:"setting-header_slider_visible";s:1:"5";s:26:"setting-header_slider_auto";s:1:"0";s:28:"setting-header_slider_scroll";s:1:"1";s:27:"setting-header_slider_speed";s:3:"300";s:26:"setting-header_slider_wrap";s:3:"yes";s:27:"setting-header_slider_width";s:0:"";s:28:"setting-header_slider_height";s:0:"";s:22:"setting-footer_widgets";s:17:"footerwidget-3col";s:24:"setting-footer_text_left";s:0:"";s:25:"setting-footer_text_right";s:0:"";s:27:"setting-global_feature_size";s:5:"large";s:22:"setting-link_icon_type";s:10:"image-icon";s:32:"setting-link_type_themify-link-0";s:10:"image-icon";s:33:"setting-link_title_themify-link-0";s:7:"Twitter";s:32:"setting-link_link_themify-link-0";s:26:"http://twitter.com/themify";s:31:"setting-link_img_themify-link-0";s:94:"http://themify.me/demo/themes/itheme2/wp-content/themes/itheme2/themify/img/social/twitter.png";s:32:"setting-link_type_themify-link-2";s:10:"image-icon";s:33:"setting-link_title_themify-link-2";s:7:"Google+";s:32:"setting-link_link_themify-link-2";s:45:"https://plus.google.com/102333925087069536501";s:31:"setting-link_img_themify-link-2";s:98:"http://themify.me/demo/themes/itheme2/wp-content/themes/itheme2/themify/img/social/google-plus.png";s:32:"setting-link_type_themify-link-1";s:10:"image-icon";s:33:"setting-link_title_themify-link-1";s:8:"Facebook";s:32:"setting-link_link_themify-link-1";s:27:"http://facebook.com/themify";s:31:"setting-link_img_themify-link-1";s:95:"http://themify.me/demo/themes/itheme2/wp-content/themes/itheme2/themify/img/social/facebook.png";s:32:"setting-link_type_themify-link-3";s:10:"image-icon";s:33:"setting-link_title_themify-link-3";s:7:"YouTube";s:32:"setting-link_link_themify-link-3";s:0:"";s:31:"setting-link_img_themify-link-3";s:94:"http://themify.me/demo/themes/itheme2/wp-content/themes/itheme2/themify/img/social/youtube.png";s:32:"setting-link_type_themify-link-4";s:10:"image-icon";s:33:"setting-link_title_themify-link-4";s:9:"Pinterest";s:32:"setting-link_link_themify-link-4";s:0:"";s:31:"setting-link_img_themify-link-4";s:96:"http://themify.me/demo/themes/itheme2/wp-content/themes/itheme2/themify/img/social/pinterest.png";s:22:"setting-link_field_ids";s:171:"{"themify-link-0":"themify-link-0","themify-link-2":"themify-link-2","themify-link-1":"themify-link-1","themify-link-3":"themify-link-3","themify-link-4":"themify-link-4"}";s:23:"setting-link_field_hash";s:1:"5";s:30:"setting-page_builder_is_active";s:6:"enable";s:23:"setting-hooks_field_ids";s:2:"[]";s:4:"skin";s:0:"";}<?php $themify_data = ob_get_clean();
	themify_set_data( unserialize( $themify_data ) );
	?>
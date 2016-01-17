<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'palette');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~AvrG|(R2,%38]Y_Dk]/r([1iS@sW,</Mh-w;ad}(2H4Np^bkdlO&VLfr`2edm9Z');
define('SECURE_AUTH_KEY',  'a]*m?I{V0r} PaRQ `nfnZ|OB7nK<Wy8_(O1TYD]JKu|t(oHp d,.asR=ICqWFqQ');
define('LOGGED_IN_KEY',    'm&9zD:7*Cjm&|X>[vm$FTtSMAflfY+y)Y<v$W^K/[o<CXg_,xo^;_)M,KY8EtETU');
define('NONCE_KEY',        ':1gwyl{[/!@lx9Lt]e.+X@,# w>5>9j|*}d}*Nz+H~y4P<+S]x;haZrE`oU^%]4T');
define('AUTH_SALT',        'X/I@nA^Ij*6C_PxP,a-:YWeI$Cz*QC~k|?W-K>*d;RK82fU%FC9_G-I46Yu`X2&A');
define('SECURE_AUTH_SALT', '*wLJ$m(Aua&h@pU( 9IL)$rVaaOg_lzcEWVbDX*iq-#)ZdK?lESIQ[sg_KbNy(`s');
define('LOGGED_IN_SALT',   'kqyIf{L@GD<_ ;C^~DTy}UFtK8.IyrXzEkHUeI%h~h=%|%/lM`P$K>+,h4lOWJ;j');
define('NONCE_SALT',       ':GFK?|Aw/jf,ZW*,D }>72VtM^qawP1D3L-o$Aw<+z$D|nDb@|` A!+>| O-UdEg');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
<?php
// Begin AIOWPSEC Firewall
if (file_exists('/var/www/s210333/data/www/piteronline.tv/aios-bootstrap.php')) {
	include_once('/var/www/s210333/data/www/piteronline.tv/aios-bootstrap.php');
}
// End AIOWPSEC Firewall
define( 'WP_CACHE', true ); // Added by WP Rocket



/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 's210333_wp103' );
/** Database username */
define( 'DB_USER', 's210333_wp103' );
/** Database password */
define( 'DB_PASSWORD', 'Ap-n!S0228' );
/** Database hostname */
define( 'DB_HOST', 'localhost' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'WP_POST_REVISIONS', 3 );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'cru3ilsvsfzcz5fcxjevbewr9kazz5ttx3dnxe4p7phfhcnpjzxansjzmzxjrx1e' );
define( 'SECURE_AUTH_KEY',  'batobkqw40wq4lzp5loko7ouyzeuvblwetz4sstyfm4q8t0zzlyv3x5p1zk20f8q' );
define( 'LOGGED_IN_KEY',    'ywgsz4s8qefj2chjgvj04jxcxewpqt0b4auifm1jmzelgeonhcc6xwobwhwbmcpi' );
define( 'NONCE_KEY',        '4v6e7wt1nolfqwofcf93gfdvoca4ripd5tpj1f6y2xibrfrnckhg7tw0hnor3muz' );
define( 'AUTH_SALT',        'qbqvcooxx6vocwetfhpbwhx3pqaapordxfusfga0rdqknkv16j6ipannnmvnr1e3' );
define( 'SECURE_AUTH_SALT', 'l9vgfonmpbio6rtcvvfelji2tvrkavgglwz6jari0mzmqx2oehhhkwcnozikmldw' );
define( 'LOGGED_IN_SALT',   '6bekaot4t8xvzniuap9nnrdrceqoxexdszvrtvvaocyzbosmxh6sm7cg6tbeuoks' );
define( 'NONCE_SALT',       'scvbwqmdtdrhuqvzysnhjjypcxhfolxxj6f99vzgruu12zou59jbpkcukyivhjit' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpst_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define('WP_DEBUG', false);
/**
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
*/


/* Add any custom values between this line and the "stop editing" line. */

define('DISABLE_WP_CRON', true); // Disable Default WordPress Cron System


//define( 'WP_ALLOW_MULTISITE', true );
//define( 'MULTISITE', true );
//define( 'SUBDOMAIN_INSTALL', false );
//define( 'DOMAIN_CURRENT_SITE', 'piteronline.tv' );
//define( 'PATH_CURRENT_SITE', '/' );
//define( 'SITE_ID_CURRENT_SITE', 1 );
//define( 'BLOG_ID_CURRENT_SITE', 1 );


/** Отключение редактирования файлов плагинов и тем через административную панель. */
define( 'DISALLOW_FILE_EDIT', true );

define('WP_MEMORY_LIMIT', '64M');

/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */

/*@DOCKETCACHE-RUNTIME-BEGIN*/
if(!\function_exists('docketcache_runtime')){
 function docketcache_runtime(){
  if(!(\PHP_VERSION_ID >= 70205)) {return;}
  try{
   $path="/var/www/s210333/data/www/piteronline.tv/wp-content/docket-cache-data";
   $runtime=$path."/runtime.php";
   if(is_file($runtime)){include_once $runtime;}
  }catch(\Throwable $e){}
 }
 docketcache_runtime();
}
/*@DOCKETCACHE-RUNTIME-END*/
require_once ABSPATH . 'wp-settings.php';
<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'rhode_resort');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'FKXdHLRDkak3lMb$aGoPr0mt:`m*4s@Ab=}S/:p3UEC-Q11pvjpJEx?7([,l`|Pv');
define('SECURE_AUTH_KEY',  'l}e;Huv.8)Up}`&hyqAoJ_t`UZC%Sm.4IS*2na T+W&2 #a?,EZ{AR>=^: 6?dm>');
define('LOGGED_IN_KEY',    '7wn7& ?3+CAB%lJO:2Gn2q.-kl~0ShadT@1omhSYg;UQ(I_&Aa#hN?Bb.ve{1l@q');
define('NONCE_KEY',        'W?y_(p}H}wX~f:S:#*BCP$cCbSVy8dMR=m2V!D$jkC@OUR0{8e5Q$4}5k&6WM0H{');
define('AUTH_SALT',        '*eL0HzwV)/hCZN8hNMWB%/qW+G,@:wdQ<<9-0$a.A<vV.}JhzpcwzN:20]yAM$S~');
define('SECURE_AUTH_SALT', '!}vz1@+_b4jl#kp9Bs{Cn|MC3L]pJAkS)[R2g!Azy?-Dq&cgTfk{zCu[wiI_65Nv');
define('LOGGED_IN_SALT',   '+0LMZ%:&+7:FWHl/]ygPP29d7SvKnS3_#t|ea0 d3]BbH%+aB#Z/o@PH253t e{H');
define('NONCE_SALT',       '/<6Mup3sp0T41Y*$deaI?K7<OF;wq# )7^uvFq[#D$ljsSnh5CtsG&HC#K)O/[&@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'rr_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

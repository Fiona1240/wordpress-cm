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
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'DBI_AWS_ACCESS_KEY_ID', '' );
define( 'DBI_AWS_SECRET_ACCESS_KEY', '' );
define( 'HRLG_AWS_HOST_URI', '' );//URL Format https://subdomain.domain.domainextention
define( 'HRLG_AWS_CF_HOST_URI', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pXV+yCV@~0X<@RX+`,v{wSYyXi:l`T$<GQ|{4#|$DFUl}HEz&f0eRFqG#4KY0,p[');
define('SECURE_AUTH_KEY',  ',=JGDk7#7E<+fZ|*JTlFGS)$2lEr:~@ra%SUA>?-ohRU`2k%xx,qV56(Y3TlgMQM');
define('LOGGED_IN_KEY',    'Y]?TU9Cj8;&Rn?H66+AM  pGA!#}J@;<E?y$`=S[L6vU<M[vp$WiR244i?+:t9q>');
define('NONCE_KEY',        '!_sujOb:Z?R#dRtl=kFmSD)X$K<`Vqz[oEkiTJ7 OY(=[vH60cf<)&|yZp~.!l0j');
define('AUTH_SALT',        'Tj^lEAZ?VgD1F/1xE,]Brj)fna$; t:C3}Vz^|,F9L8DOTMXJ7B0V.&Gba4-*Mej');
define('SECURE_AUTH_SALT', '#:g62tbMKwmWJH(NPZ&`q:<i70li;_i9B=8R-M.+ zj_L8 yCg~*j}1*HaU20S.A');
define('LOGGED_IN_SALT',   'z?`G}1OJyJv@[M}8/I[#DGM:7b?u=u.<suP$sUqZFXw7H3SHu^K.TkLtq</=Z,,S');
define('NONCE_SALT',       'l]};k{Z_ESx+OSilx5&zSyKsV;xo9Sef0xYqH?eHeHK-_^5enAL`_#nR+z0kIbA%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpcm_';

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

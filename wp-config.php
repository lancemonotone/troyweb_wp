<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

// Use WP_ENVIRONMENT_TYPE for environment-specific configurations
if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
    define( 'WP_ENVIRONMENT_TYPE', $_ENV['WP_ENV'] );
}

// Flag to easily toggle debug mode
define( 'IS_DEBUG_MODE', WP_ENVIRONMENT_TYPE === 'local' || WP_ENVIRONMENT_TYPE === 'development' );

// Site settings
$table_prefix = $_ENV['TABLE_PREFIX'] ?? 'wp_';
define( 'DB_NAME', $_ENV['DB_NAME'] );
define( 'DB_USER', $_ENV['DB_USER'] );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );
define( 'DB_HOST', $_ENV['DB_HOST'] );

// Environment settings
define( 'WP_HOME', $_ENV['WP_HOME'] );
define( 'WP_SITEURL', $_ENV['WP_SITEURL'] );

// Debug settings
if ( IS_DEBUG_MODE ) {
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
    define( 'SCRIPT_DEBUG', true );
    define( 'SAVEQUERIES', true );
    define( 'JETPACK_DEV_DEBUG', true );
    define( 'FORCE_SSL_ADMIN', true );
    define( 'FORCE_SSL_LOGIN', true );
    define( 'DISALLOW_FILE_EDIT', false );
    define( 'DISALLOW_FILE_MODS', false );
    define( 'AUTOMATIC_UPDATER_DISABLED', false );
    define( 'WP_AUTO_UPDATE_CORE', true );
    define( 'WP_ALLOW_REPAIR', true );
}

// Check if the environment is local before applying these settings
if (WP_ENVIRONMENT_TYPE === 'local') {
// File system settings
    define( 'FS_METHOD', 'direct' );
    define( 'FS_CHMOD_DIR', 0775 );
    define( 'FS_CHMOD_FILE', 0664 );
// Performance settings
    define('WP_CACHE', false);
    define('WP_MEMORY_LIMIT', '256M');
    define('WP_MAX_MEMORY_LIMIT', '512M');
    define('EMPTY_TRASH_DAYS', 0);
    define('WP_POST_REVISIONS', 0);
    define('DISABLE_WP_CRON', true);
    define('WP_CRON_LOCK_TIMEOUT', 60);
}

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',          '.mj`<YiT}]_gCwqqnKY,(|Iq?3p;r247dW-^F^7&VtWjeZlfF.~NkwuH8N? hxm ' );
define( 'SECURE_AUTH_KEY',   '=&<O/DNO+-Z>E#=i3(Ay9)25bI/1p;wRS_P)+OVQnHG$*6^^_]7^ys}}7],c7zc|' );
define( 'LOGGED_IN_KEY',     '^*FiV4-i>$F)0a8A*A|5X!@QV<C<R{t4eyL/k-3QzwVQ}Yf,.f}LY>kb]6xBTl22' );
define( 'NONCE_KEY',         '-]iqQtxg]CKqaAor62+7qw@+xfVoGhOG!D<zsz*f;MNNI@?9nXhUD{fn)co5;r;O' );
define( 'AUTH_SALT',         '}M#/osO=wEWzrcJy+(KB~<n~@|+qYia^sX7e%3l8D3#GnK2:3s>jCa}:|t}?]:tc' );
define( 'SECURE_AUTH_SALT',  '}g%kV`X%454]900H*Lf#+,Kvn 8l9*[5he(@hDl2X[y{M}w}_i]!#A89sNI$~[}4' );
define( 'LOGGED_IN_SALT',    'f=-b&>p|~+)!d/ fqPoZnhGjb.|BTI![0;%ky$;c$ONk%d9YaQSvXC`2VV~p2!-v' );
define( 'NONCE_SALT',        '*y-/o|hVnKWMr_F?$(G,S>`Dx|zmdMAp*8D]&RV6Y<*x*2feZ0 V!.<(0Wx3w`[W' );
define( 'WP_CACHE_KEY_SALT', 'EHspa9gW?S%cJj%hb~CjJaO*g-L`L^E%ynS,lCSD/`pt48+v[ZHl&KpBE>T].B$C' );

/**#@-*/

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
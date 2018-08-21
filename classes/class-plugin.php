<?php

namespace Kntnt\Taxonomy_Meta_Tag;

class Plugin {

	static private $ns;

	static private $plugin_dir;

	static private $context;

	private $classes_to_load;

	public function __construct( $classes_to_load, $start_hook = 'plugins_loaded' ) {

		// Set self::$ns
		self::$ns = strtr( strtolower( __NAMESPACE__ ), '_\\', '--' );

		// Set self::$plugin_dir
		self::$plugin_dir = strtr( dirname( __DIR__ ), '\\', '/' );

		// Set $self::$context
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			self::$context = 'cli';
		}
		else {
			$ctx = $_SERVER['SCRIPT_FILENAME'];
			$ctx = substr( $ctx, 0, - 4 );
			$ctx = substr( $ctx, strlen( ABSPATH ) );
			while ( ( $c = dirname( $ctx ) ) !== '.' ) {
				$ctx = $c;
			}
			$ctx = str_ireplace( 'wp-', '', $ctx );
			if ( $ctx === 'content' ) {
				$ctx = pathinfo( $_SERVER['SCRIPT_FILENAME'], PATHINFO_FILENAME );
			}
			self::$context = $ctx;
		}

		// Set $this->classes_to_load
		$this->classes_to_load = $classes_to_load;

		// Install script runs only on install (not activation).
		// Uninstall script runs "magically" on uninstall.
		register_activation_hook( self::$plugin_dir . '/' . self::$ns . '.php', function () {
			if ( null === get_option( self::$ns, null ) ) {
				require_once self::$plugin_dir . '/install.php';
			}
		} );

		// Setup localization.
		add_action( 'plugins_loaded', function () {
			load_plugin_textdomain( self::$ns, false, self::$ns . '/languages' );
		} );

		// Setup this plugin to run.
		add_action( $start_hook, [ $this, 'run' ] );

	}

	// Loads classes.
	public function run() {
		if ( isset( $this->classes_to_load ) && isset( $this->classes_to_load[ self::$context ] ) ) {
			foreach ( $this->classes_to_load[ self::$context ] as $class ) {
				$this->instance( $class )->run();
			}
		}
	}

	// Namespace of plugin.
	static public function ns() {
		return self::$ns;
	}

	// Absolute path of plugin directory. No trailing slash.
	static public function plugin_dir( $rel_path = '' ) {
		return rtrim( self::$plugin_dir, '/' ) . '/' . ltrim( $rel_path, '/' );
	}

	// Returns the context in which the plugin is executed.
	// Possible vales includes index, admin, login, cron and wp-cli.
	static public function context() {
		return self::$context;
	}

	// Returns an instance of the class with the provided name.
	static public function instance( $class_name ) {
		$n = strtr( strtolower( $class_name ), '_', '-' );
		$class_name = __NAMESPACE__ . '\\' . $class_name;
		require_once self::$plugin_dir . "/classes/class-$n.php";
		return new $class_name;
	}

	// If $key is left out or empty, e.g. `Plugin::option()`, returns an array
	// with this plugins all options if existing, otherwise $default.
	// If $key is included and non-empty, e.g. `Plugin::option('key')`, returns
	// `Plugin::option()['key']` if the aforementioned array has an index 'key',
	// otherwise $default.
	static public function option( $key = '', $default = false ) {
		$opt = get_option( self::$ns, null );
		if ( $opt === null ) {
			return $default;
		}
		if ( empty( $key ) ) {
			return $opt;
		}
		return isset( $opt[ $key ] ) ? $opt[ $key ] : $default;
	}

}

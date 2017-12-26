<?php

namespace WPDM;

class Core {
	/**
	 * Instance of the Domains
	 *
	 * @var \WPDM\Domains
	 */
	public $domains;

	/**
	 * Instance of the Servers
	 *
	 * @var \WPDM\Servers
	 */
	public $servers;

	/**
	 * Instance of the Whois
	 *
	 * @var \WPDM\Whois\Whois
	 */
	public $whois;

	/**
	 * Instance of the IndexChecker
	 *
	 * @var \WPDM\Google\IndexChecker
	 */
	public $index_checker;

	/**
	 * Core constructor.
	 */
	public function __construct() {
		$this->domains       = new Domains();
		$this->servers       = new Servers();
		$this->whois         = new Whois\Whois();
		$this->index_checker = new Google\IndexChecker();
	}

	/**
	 * Register Core Hooks
	 */
	public function register() {
		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'init', array( $this, 'init' ) );

		$this->domains->register();
		$this->servers->register();

		do_action( 'wpdm_loaded' );
	}

	/**
	 * Registers the default textdomain.
	 *
	 * @uses apply_filters()
	 * @uses get_locale()
	 * @uses load_textdomain()
	 * @uses load_plugin_textdomain()
	 * @uses plugin_basename()
	 *
	 * @return void
	 */
	function i18n() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpdm' );
		load_textdomain( 'wpdm', WP_LANG_DIR . '/wpdm/wpdm-' . $locale . '.mo' );
		load_plugin_textdomain( 'wpdm', false, plugin_basename( WPDM_PATH ) . '/languages/' );
	}

	/**
	 * Initializes the plugin and fires an action other plugins can hook into.
	 *
	 * @uses do_action()
	 *
	 * @return void
	 */
	function init() {
		do_action( 'wpdm_init' );
	}
}

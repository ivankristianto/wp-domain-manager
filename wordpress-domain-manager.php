<?php
/**
 * Plugin Name: WordPress Domain Manager
 * Plugin URI:  https://www.ivankristianto.com/wp-domain-manager
 * Description: WordPress Domain Manager
 * Version:     0.1.0
 * Author:      Ivan Kristianto
 * Author URI:  https://www.ivankristianto.com
 * Text Domain: wpdm
 * Domain Path: /languages
 * License:     GPL-2.0+
 */

/**
 * Copyright (c) 2017 Ivan Kristianto (email : ivan@ivankristianto.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once __DIR__ . '/vendor/autoload.php';

// Useful global constants
define( 'WPDM_VERSION', '0.1.0' );
define( 'WPDM_URL', plugin_dir_url( __FILE__ ) );
define( 'WPDM_PATH', dirname( __FILE__ ) . '/' );
define( 'WPDM_INC', WPDM_PATH . 'includes/' );

// post type and taxonomy constants
define( 'WPDM_POST_TYPE_DOMAIN', 'domain' );
define( 'WPDM_TAXONOMY_SERVER', 'server' );

// Activation/Deactivation
register_activation_hook( __FILE__, 'wpdm_activate' );
register_deactivation_hook( __FILE__, 'wpdm_deactivate' );

/**
 * Activate the plugin
 *
 * @uses init()
 * @uses flush_rewrite_rules()
 *
 * @return void
 */
function wpdm_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function wpdm_deactivate() {

}

/**
 * Initializes core plugin and returns its instance.
 *
 * @global \WPDM\Core $wpdm_core
 * @return \WPDM\Core
 */
function wpdm_core() {
	global $wpdm_core;

	if ( empty( $wpdm_core ) ) {
		$wpdm_core = new WPDM\Core();
		$wpdm_core->register();
	}

	return $wpdm_core;
}

//Bootstrap
wpdm_core();

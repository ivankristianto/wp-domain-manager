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

/**
 * Built using yo wp-make:plugin
 * Copyright (c) 2017 10up, LLC
 * https://github.com/10up/generator-wp-make
 */

// Useful global constants
define( 'WPDM_VERSION', '0.1.0' );
define( 'WPDM_URL',     plugin_dir_url( __FILE__ ) );
define( 'WPDM_PATH',    dirname( __FILE__ ) . '/' );
define( 'WPDM_INC',     WPDM_PATH . 'includes/' );

// Include files
require_once WPDM_INC . 'functions/core.php';


// Activation/Deactivation
register_activation_hook( __FILE__, '\WPDM\Core\activate' );
register_deactivation_hook( __FILE__, '\WPDM\Core\deactivate' );

// Bootstrap
WPDM\Core\setup();

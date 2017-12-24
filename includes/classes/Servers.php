<?php
namespace WPDM;

use WPDM\Server\ServerTaxonomy;

class Servers {
	/**
	 * Instance of the domain post type
	 *
	 * @var \WPDM\Server\ServerTaxonomy
	 */
	public $server_taxonomy;

	/**
	 * Articles constructor.
	 */
	public function __construct() {
		$this->server_taxonomy = new ServerTaxonomy();
	}

	/**
	 * Register the post type.
	 */
	public function register() {
		$this->server_taxonomy->register();
	}
}

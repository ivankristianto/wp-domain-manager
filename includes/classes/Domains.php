<?php
namespace WPDM;

use \WPDM\Domain\DomainPostType;


class Domains {
	/**
	 * Instance of the domain post type
	 *
	 * @var \WPDM\Domain\DomainPostType
	 */
	public $domain;

	/**
	 * Articles constructor.
	 */
	public function __construct() {
		$this->domain = new DomainPostType;
	}

	/**
	 * Register the post type.
	 */
	public function register() {
		$this->domain->register();

	}

	/**
	 * Registers article post type in a list of supported post types.
	 *
	 * @access public
	 * @param array $post_types
	 * @return array
	 */
	public function opt_in( $post_types ) {
		$post_types[] = WPDM_POST_TYPE_DOMAIN;
		return $post_types;
	}
}

<?php
namespace WPDM;

use WPDM\Domain\DomainMetabox;
use \WPDM\Domain\DomainPostType;


class Domains {
	/**
	 * Instance of the domain post type
	 *
	 * @var \WPDM\Domain\DomainPostType
	 */
	public $domain;

	/**
	 * Instance of the domain metabox
	 *
	 * @var \WPDM\Domain\DomainMetabox
	 */
	public $domain_metabox;

	/**
	 * Articles constructor.
	 */
	public function __construct() {
		$this->domain         = new DomainPostType();
		$this->domain_metabox = new DomainMetabox( $this->domain->get_name() );
	}

	/**
	 * Register the post type.
	 */
	public function register() {
		$this->domain->register();
		$this->domain_metabox->register();
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

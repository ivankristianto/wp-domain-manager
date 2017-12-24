<?php

namespace WPDM\Server;

use WPDM\Taxonomy;

class ServerTaxonomy extends Taxonomy {
	/**
	 * Hold nonce field
	 */
	const NONCE_FIELD = '__wpdm_server_nonce';

	/**
	 * Hold nonce action
	 */
	const NONCE_ACTION = 'wpdm-server-action';

	/**
	 * Hold Taxonomy Name
	 * @var string
	 */
	public $taxonomy_name = WPDM_TAXONOMY_SERVER;

	/**
	 * @var string
	 */
	public $singular_label = 'Server';

	/**
	 * @var string
	 */
	public $plural_label = 'Servers';

	/**
	 * Hold post type assigned
	 *
	 * @var string|array
	 */
	public $post_types;

	/**
	 * Register the taxonomy
	 *
	 * @return void
	 */
	public function register() {
		parent::register();
		add_filter( "wpdm_filter_{$this->taxonomy_name}_taxonomy_options", array( $this, 'filter_taxonomy_options' ) );
	}

	/**
	 * Get the taxonomy name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->taxonomy_name;
	}

	/**
	 * Get the taxonomy singular label
	 *
	 * @return string
	 */
	public function get_singular_label() {
		return $this->singular_label;
	}

	/**
	 * Get the taxonomy plural label
	 *
	 * @return string
	 */
	public function get_plural_label() {
		return $this->plural_label;
	}

	/**
	 * Get the post type(s) the taxonomy is attached to
	 *
	 * @return string|array
	 */
	public function get_post_types() {
		$post_types = array( WPDM_POST_TYPE_DOMAIN );
		$post_types = (array) apply_filters( "wpdm_filter_{$this->taxonomy_name}_taxonomy_post_types", $post_types );

		return $post_types;
	}

	/**
	 * Add extra option to taxonomy options
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public function filter_taxonomy_options( $options ) {
		$options['show_admin_column'] = true;
		$options['public']            = false;

		return $options;
	}
}

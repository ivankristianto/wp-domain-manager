<?php
namespace WPDM\Domain;

use WPDM\PostType;

class DomainPostType extends PostType {
	/**
	 * Register the post type
	 */
	public function register() {
		parent::register();
	}

	/**
	 * Get the post type name
	 *
	 * @return string
	 */
	public function get_name() {
		return WPDM_POST_TYPE_DOMAIN;
	}

	/**
	 * Get the post type singular label
	 *
	 * @return string
	 */
	public function get_singular_label() {
		return 'Domain';
	}

	/**
	 * Get the post type plural label
	 *
	 * @return string
	 */
	public function get_plural_label() {
		return 'Domains';
	}

	/**
	 * Get the post type options.
	 *
	 * @return array
	 */
	public function get_options() {
		$options = parent::get_options();

		$options['menu_icon']     = 'dashicons-admin-site';
		$options['menu_position'] = 5;

		if ( ! isset( $options['taxonomies'] ) ) {
			$options['taxonomies'] = array();
		}

		$options['taxonomies'][] = 'category';
		$options['taxonomies'][] = 'post_tag';
		$options['supports']     = array( 'title', 'editor', 'thumbnail' );

		return $options;
	}
}

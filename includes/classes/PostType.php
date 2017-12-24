<?php

namespace WPDM;

/**
 * Base class for post types.
 */
abstract class PostType {

	/**
	 * @var array Components assigned to this post type.
	 */
	public $components = array();

	/**
	 * Register the post type class.
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Get the post type name.
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	abstract public function get_singular_label();

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	abstract public function get_plural_label();

	/**
	 * Register the post type.
	 */
	public function register_post_type() {
		\register_post_type( $this->get_name(), $this->get_options() );
	}

	/**
	 * @param $component Metabox
	 */
	public function register_component( $component ) {
		$component->register();
		$this->components[] = $component;
	}

	/**
	 * Get post type labels.
	 *
	 * @return array
	 */
	public function get_labels() {
		$labels = $this->get_default_labels();
		return apply_filters( "wpdm_filter_{$this->get_name()}_post_type_labels", $labels );
	}

	/**
	 * Get the default post type labels.
	 *
	 * @return array
	 */
	public function get_default_labels() {
		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		return array(
			'name'               => $plural_label,
			'singular_name'      => $singular_label,
			'all_items'          => sprintf( 'All %s', $plural_label ),
			'add_new_item'       => sprintf( 'Add New %s', $singular_label ),
			'edit_item'          => sprintf( 'Edit %s', $singular_label ),
			'new_item'           => sprintf( 'New %s', $singular_label ),
			'view_item'          => sprintf( 'View %s', $singular_label ),
			'search_items'       => sprintf( 'Search %s', $plural_label ),
			'not_found'          => sprintf( 'No %s found.', strtolower( $plural_label ) ),
			'not_found_in_trash' => sprintf( 'No %s found in Trash.', strtolower( $plural_label ) ),
			'parent_item_colon'  => sprintf( 'Parent %s:', $plural_label ),
		);
	}

	/**
	 * Get the post type options.
	 *
	 * @return array
	 */
	public function get_options() {
		$options = $this->get_default_options();
		return apply_filters( "wpdm_filter_{$this->get_name()}_post_type_options", $options );
	}

	/**
	 * Get the default post type options.
	 *
	 * @return array
	 */
	public function get_default_options() {
		return array(
			'labels'            => $this->get_labels(),
			'public'            => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
		);
	}

}

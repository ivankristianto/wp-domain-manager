<?php

namespace WPDM;

/**
 * Base class for metabox
 */
abstract class Metabox {

	/**
	 * Hold post type value
	 *
	 * @var string
	 */
	protected $post_type = array();

	/**
	 * Hold meta keys options
	 *
	 * @var array
	 */
	protected $meta_keys = array();

	/**
	 * Metabox constructor.
	 *
	 * @param string $post_type
	 */
	function __construct( $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Get meta key
	 *
	 * @param $meta_key
	 *
	 * @return null|object
	 */
	public function __get( $meta_key ) {
		if ( isset( $this->$meta_key ) ) {
			return $this->$meta_key;
		}

		if ( ! isset( $this->meta_keys[ $meta_key ] ) ) {
			return null;
		}

		return (object) $this->meta_keys[ $meta_key ];
	}

	/**
	 * Register hook & actions
	 *
	 */
	public function register() {

		add_action( 'add_meta_boxes_' . $this->post_type, array( $this, 'add_meta_boxes' ), 10, 2 );

		if ( ! defined( 'WP_CLI' ) ) {
			add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ), 10, 3 );
		}
	}

	/**
	 * Add metaboxes to edit screen
	 *
	 * @param \WP_Post $post
	 */
	abstract public function add_meta_boxes( $post );

	/**
	 * Save meta data
	 *
	 * @param int $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
	abstract public function save_metabox( $post_id, $post, $update );
}

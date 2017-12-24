<?php

namespace WPDM;

/**
 * Base class for taxonomies.
 */
abstract class Taxonomy {

	/**
	 * The post type slug this taxonomy should mirror.
	 *
	 * @var null|string
	 */
	public $post_type_to_mirror = null;

	/**
	 * Get the taxonomy name.
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Get the post types the taxonomy should be attached to.
	 *
	 * @return array
	 */
	abstract public function get_post_types();

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	abstract public function get_singular_label();

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	abstract public function get_plural_label();

	/**
	 * Register the taxonomy class.
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_taxonomy' ), 20 );

		if ( $this->is_mirror_taxonomy() ) {
			add_filter( 'pre_insert_term', array( $this, 'filter_pre_insert_term' ), 0, 2 );
			add_action( 'admin_footer', array( $this, 'hide_add_term_links' ) );
		}
	}

	/**
	 * Register the taxonomy.
	 */
	public function register_taxonomy() {
		\register_taxonomy(
			$this->get_name(), $this->get_post_types(), $this->get_options()
		);

		if ( $this->is_mirror_taxonomy() ) {
			$this->register_mirror_taxonomy();
		}
	}

	/**
	 * If this is a mirror taxonomy, set up the post type relationship.
	 */
	public function register_mirror_taxonomy() {
		if (
			$this->is_mirror_taxonomy() &&
			function_exists( '\TDS\add_relationship' )
		) {
			\TDS\add_relationship( $this->post_type_to_mirror, $this->get_name() );
		}
	}

	/**
	 * Determine if this taxonomy is a mirror taxonomy.
	 *
	 * @return boolean
	 */
	public function is_mirror_taxonomy() {
		return ! empty( $this->post_type_to_mirror );
	}

	/**
	 * Get the taxonomy labels.
	 *
	 * @return array
	 */
	public function get_labels() {
		$labels = $this->get_default_labels();
		return apply_filters( "wpdm_filter_{$this->get_name()}_taxonomy_labels", $labels );
	}

	/**
	 * Get the default taxonomy labels.
	 *
	 * @return array
	 */
	public function get_default_labels() {
		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		return array(
			'name'              => $plural_label,
			'singular_name'     => $singular_label,
			'all_items'         => sprintf( 'All %s', $plural_label ),
			'edit_item'         => sprintf( 'Edit %s', $singular_label ),
			'view_item'         => sprintf( 'View %s', $singular_label ),
			'update_item'       => sprintf( 'Update %s', $singular_label ),
			'add_new_item'      => sprintf( 'Add New %s', $singular_label ),
			'new_item_name'     => sprintf( 'New %s Name', $singular_label ),
			'parent_item'       => sprintf( 'Parent %s', $singular_label ),
			'parent_item_colon' => sprintf( 'Parent %s:', $singular_label ),
			'search_items'      => sprintf( 'Search %s', $plural_label ),
			'popular_items'     => sprintf( 'Popular %s', $plural_label ),
			'not_found'         => sprintf( 'No %s found.', strtolower( $plural_label ) ),
		);
	}

	/**
	 * Get the taxonomy options.
	 *
	 * @return array
	 */
	public function get_options() {
		$options = $this->get_default_options();
		return apply_filters( "wpdm_filter_{$this->get_name()}_taxonomy_options", $options );
	}

	/**
	 * Get the default taxonomy options.
	 *
	 * @return array
	 */
	public function get_default_options() {
		$options = array(
			'labels'            => $this->get_labels(),
			'public'            => true,
			'show_admin_column' => false,
			'show_ui'           => true,
			'hierarchical'      => true,
			'show_in_nav_menus' => false,
		);

		if ( $this->is_mirror_taxonomy() ) {
			$options['show_in_menu']      = false;
			$options['show_in_nav_menus'] = false;
			$options['show_tagcloud']     = false;
		}

		return $options;
	}

	/**
	 * Filter a term before it is sanitized and inserted into the database. If this is a mirror taxonomy, don't allow terms to be inserted through the admin dashboard UI.
	 *
	 * @param string $term     The term to add or update.
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return string|\WP_Error
	 */
	public function filter_pre_insert_term( $term, $taxonomy ) {
		global $pagenow, $typenow;

		// Continue if creating a term in another taxonomy.
		if ( $taxonomy !== $this->get_name() ) {
			return $term;
		}

		// Continue if this is not a mirror taxonomy.
		if ( ! $this->is_mirror_taxonomy() ) {
			return $term;
		}

		// Continue if creating a term when adding a new post in the mirrored post type.
		if ( $typenow === $this->post_type_to_mirror ) {
			return $term;
		}

		$disallowed_pages = array(
			'post-new.php',
			'post.php',
			'edit-tags.php',
			'admin-ajax.php',
		);

		if ( in_array( $pagenow, $disallowed_pages ) ) {
			return new \WP_Error(
				'term_addition_blocked',
				'You cannot add terms to this taxonomy from the administration dashboard.'
			);
		}

		return $term;
	}

	/**
	 * Create and/or get a term from this taxonomy.
	 *
	 * @param  string $term_name The term name
	 * @return \WP_Term The term object
	 */
	public function create_or_get_term( $term_name ) {
		$taxonomy = $this->get_name();

		if ( ! term_exists( $term_name, $taxonomy ) ) {
			$inserted_term = wp_insert_term( $term_name, $taxonomy );
		}

		$term = get_term_by( 'name', $term_name, $taxonomy );

		return $term;
	}

	/**
	 * Hide the add term link from this taxonomy's meta box. Normally, styles like this should be added in a global stylesheet. In this case, they're printed to the footer so they can be added automatically without having to keep a stylesheet up to date when new taxonomies are added.
	 */
	public function hide_add_term_links() {
		global $pagenow;

		if ( ! $this->is_mirror_taxonomy() ) {
			return;
		}

		$disallowed_pages = array(
			'post-new.php',
			'post.php',
		);

		if ( in_array( $pagenow, $disallowed_pages ) ) {
			$selector = sprintf( '#%s-adder', $this->get_name() );
			?>
			<style>
				<?php echo esc_html( $selector ) ?> { display: none; }
			</style>
			<?php
		}
	}

	/**
	 * Adds taxonomy name to the opt in list.
	 *
	 * @access public
	 * @param array $list
	 * @return array
	 */
	public function opt_in( $list ) {
		if ( is_array( $list ) ) {
			$list[] = $this->get_name();
		}

		return $list;
	}

	/**
	 * Adds taxonomy object to the opt in list.
	 *
	 * @access public
	 * @param array $list
	 * @return array
	 */
	public function ep_opt_in( $list ) {
		if ( is_array( $list ) && $taxonomy = get_taxonomy( $this->get_name() ) ) {
			$list[] = $taxonomy;
		}

		return $list;
	}


}

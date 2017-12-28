<?php
/**
 * Created by PhpStorm.
 * User: ivankristianto
 * Date: 25/12/17
 * Time: 02.11
 */

namespace WPDM\Domain;


use WPDM\Metabox;

class DomainMetabox extends Metabox {
	const NONCE_FIELD  = '__domain_nonce';
	const NONCE_ACTION = 'domain-nonce-action';

	/**
	 * Metabox constructor.
	 *
	 * @param string $post_type
	 */
	function __construct( $post_type ) {
		$this->meta_keys = array(
			'domain' => array(
				'key'         => 'wpdm_domain_url',
				'label'       => 'Full URL (with http:// or https://)',
				'sanitize_cb' => 'sanitize_text_field',
			),
			'notes'  => array(
				'key'         => 'wpdm_domain_notes',
				'label'       => 'Notes',
				'sanitize_cb' => 'sanitize_textarea_field',
			),
		);

		parent::__construct( $post_type );
	}

	/**
	 * Add metaboxes to Snippet Post Type
	 *
	 * @param $post
	 */
	public function add_meta_boxes( $post ) {
		add_meta_box(
			'wpdm_domain_meta_box',
			'Domain Information',
			array( $this, 'display_metabox' ),
			$this->post_type
		);
	}

	/**
	 * Out metabox html markup
	 *
	 * @param \WP_Post $post
	 */
	public function display_metabox( $post ) {
		if ( ! is_a( $post, '\WP_Post' ) ) {
			return;
		}

		$domain = get_post_meta( $post->ID, $this->domain->key, true );
		$notes  = get_post_meta( $post->ID, $this->notes->key, true );

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD, false );

		?>
		<div class="wrap">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $this->domain->key ); ?>"><?php echo esc_html( $this->domain->label ); ?></label>
				</th>
				<td>
					<input type="text" class="large-text" name="<?php echo esc_attr( $this->domain->key ); ?>" value="<?php echo esc_attr( $domain ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $this->notes->key ); ?>"><?php echo esc_html( $this->notes->label ); ?></label>
				</th>
				<td>
				<textarea name="<?php echo esc_attr( $this->notes->key ); ?>" id="<?php echo esc_attr( $this->notes->key ); ?>" rows="10" class="large-text">
					<?php echo esc_textarea( $notes ); ?>
					</textarea>
				</td>
			</tr>
			</tbody>
		</table>
		</div>
		<?php
	}

	/**
	 * Save meta data
	 *
	 * @param int $post_id Post ID.
	 * @param \WP_Post $post Post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 */
	public function save_metabox( $post_id, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$post_type = get_post_type_object( WPDM_POST_TYPE_DOMAIN );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE_FIELD ] ), self::NONCE_ACTION ) ) { //Input var okay.
			return;
		}

		foreach ( $this->meta_keys as $meta_key => $options ) {
			if ( isset( $_POST[ $this->$meta_key->key ] ) ) { //Input var okay.
				// We are not saving anything if not sanitized
				if ( null !== $this->$meta_key->sanitize_cb && function_exists( $this->$meta_key->sanitize_cb ) ) {
					$value_safe = call_user_func( $this->$meta_key->sanitize_cb, $_POST[ $this->$meta_key->key ] ); //Input var okay.
					update_post_meta( $post_id, $this->$meta_key->key, $value_safe );
				}
			}
		}
	}
}

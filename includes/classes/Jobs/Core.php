<?php
namespace WPDM\Jobs;

class Core {

	public function register() {
		// Actions
		add_action( 'save_post', array( $this, 'check_single_domain' ), 10, 1 );
		add_action( 'schedule_wpdm_single_domain', array( __CLASS__, 'fetch_single_domain_data' ), 10, 1 );
		add_action( 'schedule_wpdm_bulk_domain', array( __CLASS__, 'process_bulk_domain_data' ), 10, 1 );
	}
	/**
	 * Schedule an event to check domain
	 *
	 * @param int $post_id
	 */
	public function check_single_domain( $post_id ) {
		if ( defined( 'WP_CLI' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Finally, the last check.
		if ( 'publish' === get_post_status( $post_id ) ) {
			wp_schedule_single_event( time() + 10, 'schedule_wpdm_single_domain', [ $post_id ] );
		}
	}

	/**
	 * Register our Cron Event
	 */
	public function register_cron() {
		if ( ! wp_next_scheduled( 'schedule_wpdm_bulk_domain' ) ) {
			wp_schedule_event( time(), 'hourly', 'schedule_wpdm_bulk_domain' );
		}
	}

	/**
	 * Primary handler for fetching domain data
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public static function fetch_single_domain_data( $post_id ) {
		global $wpdm_core;

		$post = get_post( (int) $post_id );

		if ( ! is_a( $post, '\WP_Post' ) ) {
			return false;
		}

		$post_type = get_post_type( $post );
		if ( \WPDM_POST_TYPE_DOMAIN !== $post_type ) {
			return false;
		}

		$domain = $post->post_title;
		$url    = get_post_meta( $post->ID, 'wpdm_domain_url', true );

		try {
			$expired_date = $wpdm_core->whois->get_expired_date( $domain );
			$nameservers  = $wpdm_core->whois->get_nameservers( $domain );
			$is_online    = is_website_online( $url );
			$google_index = $wpdm_core->index_checker->get_result( $domain );

			update_post_meta( $post_id, \WPDM\LAST_UPDATE_META_KEY, time() );
			update_post_meta( $post_id, \WPDM\EXPIRED_DATE_META_KEY, $expired_date );
			update_post_meta( $post_id, \WPDM\NAMESERVERS_META_KEY, $nameservers );
			update_post_meta( $post_id, \WPDM\UPTIME_META_KEY, $is_online );
			update_post_meta( $post_id, \WPDM\GOOGLE_INDEX_META_KEY, $google_index );

			update_post_modified_dates( $post_id );
		} catch ( \Exception $ex ) {
			error_log( $ex->getMessage() );
		}
	}

	/**
	 * Get 10 least modified posts and process
	 */
	public static function process_bulk_domain_data() {
		$args  = array(
			'post_type'      => WPDM_POST_TYPE_DOMAIN,
			'orderby'        => 'modified',
			'order'          => 'ASC',
			'posts_per_page' => 10,
			'fields'         => 'ids',
		);
		$query = new \WP_Query( $args );

		$post_ids = $query->posts;
		foreach ( $post_ids as $post_id ) {
			self::fetch_single_domain_data( $post_id );
			sleep( 2 );
		}
	}

}


<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

WP_CLI::add_command( 'wpdm', 'WPDM_CLI_Command' );

/**
 * CLI Commands for ElasticPress
 *
 */
class WPDM_CLI_Command extends WP_CLI_Command {
	/**
	 * Update a domain post for a site
	 *
	 * <post_id>
	 * : Domain Post ID
	 *
	 * @param array $args
	 *
	 * @since 0.1.0
	 *
	 * @param array $assoc_args
	 */
	public function update( $args, $assoc_args ) {
		global $wpdm_core;

		list( $post_id ) = $args;

		$post = get_post( (int) $post_id );

		if ( ! is_a( $post, '\WP_Post' ) ) {
			\WP_CLI::error( 'Data not found' );
		}

		$post_type = get_post_type( $post );
		if ( WPDM_POST_TYPE_DOMAIN !== $post_type ) {
			\WP_CLI::error( 'Data not found' );
		}

		$domain = $post->post_title;
		$url    = get_post_meta( $post->ID, 'wpdm_domain_url', true );

		\WPDM\Jobs\Core::fetch_single_domain_data( $post_id );

		\WP_CLI::success( __( 'Checking Result:', 'wpdm' ) );

		$last_checked = get_post_meta( $post_id, \WPDM\LAST_UPDATE_META_KEY, true );
		$expired_date = get_post_meta( $post_id, \WPDM\EXPIRED_DATE_META_KEY, true );
		$nameservers  = get_post_meta( $post_id, \WPDM\NAMESERVERS_META_KEY, true );
		$uptime       = get_post_meta( $post_id, \WPDM\UPTIME_META_KEY, true );
		$google_index = get_post_meta( $post_id, \WPDM\GOOGLE_INDEX_META_KEY, true );

		$uptime = empty( $google_index ) ? 'Good' : 'Down';

		$google_index = empty( $google_index ) ? 'N/A' : $google_index . ' results';

		\WP_CLI::log( __( 'Last Checked: ', 'wpdm' ) . date( 'F j, Y', (int) $last_checked ) );
		\WP_CLI::log( __( 'Expired: ', 'wpdm' ) . date( 'F j, Y', strtotime( $expired_date ) ) );
		\WP_CLI::log( __( 'Nameservers: ', 'wpdm' ) );
		if ( is_array( $nameservers ) ) {
			foreach ( $nameservers as $nameserver ) {
				\WP_CLI::log( $nameserver );
			}
		} else {
			\WP_CLI::log( $nameservers );
		}
		\WP_CLI::log( __( 'Online Status: ', 'wpdm' ) . $uptime );
		\WP_CLI::log( __( 'Google Index: ', 'wpdm' ) . $google_index );
	}
}

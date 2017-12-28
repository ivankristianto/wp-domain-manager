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
	 * Check a domain post for a site
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
	public function check( $args, $assoc_args ) {
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

		WP_CLI::log( __( 'Checking Expired Date', 'wpdm' ) );
		$expired_date = $wpdm_core->whois->get_expired_date( $domain );
		var_dump( $expired_date );

		/*WP_CLI::log( __( 'Checking Expired Date', 'wpdm' ) );
		$nameservers = $wpdm_core->whois->get_nameservers( $domain );
		var_dump( $nameservers );

		WP_CLI::log( __( 'Checking Online Status', 'wpdm' ) );
		$is_online = is_website_online( $url );
		var_dump( $is_online );

		WP_CLI::log( __( 'Checking Google Index', 'wpdm' ) );
		$google_index = $wpdm_core->index_checker->get_result( $domain );
		var_dump( $google_index );*/
	}
}

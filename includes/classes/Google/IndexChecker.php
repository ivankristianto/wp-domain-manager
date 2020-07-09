<?php
namespace WPDM\Google;

class IndexChecker {

	/**
	 * IndexChecker constructor.
	 */
	function __construct() {
	}

	/**
	 * Get Index count in Google search results
	 * @param $domain
	 *
	 * @return bool|integer
	 */
	public function get_result( $domain ) {
		$url = 'https://www.google.com/search?q=site:' . $domain;

		$response = wp_remote_get( $url, \WPDM\get_remote_args() );

		if ( ! is_array( $response ) ) {
			return false;
		}

		$body          = wp_remote_retrieve_body( $response );
		$regex_pattern = '/(About|Sekitar) (.*?) (results|hasil)/';

		preg_match_all( $regex_pattern, $body, $matches, PREG_SET_ORDER, 0 );

		if ( ! isset( $matches[0][2] ) ) {
			return false;
		}

		return (int) $matches[0][2];
	}
}

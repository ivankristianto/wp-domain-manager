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

		$args     = array(
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'user-agent'  => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
			'body'        => null,
			'compress'    => false,
			'decompress'  => true,
			'sslverify'   => true,
			'stream'      => false,
			'filename'    => null,
		);
		$response = wp_remote_get( $url, $args );

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

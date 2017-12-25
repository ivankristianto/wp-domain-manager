<?php

/**
 * Check if website is online
 *
 * @param $url
 *
 * @return bool
 */
function is_website_online( $url ) {
	if ( false === strpos( $url, 'http' ) ) {
		$url = 'https://' . $url;
	}

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

	$response_code = wp_remote_retrieve_response_code( $response );
	return ( 200 === $response_code );
}

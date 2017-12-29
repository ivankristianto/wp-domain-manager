<?php

namespace WPDM\Whois;

class Whois {

	/**
	 * Hold phpwhois object
	 *
	 * @var \Whois
	 */
	protected $php_whois;

	/**
	 * Whois constructor.
	 */
	function __construct() {
		$this->php_whois            = new \Whois();
		$this->php_whois->deepWhois = true;
	}

	/**
	 * Get Domain expiration date
	 *
	 * @param $domain
	 *
	 * @return bool|string
	 */
	public function get_expired_date( $domain ) {
		if ( empty( $domain ) ) {
			return false;
		}

		$expiration_date = '';
		$result          = $this->php_whois->lookup( $domain );
		$regex_pattern   = '/^(Registry Expiry Date:|Expiration Date:)\W?\K(.*)/';

		foreach ( $result['rawdata'] as $raw ) {
			preg_match_all( $regex_pattern, trim( $raw ), $matches, PREG_SET_ORDER, 0 );
			if ( is_array( $matches ) && count( $matches ) > 0 && count( $matches[0] ) > 0 ) {
				$expiration_date = $matches[0][0];
				break;
			}
		}

		return $expiration_date;
	}

	/**
	 * Get Domain nameservers
	 *
	 * @param $domain
	 *
	 * @return bool|array
	 */
	public function get_nameservers( $domain ) {
		if ( empty( $domain ) ) {
			return false;
		}

		$result = $this->php_whois->lookup( $domain );
		if ( isset( $result['regrinfo'] ) && is_array( $result['regrinfo'] ) &&
			 isset( $result['regrinfo']['domain'] ) && isset( $result['regrinfo']['domain']['nserver'] ) ) {
			return array_keys( $result['regrinfo']['domain']['nserver'] );
		}

		$nameservers   = array();
		$regex_pattern = '/^(Name Server:)\W?\K(.*)/';
		foreach ( $result['rawdata'] as $raw ) {
			preg_match_all( $regex_pattern, trim( $raw ), $matches, PREG_SET_ORDER, 0 );
			if ( is_array( $matches ) && count( $matches ) > 0 && count( $matches[0] ) > 0 ) {
				$nameservers[] = $matches[0][0];
			}
		}

		return $nameservers;
	}
}

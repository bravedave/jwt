<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dvc\jwt;

use dvc\Request;
use sys;
use strings;

abstract class jwt {
	protected static $SECRET = false;

	protected static function _config() {
		return sprintf( '%s%slogon.json', \config::dataPath(), DIRECTORY_SEPARATOR);

    }

    protected static function _secret() {
        if ( self::$SECRET) return self::$SECRET;

		$config = self::_config();

        $j = file_exists( $config) ?
            json_decode( file_get_contents( $config)):
            (object)[];

        if ( isset( $j->secret)) {
            self::$SECRET = $j->secret;
            return self::$SECRET;

        };

        self::$SECRET = $j->secret = strings::rand();
        file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES));

        return self::$SECRET;

	}

    static function decode( $jwt) {
        try {
            // sys::logger( sprintf( ' - secret %s (%s)', self::_secret(), __METHOD__ ));
            return \Firebase\JWT\JWT::decode( $jwt, self::_secret(), ['HS256']);

        }
        catch ( \Exception $e) {
            // sys::logger( sprintf( 'fail token %s (%s)', $jwt, __METHOD__ ));
            // sys::logger( sprintf( ' - secret %s (%s)', self::_secret(), __METHOD__ ));
            sys::logger( sprintf( 'invalid token - %s (%s)', $e->getMessage(), __METHOD__ ));

        }

        return false;

    }

    static function expires( $jwt) {
        if ( $decoded = self::decode( $jwt)) {

            // sys::logger( sprintf( 'valid token (%s)', __METHOD__ ));
            return $decoded->exp;

        }

        return false;

    }

    static function token( $params) {
        $request = Request::get();

        $time = time();

        $options = \array_merge( [
            'issuer_claim' => $request->getServerName(),
            'audience_claim' => 'client_credentials',
            'issuedat_claim' => $time, /* issued at */
            'notbefore_claim' => $time, /* not before in seconds */
            'expire_claim' => $time + 3600, /* expire time in seconds */
            'data' => [],

        ], (array)$params);

        return \Firebase\JWT\JWT::encode([
            "iss" => $options['issuer_claim'],
            "aud" => $options['audience_claim'],
            "iat" => $options['issuedat_claim'],
            "nbf" => $options['notbefore_claim'],
            "exp" => $options['expire_claim'],
            "data" => $options['data']

        ], self::_secret());

    }

}

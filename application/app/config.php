<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
*/

abstract class config extends dvc\config {
	static $DB_TYPE = 'sqlite';

	static $WEBNAME = 'JWT Module for DVC';
	static $LOGON_VERSION = 0.0;

	static protected function _app_config() {
		return sprintf( '%s%sapp.json', self::dataPath(), DIRECTORY_SEPARATOR);

    }

    static function _app_init() {
		$config = self::_app_config();

		if ( file_exists( $config)) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->logon_version)) {
				self::$LOGON_VERSION = $j->logon_version;

			};

		}

	}

	static function _app_version( $set = null) {
		$ret = self::$LOGON_VERSION;

		if ( (float)$set) {
			$config = self::_app_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$LOGON_VERSION = $j->logon_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES));

		}

		return $ret;

	}

}

config::_app_init();

<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
 * */

namespace dao;

class users extends _dao {
	protected $_db_name = 'users';

	public function getByEmail( $email) {
		if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE email = '%s'", $this->escape( $email)))) {
			return $res->dto();

		}

		return false;

	}

	public function rowcount() {
		if ( $res = $this->Result( 'SELECT COUNT(*) AS count FROM users')) {
			if ( $dto = $res->dto()) {
				return $dto->count;

			}

		}

		return 0;

	}

}
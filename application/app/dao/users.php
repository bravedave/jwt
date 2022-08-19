<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

use green\users\dao\users as greenusers;

class users extends greenusers {
	// protected $_db_name = 'users';

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

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

$dbc = 'sqlite' == \config::$DB_TYPE ?
	new \dvc\sqlite\dbCheck( $this->db, 'users' ) :
	new \dao\dbCheck( $this->db, 'users' );

$dbc->defineField( 'name', 'varchar');
$dbc->defineField( 'email', 'varchar');
$dbc->defineField( 'password', 'varchar');

$dbc->check();

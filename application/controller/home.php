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

class home extends Controller {
    const _app_version = 0.01;

    protected function _index() {
        $this->render([
            'title' => $this->title = config::$WEBNAME,
            'primary' => 'home',
            'secondary' => 'blank'

        ]);

    }

	protected function before() {
		if ( config::_app_version() < self::_app_version) {
			config::_app_version( self::_app_version);
			$dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

		}

		// do something to set a default user - or not
		$dao = new dao\users;
		if ( !$dao->rowcount()) {
			$dao->Insert([
				'name' => 'David Bray',
				'email' => 'david@brayworth.com.au',
				'password' => password_hash( 'secret', PASSWORD_BCRYPT),

			]);


		}

		parent::before();

	}

	protected function posthandler() {
		$action = $this->getPost('action');

		if ( 'token' == $action) {
			/*
			_brayworth_.post({
				url : _brayworth_.url(''),
				data : {
					action : 'token',
					email : 'david@brayworth.com.au',
					pass : 'secret',
					grant_type : 'client_credentials'

				},

			}).then( function( d) {
				console.log( d);

			});
			*/

			if ($email = $this->getPost( 'email')) {
				if ($pass = $this->getPost('pass')) {
					if ( strings::isEmail( $email)) {
						$dao = new dao\users;
						if ( $dto = $dao->getByEmail( $email)) {
							if ( password_verify( $pass, $dto->password)) {
								$jwt = dvc\jwt\jwt::token([
									'audience_claim' => $this->getPost('grant_type') || 'client_credentials',
									'data' => [
										'id' => $dto->id,
										'name' => $dto->name,
										'email' => $dto->email

									]

                                ]);

                                $expires = dvc\jwt\jwt::expires( $jwt);
                                Json::ack('Successful login')
									->add( 'jwt', $jwt)
									->add( 'email', $dto->email)
									->add( 'expireAt', $expires );

							} else { Json::nak( 'unsuccessful login..'); }

						} else { Json::nak( 'unsuccessful login.'); }

					} else { Json::nak( 'unsuccessful login'); }

				} else { Json::nak( 'incomplete login'); }

			} else { Json::nak( 'incomplete login'); }

		}
		elseif ( isset( $_SERVER['HTTP_AUTHORIZATION'])) {
			/*
			_brayworth_.post({
				url : _brayworth_.url(''),
				data : {
					action : 'token',
					email : 'david@brayworth.com.au',
					pass : 'secret',
					grant_type : 'client_credentials'

				},

			}).then( function( d) {
                if ( 'ack' == d.response) {
                    console.log( d);
                    _brayworth_.post({
                        url : _brayworth_.url(''),
                        data : {
                            action : 'something',

                        },
                        headers : {
                            'authorization' : 'Bearer ' + d.jwt

                        }

                    }).then( function( d) {
                        if ( 'ack' == d.response) {
                            console.log( d);

                        }
                        else {
                            console.log( d);

                        }

                    });

                }
                else {
                    console.log( d);

                }

			});
            */

            $authheader = $_SERVER['HTTP_AUTHORIZATION'];
            // sys::logger( sprintf( 'authHeader = %s', $authheader));

            $token = trim( preg_replace('@^Bearer@i', '', $authheader));
            // sys::logger( sprintf( 'authHeader = %s', $token));
            if ( $decoded = dvc\jwt\jwt::decode( $token)) {
                // Access is granted. Add code of the operation here
                Json::ack( 'Access granted')
                    ->add( 'name', $decoded->data->name);

            }
            else {
                http_response_code(401);
                Json::nak( 'Access denied');

            }

        }
        else {
            // Json::nak( 'Access denied');
            parent::posthandler();

        }

	}

	function __construct( $rootPath ) {
		$this->label = config::$WEBNAME;
		parent::__construct( $rootPath);

	}

}

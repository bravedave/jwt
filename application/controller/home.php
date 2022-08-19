<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class home extends Controller {
	protected function _index() {
		$render = [
			'title' => $this->title = config::$WEBNAME,
			'primary' => 'home',
			'secondary' => 'blank'

		];

		if (\config::$SYNTAX_HIGHLIGHT_DOCS) {
			// '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/styles/default.min.css">'
			$render['css'] = [
				'<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/styles/github-gist.min.css">'

			];

			$render['scripts'] = [
				'<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>',
				'<script>hljs.initHighlightingOnLoad();</script>'

			];
		}

		$this->render($render);
	}

	protected function before() {
		green\users\config::green_users_checkdatabase();

		// do something to set a default user - or not
		$dao = new dao\users;
		if (!$dao->rowcount()) {
			$dao->Insert([
				'name' => 'John Citizen',
				'email' => 'john@itizen.dom',
				'password' => password_hash('secret', PASSWORD_BCRYPT),

			]);
		}

		parent::before();
	}

	protected function posthandler() {
		$action = $this->getPost('action');

		if ('token' == $action) {
			if ($email = $this->getPost('email')) {
				if ($pass = $this->getPost('pass')) {
					if (strings::isEmail($email)) {
						$dao = new dao\users;
						if ($dto = $dao->getByEmail($email)) {
							if (password_verify($pass, $dto->password)) {
								$jwt = dvc\jwt\jwt::token([
									'audience_claim' => $this->getPost('grant_type') || 'client_credentials',
									'data' => [
										'id' => $dto->id,
										'name' => $dto->name,
										'email' => $dto->email

									]

								]);

								$expires = dvc\jwt\jwt::expires($jwt);
								Json::ack('Successful login')
									->add('jwt', $jwt)
									->add('email', $dto->email)
									->add('expireAt', date('c', $expires));
							} else {
								Json::nak('unsuccessful login..');
							}
						} else {
							Json::nak('unsuccessful login.');
						}
					} else {
						Json::nak('unsuccessful login');
					}
				} else {
					Json::nak('incomplete login');
				}
			} else {
				Json::nak('incomplete login');
			}
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			$authheader = $_SERVER['HTTP_AUTHORIZATION'];
			$token = trim(preg_replace('@^Bearer@i', '', $authheader));
			if ($decoded = dvc\jwt\jwt::decode($token)) {
				// Access is granted. Add code of the operation here
				Json::ack('Access granted')
					->add('name', $decoded->data->name);
			} else {
				http_response_code(401);
				Json::nak('Access denied');
			}
		} else {
			parent::posthandler();
		}
	}
}

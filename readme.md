# DVC JWT Module

Adds JWT support to DVC leveraging https://github.com/firebase/php-jwt

## Install

noting that this repo isn't on packagist so will require the repo to be specified

```json
"repositories": [
	{ "type": "git", "url": "https://github.com/bravedave/jwt" }

],
```

and install with composer ```composer require bravedave/jwt @dev```

## usage

this demo sets up a default user

|       |                  |
| :---- | :--------------- |
| name  | John Citizen     |
| email | john@citizen.dom |
| pass  | secret           |



###### so to retrieve a token:
```javascript
( _ => {
	_.post({
		url : _.url(''),
		data : {
			action : 'token',
			email : 'john@itizen.dom',
			pass : 'secret',
			grant_type : 'client_credentials'

		},

	}).then( d => { console.log( d); });

})(_brayworth_);
```



###### to do something useful:
```javascript
( _ => {
	_.post({
		url : _.url(''),
		data : {
			action : 'token',
			email : 'john@itizen.dom',
			pass : 'secret',
			grant_type : 'client_credentials'

		},

	}).then( d => {
		if ( 'ack' == d.response) {
			console.log( d);
			_.post({
				url : _.url(''),
				data : { action : 'something' },
				headers : { 'authorization' : 'Bearer ' + d.jwt }

			}).then( d => { console.log( d); });

		} else { console.log( d); }

	});

})( _brayworth_);
```

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
const api = data => fetch(data.url, {
  method: 'POST', // or 'PUT'
  headers: { ...{
    'Content-Type': 'application/json',
  }, ...data.headers},
  body: JSON.stringify(data.data)
})
.then(r => r.json())
.catch(err => console.error('Error:', err));

api( {
	url : './',
	data : {
		action : 'token',
		email : 'john@itizen.dom',
		pass : 'secret',
		grant_type : 'client_credentials'
	}
}).then(console.log);
```

###### to do something useful:
```javascript
const api = data => fetch(data.url, {
  method: 'POST', // or 'PUT'
  headers: { ...{
    'Content-Type': 'application/json',
  }, ...data.headers},
  body: JSON.stringify(data.data)
})
.then(r => r.json())
.catch(err => console.error('Error:', err));

api({
	url : './',
	data : {
		action : 'token',
		email : 'john@itizen.dom',
		pass : 'secret',
		grant_type : 'client_credentials'
	},
}).then( d => {
	if ( 'ack' == d.response) {
		api({
			url : './',
			data : { action : 'something' },
			headers : { 'authorization' : 'Bearer ' + d.jwt }
		}).then(console.log);

	} else { console.log( d); }
});
```

# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Installation

1. install package

	```php
		composer require unisharp/laravel-settings
	```

1. set service provider in config/app.php

	```php
		Unisharp\Setting\SettingServiceProvider::class,
	```

1. create settings table

	```php
		php artisan migrate --path=vendor/unisharp/laravel-settings/src/migrations
	```

1. visit `http://your-project-url/setting-test`

## Usage

Sample codes were written in controllers/ExampleComtroller.php.

```php
	Setting::get('name', 'Computer');
	// get setting value with key 'name'
	// return 'Computer' if the key does not exists

	Setting::lang('zh-TW')->get('name', 'Computer');
	// get setting value with key and language

	Setting::set('name', 'Computer');
	// set setting value by key

	Setting::lang('zh-TW')->set('name', 'Computer');
	// set setting value by key and language

	Setting::has('name');
	// check the key exists, return boolean

	Setting::lang('zh-TW')->has('name');
	// check the key exists by language, return boolean

	Setting::forget('name');
	// delete the setting by key

	Setting::lang('zh-TW')->forget('name');
	// delete the setting by key and language
```

## Dealing with array

```php
	Setting::get('item');
	// return null;

	Setting::set('item', ['USB' => '8G', 'RAM' => '4G']);
	Setting::get('item');
	//  return array(
	//		  	'USB' => '8G',
	//	 		'RAM' => '4G'
	//  	);

	Setting::get('item.USB');
	// return '8G';
```

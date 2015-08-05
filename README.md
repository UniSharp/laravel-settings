# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Usage

```php
	Setting::get('name', 'Michelle');
	// get setting value with key 'name'
	// return 'Michelle' if the key does not exists

	Setting::lang('zh-TW')->get('name', 'Michelle');
	// get setting value with key and language

	Setting::set('name', 'Michelle');
	// set setting value by key

	Setting::lang('zh-TW')->set('name', 'Michelle');
	// set setting value by key and language

	Setting::has('name');
	// check the key exists, return boolean

	Setting::lang('zh-TW')->has('name');
	// check the key exists by language, return boolean
```

## Dealing with JSON

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

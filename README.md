# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Usage

```php
	Setting::get($key, $default_value = null, $language = null);
	// get a setting with language(optional)
	// return $default_value(optional) if not exists

	Setting::set($key, $value, $language = null);
	// set a setting by key and value with language(optional)

	Setting::has($key, $language = null);
	// return true if the setting exists
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

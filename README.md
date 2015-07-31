# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Usage

```php
	Setting::lang($language)->get($key, $default_value = null);
	// get a setting value
	// return $default_value(optional) if not exists
	// chain method lang() is optional

	Setting::lang($language)->set($key, $value);
	// set a setting by key and value
	// chain method lang() is optional

	Setting::lang($language)->has($key);
	// return true if the setting exists
	// chain method lang() is optional
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

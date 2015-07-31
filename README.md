# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Usage

	```php
		// get a setting with language(optional)
		// return $default_value(optional) if not exists
		Setting::get($key, $default_value = '', $language = '');

		// set a setting by key and value with language(optional)
		Setting::set($key, $value, $language = '');

		// return true if the setting exists
		Setting::has($key, $language = '');
	```

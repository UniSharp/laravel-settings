# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * JSON format support.
 * Localization support.

## Usage

```php
	Setting::get($key, $default_value = '', $language = '');
	// get a setting with language(optional)
	// return $default_value(optional) if not exists

	Setting::set($key, $value, $language = '');
	// set a setting by key and value with language(optional)

	Setting::has($key, $language = '');
	// return true if the setting exists
```

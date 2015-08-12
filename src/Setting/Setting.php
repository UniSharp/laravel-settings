<?php

namespace Unisharp\Setting;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent implements SettingInterface
{
    protected $fillable = ['key', 'value', 'locale'];

    public $timestamps = false;

    protected static $lang = null;

    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    public static function get($key, $default_value = null)
    {
        if (strpos($key, '.') !== false) {
            $setting = Setting::getSubValue($key);
        } else {
            $setting = (Setting::has($key)) ? Setting::getByKey($key) : $default_value;
        }
        self::$lang = null;
        return $setting;
    }

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function set($key, $value)
    {
        if (strpos($key, '.') !== false) {
            Setting::setSubValue($key, $value);
        } else {
            Setting::setByKey($key, $value);
        }

        self::$lang = null;
    }

    /**
     * Check if the setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has($key)
    {
        if (strpos($key, '.') !== false) {
            $setting = Setting::getSubValue($key);
            self::$lang = null;

            return (empty($setting)) ? false : true;
        } else {
            $setting = Setting::getFromDb($key);
            self::$lang = null;
            
            return (count($setting) === 0) ? false : true;
        }
    }

    /**
     * Delete a setting.
     *
     * @param  string  $key
     * @return void
     */
    public static function forget($key)
    {
        if (strpos($key, '.') !== false) {
            Setting::forgetSubKey($key);
        } else {
            Setting::forgetByKey($key);
        }

        self::$lang = null;
    }

    /**
     * Set the language to work with other functions.
     *
     * @param  string  $language
     * @return void
     */
    public static function lang($language)
    {
        self::$lang = (empty($language)) ? null : $language;

        return new self();
    }

    /*********************
     * Private functions *
     *********************
     */

    private static function getByKey($key)
    {
        $setting = Setting::getFromDb($key)->value;
        $setting = (is_array(json_decode($setting))) ? json_decode($setting) : $setting;

        return $setting;
    }

    private static function getFromDb($key)
    {
        $setting = Setting::where('key', $key);

        $setting = (!is_null(self::$lang)) ? $setting->where('locale', self::$lang) : $setting->whereNull('locale');

        return $setting->first();
    }

    private static function getSubValue($key)
    {
        $setting = Setting::getSettingArray($key);

        $subkeys = Setting::getSubKeys($key);

        $setting = array_get($setting, $subkeys);

        return $setting;
    }

    private static function setByKey($key, $value)
    {
        $value = (is_array($value)) ? json_encode($value) : $value;
        $main_key = explode('.', $key)[0];
        
        if (Setting::has($main_key)) {
            $setting = (!is_null(self::$lang)) ? Setting::where('locale', self::$lang) : new Setting();

            $setting->where('key', $main_key)->update(['value' => $value]);
        } else {
            $setting = ['key' => $main_key, 'value' => $value];

            if (!is_null(self::$lang)) {
                $setting['locale'] = self::$lang;
            }
            
            Setting::create($setting);
        }
    }

    private static function setSubValue($key, $new_value)
    {
        $setting = Setting::getSettingArray($key);

        $subkeys = Setting::getSubKeys($key);

        array_set($setting, $subkeys, $new_value);

        Setting::setByKey($key, $setting);
    }

    private static function getSettingArray($key)
    {
        $mainKey = explode('.', $key)[0];
        $setting = Setting::getFromDb($mainKey);
        $setting = is_null($setting) ? $setting : json_decode($setting->value, true);

        return $setting;
    }

    private static function getSubKeys($key)
    {
        $keys = explode('.', $key);
        unset($keys[0]);
        $subkeys = implode('.', $keys);

        return $subkeys;
    }

    private static function forgetSubKey($key)
    {
        $setting = Setting::getSettingArray($key);

        $subkeys = Setting::getSubKeys($key);

        array_forget($setting, $subkeys);

        Setting::setByKey($key, $setting);
    }

    private static function forgetByKey($key)
    {
        $setting = Setting::where('key', $key);

        $setting = (!is_null(self::$lang)) ? $setting->where('locale', self::$lang) : $setting->whereNull('locale');

        $setting->delete();
    }
}

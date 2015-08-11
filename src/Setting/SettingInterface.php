<?php

namespace Unisharp\Setting;

interface SettingInterface
{
    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    public static function get($key, $default_value = null);

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function set($key, $value);

    /**
     * Check if the setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has($key);

    /**
     * Delete a setting.
     *
     * @param  string  $key
     * @return void
     */
    public static function forget($key);

    /**
     * Set the language to work with other functions.
     *
     * @param  string  $language
     * @return void
     */
    public static function lang($language);
}

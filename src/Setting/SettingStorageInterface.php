<?php

namespace Unisharp\Setting;

interface SettingStorageInterface
{
    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    public static function retrieve($key, $lang = null);

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function store($key, $value, $lang);

    /**
     * Check if the setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public static function modify($key, $value, $lang);

    /**
     * Delete a setting.
     *
     * @param  string  $key
     * @return void
     */
    public static function forget($key, $lang);
}

<?php

namespace Unisharp\Setting;

interface SettingStorageContract
{
    /**
     * Return all data
     *
     * @return array
     */
    public static function all();

    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    public function retrieve($key, $lang = null);

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function store($key, $value, $lang);

    /**
     * Check if the setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function modify($key, $value, $lang);

    /**
     * Delete a setting.
     *
     * @param  string  $key
     * @return void
     */
    public function forget($key, $lang);
}

<?php

namespace Unisharp\Setting;

use Cache;
use \Unisharp\Setting\EloquentStorage as Storage;

class Setting
{
    protected $lang = null;
    protected $autoResetLang = true;

    public function all()
    {
        return Storage::all();
    }

    /**
     * Return setting value or default value by key.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string|null
     */
    public function get($key, $default_value = null)
    {
        if (strpos($key, '.') !== false) {
            $setting = static::getSubValue($key);
        } else {
            if (static::hasByKey($key)) {
                $setting = static::getByKey($key);
            } else {
                $setting = $default_value;
            }
        }
        $this->resetLang();
        return $setting;
    }

    /**
     * Set the setting by key and value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value)
    {
        if (strpos($key, '.') !== false) {
            static::setSubValue($key, $value);
        } else {
            static::setByKey($key, $value);
        }

        $this->resetLang();
        return;
    }

    /**
     * Check if the setting exists.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        $exists = static::hasByKey($key);

        $this->resetLang();

        return $exists;
    }

    /**
     * Delete a setting.
     *
     * @param  string  $key
     * @return void
     */
    public function forget($key)
    {
        if (strpos($key, '.') !== false) {
            static::forgetSubKey($key);
        } else {
            static::forgetByKey($key);
        }

        $this->resetLang();
        return;
    }


    /**
     * Should language parameter auto retested ?
     *
     * @param  boolean  $option
     * @return instance of Setting
     */
    public function langResetting($option = false)
    {
        $this->autoResetLang = $option;
        return $this;
    }


    /**
     * Reset the language so we could switch to other local
     *
     * @param  boolean  $force
     * @return instance of Setting
     */
    private function resetLang($force = false)
    {
        if ($this->autoResetLang || $force)
        {
            $this->lang = null;    
        }
        return $this;
    }


    /**
     * Set the language to work with other functions.
     *
     * @param  string  $language
     * @return instance of Setting
     */
    public function lang($language)
    {
        if (empty($language)) {
            $this->resetLang();
        } else {
            $this->lang = $language;
        }

        return $this;
    }

    /*
     *********************
     * Private functions *
     *********************
     */

    private function getByKey($key)
    {
        if (strpos($key, '.') !== false) {
            $main_key = explode('.', $key)[0];
        } else {
            $main_key = $key;
        }

        if (Cache::has($main_key.'@'.$this->lang)) {
            $setting = Cache::get($main_key.'@'.$this->lang);
        } else {
            $setting = Storage::retrieve($main_key, $this->lang);

            if (!is_null($setting)) {
                $setting = $setting->value;
            }

            $setting_array = json_decode($setting, true);

            if (is_array($setting_array)) {
                $setting = $setting_array;
            }

            Cache::add($main_key.'@'.$this->lang, $setting, 1);
        }

        return $setting;
    }

    private function setByKey($key, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $main_key = explode('.', $key)[0];
        
        if (static::hasByKey($main_key)) {
            Storage::modify($main_key, $value, $this->lang);
        } else {
            Storage::store($main_key, $value, $this->lang);
        }

        if (Cache::has($main_key.'@'.$this->lang)) {
            Cache::forget($main_key.'@'.$this->lang);
        }
    }

    private function hasByKey($key)
    {
        if (strpos($key, '.') !== false) {
            $setting = static::getSubValue($key);
            return (empty($setting)) ? false : true;
        } else {
            if (Cache::has($key.'@'.$this->lang)) {
                $setting = Cache::get($key.'@'.$this->lang);
            } else {
                $setting = Storage::retrieve($key, $this->lang);
            }
            //$setting = Storage::retrieve($key, $this->lang);
            return (count($setting) === 0) ? false : true;
        }
    }

    private function forgetByKey($key)
    {
        Storage::forget($key, $this->lang);

        Cache::forget($key.'@'.$this->lang);
    }

    private function getSubValue($key)
    {
        $setting = static::getByKey($key);

        $subkey = static::removeMainKey($key);

        $setting = array_get($setting, $subkey);

        return $setting;
    }

    private function setSubValue($key, $new_value)
    {
        $setting = static::getByKey($key);

        $subkey = static::removeMainKey($key);

        array_set($setting, $subkey, $new_value);

        static::setByKey($key, $setting);
    }

    private function forgetSubKey($key)
    {
        $setting = static::getByKey($key);

        $subkey = static::removeMainKey($key);

        array_forget($setting, $subkey);

        static::setByKey($key, $setting);
    }

    private function removeMainKey($key)
    {
        $pos = strpos($key, '.');
        $subkey = substr($key, $pos+1);

        return $subkey;
    }
}

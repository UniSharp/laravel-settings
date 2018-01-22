<?php

namespace Unisharp\Setting;

use Illuminate\Contracts\Cache\Factory as CacheContract;

class Setting
{
    protected $lang = null;
    protected $autoResetLang = true;
    protected $storage = null;
    protected $cache = null;

    public function __construct(SettingStorageContract $storage, CacheContract $cache)
    {
        $this->storage = $storage;
        $this->cache = $cache;
    }

    public function all()
    {
        return $this->storage->all();
    }

    /**
     * Return setting value or default value by key.
     *
     * @param string $key
     * @param string $value
     *
     * @return string|null
     */
    public function get($key, $default_value = null)
    {
        if (strpos($key, '.') !== false) {
            $setting = $this->getSubValue($key);
        } else {
            if ($this->hasByKey($key)) {
                $setting = $this->getByKey($key);
            } else {
                $setting = $default_value;
            }
        }
        $this->resetLang();

        if (is_null($setting)) {
            $setting = $default_value;
        }

        return $setting;
    }

    /**
     * Set the setting by key and value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        if (strpos($key, '.') !== false) {
            $this->setSubValue($key, $value);
        } else {
            $this->setByKey($key, $value);
        }

        $this->resetLang();
    }

    /**
     * Check if the setting exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        $exists = $this->hasByKey($key);

        $this->resetLang();

        return $exists;
    }

    /**
     * Delete a setting.
     *
     * @param string $key
     *
     * @return void
     */
    public function forget($key)
    {
        if (strpos($key, '.') !== false) {
            $this->forgetSubKey($key);
        } else {
            $this->forgetByKey($key);
        }

        $this->resetLang();
    }

    /**
     * Should language parameter auto retested ?
     *
     * @param bool $option
     *
     * @return instance of Setting
     */
    public function langResetting($option = false)
    {
        $this->autoResetLang = $option;

        return $this;
    }

    /**
     * Set the language to work with other functions.
     *
     * @param string $language
     *
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

    /**
     * Reset the language so we could switch to other local.
     *
     * @param bool $force
     *
     * @return instance of Setting
     */
    protected function resetLang($force = false)
    {
        if ($this->autoResetLang || $force) {
            $this->lang = null;
        }

        return $this;
    }

    protected function getByKey($key)
    {
        if (strpos($key, '.') !== false) {
            $main_key = explode('.', $key)[0];
        } else {
            $main_key = $key;
        }

        if ($this->cache->has($main_key.'@'.$this->lang)) {
            $setting = $this->cache->get($main_key.'@'.$this->lang);
        } else {
            $setting = $this->storage->retrieve($main_key, $this->lang);

            if (!is_null($setting)) {
                $setting = $setting->value;
            }

            $setting_array = json_decode($setting, true);

            if (is_array($setting_array)) {
                $setting = $setting_array;
            }

            $this->cache->add($main_key.'@'.$this->lang, $setting, 1);
        }

        return $setting;
    }

    protected function setByKey($key, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $main_key = explode('.', $key)[0];

        if ($this->hasByKey($main_key)) {
            $this->storage->modify($main_key, $value, $this->lang);
        } else {
            $this->storage->store($main_key, $value, $this->lang);
        }

        if ($this->cache->has($main_key.'@'.$this->lang)) {
            $this->cache->forget($main_key.'@'.$this->lang);
        }
    }

    protected function hasByKey($key)
    {
        if (strpos($key, '.') !== false) {
            $setting = $this->getSubValue($key);

            return (empty($setting)) ? false : true;
        } else {
            if ($this->cache->has($key.'@'.$this->lang)) {
                $setting = $this->cache->get($key.'@'.$this->lang);
            } else {
                $setting = $this->storage->retrieve($key, $this->lang);
            }

            return (empty($setting)) ? false : true;
        }
    }

    protected function forgetByKey($key)
    {
        $this->storage->forget($key, $this->lang);

        $this->cache->forget($key.'@'.$this->lang);
    }

    protected function getSubValue($key)
    {
        $setting = $this->getByKey($key);

        $subkey = $this->removeMainKey($key);

        $setting = array_get($setting, $subkey);

        return $setting;
    }

    protected function setSubValue($key, $new_value)
    {
        $setting = $this->getByKey($key);

        $subkey = $this->removeMainKey($key);

        array_set($setting, $subkey, $new_value);

        $this->setByKey($key, $setting);
    }

    protected function forgetSubKey($key)
    {
        $setting = $this->getByKey($key);

        $subkey = $this->removeMainKey($key);

        array_forget($setting, $subkey);

        $this->setByKey($key, $setting);
    }

    protected function removeMainKey($key)
    {
        $pos = strpos($key, '.');
        $subkey = substr($key, $pos + 1);

        return $subkey;
    }
}

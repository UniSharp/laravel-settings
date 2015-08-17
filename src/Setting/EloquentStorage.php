<?php

namespace Unisharp\Setting;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EloquentStorage extends Eloquent implements SettingStorageInterface
{
    protected $fillable = ['key', 'value', 'locale'];

    protected $table = 'settings';

    public $timestamps = false;

    public static function retrieve($key, $lang = null)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        return $setting->first();
    }

    public static function store($key, $value, $lang)
    {
        $setting = ['key' => $key, 'value' => $value];

        if (!is_null($lang)) {
            $setting['locale'] = $lang;
        }
        
        static::create($setting);
    }

    public static function modify($key, $value, $lang)
    {
        if (!is_null($lang)) {
            $setting = static::where('locale', $lang);
        } else {
            $setting = new static();
        }

        $setting->where('key', $key)->update(['value' => $value]);
    }

    public static function forget($key, $lang)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        $setting->delete();
    }
}

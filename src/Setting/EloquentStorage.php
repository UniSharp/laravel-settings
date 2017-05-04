<?php

namespace Unisharp\Setting;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EloquentStorage extends Eloquent implements SettingStorageContract
{
    protected $fillable = ['key', 'value', 'locale'];

    protected $table = 'settings';

    public $timestamps = false;

    public function retrieve($key, $lang = null)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        return $setting->first();
    }

    public function store($key, $value, $lang)
    {
        $setting = ['key' => $key, 'value' => $value];

        if (!is_null($lang)) {
            $setting['locale'] = $lang;
        }

        static::create($setting);
    }

    public function modify($key, $value, $lang)
    {
        if (!is_null($lang)) {
            $setting = static::where('locale', $lang);
        } else {
            $setting = new static();
        }

        $setting->where('key', $key)->update(['value' => $value]);
    }

    public function forget($key, $lang)
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

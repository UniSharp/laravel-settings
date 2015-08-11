<?php

namespace Unisharp\Setting\Controllers;

use \Unisharp\Setting\Setting as Info;
use \Request;

class ExampleController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $settings = Info::all();

        return view('laravel-settings::defaultView')->with(compact('settings'));
    }

    public function search()
    {
        $key = Request::input('key');
        $lang = Request::input('lang');

        if (!empty($key)) {
            if (empty($lang)) {
                $exists = Info::has($key);
            } else {
                $exists = Info::lang($lang)->has($key);
            }

            if ($exists) {
                $value = Info::get($key);
                return 'Key : \''.$key.'\' was found. The value is : '.json_encode($value);
            } else {
                return 'Key : '.$key.' not found.';
            }
        }
        
        return 'Please specify the key.';
    }

    public function save()
    {
        Info::set('pc.cpu', 'intel');
        Info::set('pc.ssd.write', 'good');
        Info::set('pc.ssd.read', 'bad');

        Info::set('exam', 'place1');
        Info::lang('en')->set('exam', 'place2');

        $arr = ['a' => 'aa', 'b' => 'bb', 'c' => ['d' => 'dd', 'e' => 'kkkkk']];
        Info::set('char', $arr);
        Info::lang('en')->set('char', $arr);

        return redirect('setting-test');
    }

    public function drop()
    {
        $key = Request::input('key');
        $lang = Request::input('lang');

        if (!empty($key)) {
            if (empty($lang)) {
                Info::forget($key);
            } else {
                Info::lang($lang)->forget($key);
            }
        }

        return redirect('setting-test');
    }
}

<?php

namespace Tests;

use Unisharp\Setting\Setting;
use Unisharp\Setting\SettingStorageInterface;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Cache;

class MockStorage implements SettingStorageInterface
{
    protected $data = [];

    public function all()
    {
        throw new \Exception('Not implemented');
    }

    public function retrieve($key, $lang = 'NOLANG')
    {
        if (!array_key_exists($lang, $this->data)) {
            $this->data[$lang] = [];
        }

        if (!array_key_exists($key, $this->data[$lang])) {
            return null;
        }

        $res = new \stdClass();
        $res->lang = $lang;
        $res->key = $key;
        $res->value = $this->data[$lang][$key];
        return $res;
    }

    public function store($key, $value, $lang = 'NOLANG')
    {
        if (!array_key_exists($lang, $this->data)) {
            $this->data[$lang] = [];
        }
        $this->data[$lang][$key] = $value;
        return true;
    }

    public function modify($key, $value, $lang = 'NOLANG')
    {
        if (!array_key_exists($lang, $this->data)) {
            $this->data[$lang] = [];
        }
        $this->data[$lang][$key] = $value;
        return true;
    }

    public function forget($key, $lang = 'NOLANG')
    {
        unset($this->data[$lang][$key]);
        return true;
    }
}


class UnitTest extends TestCase
{
    public function testSimpleGetSetWithoutCache()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('add')
            ->once()
            ->andReturn(true);

        $setting = new Setting(new MockStorage());
        $setting->set('key', 'value');
        $this->assertSame('value', $setting->get('key'));
    }


    public function testSimpleGetSetWithCache()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->with('key@')
            ->andReturn('value');

        $setting = new Setting(new MockStorage());
        $setting->set('key', 'value');
        $this->assertSame('value', $setting->get('key'));
    }

    public function testSimpleGetSetDotValueWithoutCache()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('add')
            ->once()
            ->andReturn(true);

        $setting = new Setting(new MockStorage());
        $arr = ['a' => 'va', 'b' => 'vb'];
        $setting->set('key', $arr);
        $this->assertSame($arr, $setting->get('key'));
        $this->assertSame($arr['a'], $setting->get('key.a'));

        $setting->set('key2.c', 'val2c');
        $this->assertSame('val2c', $setting->get('key2.c'));
        $this->assertSame(['c' => 'val2c'], $setting->get('key2'));
    }

    public function testLang()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('add')
            ->once()
            ->andReturn(true);

        $setting = new Setting(new MockStorage());

        $setting->lang('lang1')->set('key', 'val1');
        $this->assertSame('val1', $setting->lang('lang1')->get('key'));
        $this->assertNull($setting->get('key'));
    }

    public function testForget()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('add')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('forget')
            ->once()
            ->andReturn(true);

        $setting = new Setting(new MockStorage());
        $setting->set('key', 'value');
        $this->assertSame('value', $setting->get('key'));
        $setting->forget('key');
        $this->assertNull($setting->get('key'));
    }

    public function testDefault()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('add')
            ->once()
            ->andReturn(true);

        $setting = new Setting(new MockStorage());
        $setting->set('key', 'value');
        $this->assertSame('value', $setting->get('key', 'new-value'));
        $this->assertSame('new-value', $setting->get('not-exists', 'new-value'));

        $setting->set('key2.a', 'value-a');
        $this->assertSame(['a' => 'value-a'], $setting->get('key2', 'new-value'));
        $this->assertSame('value-a', $setting->get('key2.a', 'new-value'));
        $this->assertSame('new-value', $setting->get('key2.b', 'new-value'));
    }

}

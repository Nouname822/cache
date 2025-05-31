<?php

namespace Nurymbet\Phantom\Cache\tests;

use Nurymbet\Phantom\Cache\Adapter\Redis;
use Nurymbet\Phantom\Cache\Setting;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    private Redis $redis;

    protected function setUp(): void
    {
        new Setting([
            'redis' => [
                'scheme' => 'tcp',
                'host' => '127.0.0.1',
                'port' => 6379,
                'password' => null,
            ]
        ]);

        $this->redis = new Redis();
    }

    public function testSet()
    {
        $this->assertTrue($this->redis->set('test', '123'));
        $this->assertTrue($this->redis->set('test', '123_new'));
        $this->assertTrue($this->redis->set('test', ['123', '346' => 123]));
        $this->assertTrue($this->redis->set('test', true));
        $this->assertTrue($this->redis->set('test', 123));
        $this->assertTrue($this->redis->set('test', 123.123));
        $this->assertTrue($this->redis->set('test', null));
    }

    public function testGet()
    {
        $this->assertNull($this->redis->get('test', 'null'));
        $this->assertTrue($this->redis->set('test', 123.123));
        $this->assertIsFloat($this->redis->get('test'));
        $this->assertTrue($this->redis->set('test', 123));
        $this->assertIsInt($this->redis->get('test'));
        $this->assertTrue($this->redis->set('test', true));
        $this->assertIsBool($this->redis->get('test'));
        $this->assertTrue($this->redis->set('test', ['123', '346' => 123]));
        $this->assertIsArray($this->redis->get('test'));
        $this->assertTrue($this->redis->set('test', '123'));
        $this->assertIsString($this->redis->get('test'));
    }

    public function testHas()
    {
        $this->assertEquals(1, $this->redis->has('test'));
        $this->redis->del('test');
        $this->assertEquals(0, $this->redis->has('test'));
    }

    public function testDel()
    {
        $this->assertEquals(0, $this->redis->del('test'));
        $this->assertEquals(0, $this->redis->del('test'));
        $this->assertEquals(0, $this->redis->del('test'));
        $this->assertEquals(0, $this->redis->del('test'));
    }

    public function testClear()
    {
        // Смотря на настройки Redis он может вернуть false или true
        $this->assertFalse($this->redis->clearAllDatabases());
    }
}

<?php

namespace Phantom\Cache\tests;

use Phantom\Cache\Adapter\Cache;
use Phantom\Cache\Setting;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    protected function setUp(): void
    {
        new Setting([
            'file' => [
                'path' => __DIR__ . '/storage',
                'extension' => 'cache'
            ]
        ]);
    }

    public function testSet()
    {
        $cache = new Cache('app');

        $this->assertTrue($cache->set('test', '123'));
    }

    public function testSetIfExist()
    {
        $cache = new Cache('app');

        $cache->set('test', '123_new');

        $this->assertEquals('123_new', $cache->get('test'));
    }

    public function testGet()
    {
        $cache = new Cache('app');

        $this->assertEquals('123_new', $cache->get('test'));
    }

    public function testHas()
    {
        $cache = new Cache('app');

        $this->assertTrue($cache->has('test'));
    }

    public function testDel()
    {
        $cache = new Cache('app');

        $this->assertTrue($cache->del('test'));
    }

    public function testGetIfDontExist()
    {
        $cache = new Cache('app');

        $this->assertNull($cache->get('test'));
    }

    public function testHasIfDontExist()
    {
        $cache = new Cache('app');

        $this->assertFalse($cache->has('test'));
    }

    public function testDelIfDontExist()
    {
        $cache = new Cache('app');

        $this->assertTrue($cache->del('test'));
    }

    public function testClear()
    {
        $cache = new Cache('app');

        $this->assertTrue($cache->clear());
        $this->assertTrue($cache->clear(true));
    }
}

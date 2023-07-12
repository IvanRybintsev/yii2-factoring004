<?php

namespace BnplPartners\Factoring004Yii2;

use yii\caching\CacheInterface;

class CacheAdapter implements \Psr\SimpleCache\CacheInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public static function init(CacheInterface $cache)
    {
        return new self($cache);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        if ($this->cache->exists($key)) {
            return $this->cache->get($key);
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        return $this->cache->flush();
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {}

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {}

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {}

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return $this->cache->exists($key);
    }
}
<?php

namespace Simplon\Queue;

use Simplon\Helper\Interfaces\DataInterface;
use Simplon\Redis\Redis;

/**
 * @package Simplon\Queue
 */
interface ConfigInterface extends DataInterface
{
    /**
     * @return Redis
     */
    public function getRedis(): Redis;

    /**
     * @return string
     */
    public function getQueue(): string;

    /**
     * @return null|ContextInterface
     */
    public function getContext(): ?ContextInterface;
}
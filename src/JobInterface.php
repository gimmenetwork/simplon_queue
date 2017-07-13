<?php

namespace Simplon\Queue;

use Simplon\Helper\Interfaces\DataInterface;

/**
 * @package Simplon\Queue
 */
interface JobInterface extends DataInterface
{
    /**
     * @param string $taskId
     * @param null|DataInterface $context
     */
    public function run(string $taskId, ?DataInterface $context = null): void;
}
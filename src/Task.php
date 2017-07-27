<?php

namespace Simplon\Queue;

use Simplon\Helper\Data\Data;

/**
 * @package Simplon\Queue
 */
class Task extends Data
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var array
     */
    protected $job;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJobClassNamespace(): string
    {
        return $this->job['class'];
    }

    /**
     * @return array
     */
    public function getJobData(): array
    {
        return $this->job['data'];
    }
}
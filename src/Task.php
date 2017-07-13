<?php

namespace Simplon\Queue;

use Simplon\Helper\SecurityUtil;

/**
 * @package Simplon\Queue
 */
class Task
{
    /**
     * @var string
     */
    protected $jobFullyQualifiedClassName;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var string|null
     */
    private $id;

    /**
     * @param JobInterface $job
     * @param string $id
     */
    public function __construct(JobInterface $job, ?string $id = null)
    {
        $this->jobFullyQualifiedClassName = get_class($job);
        $this->data = $job->toArray();
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode([
            'id'    => $this->getId(),
            'class' => $this->getJobFullyQualifiedClassName(),
            'data'  => $this->getData(),
        ]);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        if (!$this->id)
        {
            $this->id = SecurityUtil::createRandomToken(22);
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function getJobFullyQualifiedClassName(): string
    {
        return $this->jobFullyQualifiedClassName;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
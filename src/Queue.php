<?php

namespace Simplon\Queue;

use Simplon\Helper\SecurityUtil;
use Simplon\Redis\Redis;

/**
 * @package Simplon\Queue
 */
class Queue
{
    const REDIS_KEY_PREFIX = 'SIMPLON_QUEUE';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return strtoupper($this->config->getQueue());
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        if ($size = $this->getRedis()->listSize($this->buildQueueKey()))
        {
            return $size;
        }

        return 0;
    }

    /**
     * @param JobInterface $job
     *
     * @return bool
     */
    public function addJob(JobInterface $job): bool
    {
        $taskData = [
            'id'  => SecurityUtil::createRandomToken(22),
            'job' => [
                'class' => get_class($job),
                'data'  => $job->toArray(),
            ],
        ];

        if ($this->getRedis()->listPush($this->buildQueueKey(), json_encode($taskData)))
        {
            return true;
        }

        return false;
    }

    /**
     * @return Task|null
     */
    public function fetchTask(): ?Task
    {
        if ($json = $this->getRedis()->listShift($this->buildQueueKey()))
        {
            return new Task(json_decode($json, true));
        }

        return null;
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function runTask(Task $task): bool
    {
        $className = $task->getJobClassNamespace();

        if (class_exists($className))
        {
            /** @var JobInterface $job */
            $job = new $className($task->getJobData());
            $job->run($task->getId(), $this->config->getContext());

            return true;
        }

        return false;
    }

    /**
     * @return Redis
     */
    private function getRedis(): Redis
    {
        return $this->config->getRedis();
    }

    /**
     * @return string
     */
    private function buildQueueKey(): string
    {
        return self::REDIS_KEY_PREFIX . ':' . $this->getName();
    }
}
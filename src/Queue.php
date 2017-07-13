<?php

namespace Simplon\Queue;

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
        return $this->config->getQueue();
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
     * @param Task $task
     *
     * @return bool
     */
    public function addTask(Task $task): bool
    {
        if ($this->getRedis()->listPush($this->buildQueueKey(), $task->toJSON()))
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
            $data = json_decode($json, true);

            /** @var JobInterface $job */
            $job = new $data['class'];

            if (isset($data['data']))
            {
                $job->fromArray($data['data']);
            }

            return new Task($job, $data['id']);
        }

        return null;
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function runJob(Task $task): bool
    {
        $className = $task->getJobFullyQualifiedClassName();

        if (class_exists($className))
        {
            /** @var JobInterface $job */
            $job = new $className($task->getData());
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
        return self::REDIS_KEY_PREFIX . ':' . $this->config->getQueue();
    }
}
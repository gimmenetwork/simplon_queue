<?php

namespace Simplon\Queue;

use Simplon\Helper\Interfaces\DataInterface;

/**
 * @package Simplon\Queue
 */
class CallbackJob extends Job
{
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param string $taskId
     * @param null|DataInterface $context
     *
     * @return void
     */
    public function run(string $taskId, ?DataInterface $context = null): void
    {
        var_dump([$taskId, $this->toArray()]);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return CallbackJob
     */
    public function setUrl(string $url): CallbackJob
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return CallbackJob
     */
    public function setMethod(string $method): CallbackJob
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return CallbackJob
     */
    public function setParams(array $params): CallbackJob
    {
        $this->params = $params;

        return $this;
    }
}
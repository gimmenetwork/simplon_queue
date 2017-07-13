<?php

namespace Simplon\Queue\Cli;

use Moment\Moment;
use Moment\MomentException;
use Simplon\Queue\Queue;

/**
 * @package Simplon\Queue\Cli
 */
class WorkerCli
{
    /**
     * @var Queue
     */
    private $queue;

    /**
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @throws MomentException
     */
    public function run(): void
    {
        echo "\n";
        echo "[ QUEUE: " . $this->queue->getName() . " ]\n";
        echo "------------------------------------------------\n";
        echo "-> STARTED AT: " . (new Moment('now'))->format() . "\n";
        echo "-> CURRENT SIZE: " . $this->queue->getSize() . " item(s)\n\n";

        $emptyQueue = true;
        $previousCallEmpty = false;

        while (true)
        {
            if ($task = $this->queue->fetchTask())
            {
                $emptyQueue = false;
                $previousCallEmpty = false;

                echo "[" . (new Moment('now'))->format() . "] - found task with ID... " . $task->getId() . "\n";
                $this->queue->runJob($task);

                usleep(100000);
                continue;
            }

            if ($emptyQueue)
            {
                if ($previousCallEmpty === false)
                {
                    echo "\nEMPTY QUEUE. WAITING FOR JOBS...\n\n";
                }

                $emptyQueue = true;
                $previousCallEmpty = true;
                sleep(5);
            }
        }
    }
}
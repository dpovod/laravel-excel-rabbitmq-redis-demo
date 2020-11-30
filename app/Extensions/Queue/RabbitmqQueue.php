<?php
declare(strict_types=1);

namespace App\Extensions\Queue;

use App\Extensions\Queue\Connectors\RabbitmqConnector;
use App\Extensions\Queue\Jobs\RabbitmqJob;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqQueue extends Queue implements QueueContract
{
    /**
     * The database connection instance.
     *
     * @var AbstractConnection
     */
    protected $amqpConnection;

    /** @var AMQPChannel */
    protected $amqpChannel;

    /**
     * The name of the default queue.
     *
     * @var string
     */
    protected $default;

    /**
     * The expiration time of a job.
     *
     * @var int|null
     */
    protected $retryAfter = 60;

    /**
     * Create a new rabbitmq queue instance.
     *
     * @param AbstractConnection $connection
     * @param string $default
     * @param int $retryAfter
     */
    public function __construct(AbstractConnection $connection, $default = 'default', $retryAfter = 60)
    {
        $this->default = $default;
        $this->amqpConnection = $connection;
        $this->retryAfter = $retryAfter;
    }

    /**
     * Get the size of the queue.
     *
     * @param string|null $queue
     * @return int
     * @throws \Exception
     */
    public function size($queue = null)
    {
        $queue = $this->getQueue($queue);
        $queueInfo = $this->getAmqpChannel()->queue_declare($queue, false, true, false, false);

        return $queueInfo[1];
    }

    /**
     * Push a new job onto the queue.
     *
     * @param string $job
     * @param mixed $data
     * @param string|null $queue
     * @return mixed
     * @throws \Exception
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushRaw($this->createPayload($job, $this->getQueue($queue), $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param string $payload
     * @param string|null $queue
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->pushToQueue($payload, $queue = null);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param \DateTimeInterface|\DateInterval|int $delay
     * @param string $job
     * @param mixed $data
     * @param string|null $queue
     * @return void
     * @throws \Exception
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        throw new \Exception('Method not allowed');
    }

    /**
     * Push an array of jobs onto the queue.
     *
     * @param array $jobs
     * @param mixed $data
     * @param string|null $queue
     * @return mixed
     * @throws \Exception
     */
    public function bulk($jobs, $data = '', $queue = null)
    {
        throw new \Exception('Method not allowed');
    }

    /**
     * Release a reserved job back onto the queue.
     *
     * @param string $queue
     * @param RabbitmqJob $job
     * @param int $delay
     * @return mixed
     * @throws \Exception
     */
    public function release($queue, $job, $delay)
    {
        throw new \Exception('Method not allowed');
    }

    /**
     * Push a raw payload to the database with a given delay.
     *
     * @param string $payload
     * @param string|null $queue
     * @return mixed
     * @throws \Exception
     */
    protected function pushToQueue(string $payload, ?string $queue = null)
    {
        $queue = $this->getQueue($queue);
        $this->getAmqpChannel()->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($payload, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        return $this->getAmqpChannel()->basic_publish($msg, '', $queue);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param string|null $queue
     * @return Job|null
     *
     * @throws \Throwable
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);
        $this->getAmqpChannel()->queue_declare($queue, false, true, false, false);
        $message = $this->getAmqpChannel()->basic_get($queue, true);

        return $message ?
            new RabbitmqJob($this->container, $this, $message->body, null, $this->connectionName, $queue ?: $this->default)
            : null;
    }

    /**
     * Delete a reserved job from the queue.
     *
     * @param string $queue
     * @param string $id
     * @return void
     *
     * @throws \Throwable
     */
    public function deleteReserved($queue, $id)
    {
        //@TODO: возможно здесь есть смысл делать ack/nack
    }

    /**
     * Get the queue or return the default.
     *
     * @param string|null $queue
     * @return string
     */
    public function getQueue(?string $queue = null)
    {
        return $queue ?: $this->default;
    }

    /**
     * @return AMQPChannel
     * @throws \Exception
     */
    private function getAmqpChannel(): AMQPChannel
    {
        if (!$this->amqpChannel || !$this->amqpChannel->is_open() || !$this->amqpConnection->isConnected()) {
            $this->initAmqpChannel();
        }

        return $this->amqpChannel;
    }

    /**
     * @throws \Exception
     */
    private function initAmqpChannel()
    {
        try {
            $this->amqpChannel = new AMQPChannel($this->amqpConnection);
        } catch (\Exception $e) {
            $this->amqpConnection = (new RabbitmqConnector())->createAmqpConnection();
            $this->amqpChannel = new AMQPChannel($this->amqpConnection);
        }
    }
}

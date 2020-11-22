<?php
declare(strict_types=1);

namespace App\Extensions\Queue\Connectors;

use App\Extensions\Queue\RabbitmqQueue;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use PhpAmqpLib\Connection\AMQPSocketConnection;

class RabbitmqConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param array $config
     * @return Queue
     * @throws \Exception
     */
    public function connect(array $config)
    {
        $connection = new AMQPSocketConnection(
            env('RABBITMQ_HOST', 'localhost'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USERNAME', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        return new RabbitmqQueue($connection, $config['queue'], $config['retry_after'] ?? 60);
    }
}

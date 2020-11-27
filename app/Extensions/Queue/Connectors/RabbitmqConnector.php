<?php
declare(strict_types=1);

namespace App\Extensions\Queue\Connectors;

use App\Extensions\Queue\RabbitmqQueue;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Connection\AMQPSocketConnection;

/**
 * Class RabbitmqConnector
 * @package App\Extensions\Queue\Connectors
 */
class RabbitmqConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param array $config
     * @return Queue
     * @throws \Exception
     */
    public function connect(array $config): Queue
    {
        return new RabbitmqQueue($this->createAmqpConnection(), $config['queue'], $config['retry_after'] ?? 60);
    }

    /**
     * @return AMQPSocketConnection
     * @throws \Exception
     */
    public function createAmqpConnection(): AMQPSocketConnection
    {
        $rabbitmqConfig = Config::get('rabbitmq');

        return new AMQPSocketConnection(
            $rabbitmqConfig['host'],
            $rabbitmqConfig['port'],
            $rabbitmqConfig['user'],
            $rabbitmqConfig['password']
        );
    }
}

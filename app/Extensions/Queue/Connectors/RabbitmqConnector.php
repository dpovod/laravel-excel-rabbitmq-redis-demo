<?php
declare(strict_types=1);

namespace App\Extensions\Queue\Connectors;

use App\Extensions\Queue\RabbitmqQueue;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Facades\Config;
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
        $rabbitmqConfig = Config::get('rabbitmq');

        $connection = new AMQPSocketConnection(
            $rabbitmqConfig['host'],
            $rabbitmqConfig['port'],
            $rabbitmqConfig['user'],
            $rabbitmqConfig['password']
        );

        return new RabbitmqQueue($connection, $config['queue'], $config['retry_after'] ?? 60);
    }
}

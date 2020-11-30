<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class BaseImportProgressEvent
 * @package App\Events
 */
abstract class BaseImportProgressEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string */
    public $importJobUuid;

    /**
     * Create a new event instance.
     *
     * @param string $importJobUuid
     */
    public function __construct(string $importJobUuid)
    {
        $this->importJobUuid = $importJobUuid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('import-progress');
    }
}

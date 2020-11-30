<?php

namespace App\Events;

/**
 * Class ImportFailed
 * @package App\Events
 */
class ImportFailed extends BaseImportProgressEvent
{
    /** @var string */
    public $reason;

    /**
     * Create a new event instance.
     *
     * @param string $importJobUuid
     * @param string $reason
     */
    public function __construct(string $importJobUuid, string $reason)
    {
        parent::__construct($importJobUuid);
        $this->reason = $reason;
    }
}

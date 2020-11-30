<?php

namespace App\Events;

class ImportProgressChanged extends BaseImportProgressEvent
{
    /** @var int */
    public $progress;

    /**
     * Create a new event instance.
     *
     * @param string $importJobUuid
     * @param int $progress
     */
    public function __construct(string $importJobUuid, int $progress)
    {
        parent::__construct($importJobUuid);
        $this->progress = $progress;

        if ($progress === 100) {
            event(new ImportCompleted($importJobUuid));
        }
    }
}

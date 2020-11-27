<?php

namespace App\Imports;

use App\Models\Row;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RowsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithColumnLimit,
    WithCalculatedFormulas, WithEvents
{
    use RemembersRowNumber, RegistersEventListeners;

    /** @var int */
    private $limitRows;

    /** @var string */
    public $importId;

    /**
     * RowsImport constructor.
     * @param int $totalRowsCount
     * @param string $importId
     */
    public function __construct(int $totalRowsCount, string $importId)
    {
        $this->limitRows = $totalRowsCount;
        $this->importId = $importId;
    }

    public function model(array $row)
    {
        $rowNumber = $this->getRowNumber();

        if ($rowNumber % $this->batchSize() === 0) {
            $this->setProgress($rowNumber);
        }

        if (!$row['id']) {
            return null;
        }

        return new Row([
            'id' => $row['id'],
            'name' => $row['name'],
            'date' => Date::excelToDateTimeObject($row['date']),
        ]);
    }

    /***
     * @param BeforeImport $event
     */
    public static function beforeImport(BeforeImport $event)
    {
        /** @var RowsImport $import */
        $import = $event->getConcernable();
        $import->setProgress(0);
    }

    /**
     * @param AfterImport $event
     */
    public static function afterImport(AfterImport $event)
    {
        /** @var RowsImport $import */
        $import = $event->getConcernable();
        $import->setProgress($import->limitRows);
    }

    /**
     * @param int $processedRows
     */
    public function setProgress(int $processedRows)
    {
        $percents = $processedRows / $this->limitRows * 100;
        Cache::store('redis')->put($this->getProgressKey(), $percents);
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limitRows;
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return string
     */
    public function endColumn(): string
    {
        return 'C';
    }

    /**
     * @return string
     */
    private function getProgressKey(): string
    {
        return 'IMPORT_PROGRESS:' . $this->importId;
    }
}

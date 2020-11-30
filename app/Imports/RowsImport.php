<?php

namespace App\Imports;

use App\Events\ImportProgressChanged;
use App\Models\Row;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RowsImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithLimit,
    WithColumnLimit,
    WithCalculatedFormulas
{
    use RemembersRowNumber;

    /** @var int */
    private $limitRows;

    /** @var string */
    public $importJobUuid;

    /**
     * RowsImport constructor.
     * @param int $totalRowsCount
     * @param string $importJobUuid
     * @throws \Exception
     */
    public function __construct(int $totalRowsCount, string $importJobUuid)
    {
        if ($totalRowsCount === 1) {
            throw new \Exception('You cannot import a file without data (your file contains only a header)');
        }

        $this->limitRows = $totalRowsCount;
        $this->importJobUuid = $importJobUuid;
    }

    /**
     * @param array $row
     * @return Row|null
     * @throws \Exception
     */
    public function model(array $row)
    {
        $rowNumber = $this->getRowNumber() - 1;

        if ($rowNumber % $this->batchSize() === 0 || $rowNumber === $this->limitRows) {
            $this->setProgress($rowNumber);
        }

        // to prevent processing empty rows
        if (!$row['id'] || !$row['name'] || !$row['date']) {
            return null;
        }

        $this->validateSingleRow($row);

        return new Row([
            'id' => $row['id'],
            'name' => $row['name'],
            'date' => Date::excelToDateTimeObject($row['date']),
        ]);
    }

    /**
     * @return string[]
     */
    private function validationRules(): array
    {
        return [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'date' => 'required|date',
        ];
    }

    /**
     * @param array $row
     * @throws \Exception
     */
    private function validateSingleRow(array $row)
    {
        try {
            $inputDate = $row['date'] ?? null;
            $row['date'] = isset($row['date']) ? Date::excelToDateTimeObject($row['date']) : null;
        } catch (\ErrorException $e) {
            $row['date'] = $inputDate;
        }

        $validator = Validator::make($row, $this->validationRules());

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            throw new \Exception("Error '$error' in row {$this->getRowNumber()}");
        }
    }

    /**
     * @param int $processedRows
     */
    public function setProgress(int $processedRows)
    {
        $percents = (int)($processedRows / $this->limitRows * 100);
        Cache::store('redis')->put($this->getProgressKey(), $percents);
        event(new ImportProgressChanged($this->importJobUuid, $percents));
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
        return 'IMPORT_PROGRESS:' . $this->importJobUuid;
    }
}

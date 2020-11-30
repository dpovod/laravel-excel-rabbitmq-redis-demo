<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Events\ImportFailed;
use App\Events\ImportProgressChanged;
use App\Imports\RowsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

/**
 * Class ParseExcelFileToDb
 * @package App\Jobs
 */
class ParseExcelFileToDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string */
    private $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        event(new ImportProgressChanged($this->job->uuid(), 0));
        $filePath = storage_path('app/' . $this->filePath);
        $inputFileType = IOFactory::identify($filePath);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $rowsCount = $spreadsheet->getActiveSheet()->getHighestDataRow();

        try {
            Excel::import(new RowsImport($rowsCount, $this->job->uuid()), $this->filePath);
        } catch (ValidationException $e) {
            $error = $e->failures()[0]->errors()[0];
            event(new ImportFailed($this->job->uuid(), $error));
        } catch (\Exception $e) {
            event(new ImportFailed($this->job->uuid(), $e->getMessage()));
        }

        Storage::delete($this->filePath);
    }
}

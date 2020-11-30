<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\HeadingRowImport;

/**
 * Class UploadExcelFileRequest
 * @package App\Http\Requests
 */
class UploadExcelFileRequest extends ApiRequest
{
    private const ALLOWED_MIME_TYPES = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    private const EXCEL_READERS = [
        'xls' => Excel::XLS,
        'xlsx' => Excel::XLSX,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimetypes:' . implode(',', self::ALLOWED_MIME_TYPES),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'file.mimetypes' => 'File must be an xls/xlsx',
            'file.*' => 'File is required',
        ];
    }

    /**
     * @return string[]
     */
    private function headerColumns(): array
    {
        return ['id', 'name', 'date'];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->validateTableHeader($validator);
        });
    }

    /**
     * @param Validator $validator
     * @throws \Exception
     */
    private function validateTableHeader(Validator $validator)
    {
        $file = $this->file('file');
        $readerType = $this->getExcelReaderType($file->getClientOriginalExtension());
        $headings = (new HeadingRowImport())->toArray($file->path(), null, $readerType);
        $headingRow = $headings[0][0] ?? [];
        $headingRow = array_reverse($headingRow);

        foreach ($this->headerColumns() as $column) {
            if (!in_array($column, $headingRow)) {
                $validator->errors()
                    ->add('header', "Table header is invalid. Columns 'id', 'name' and 'date' must be present.");
                return;
            }
        }
    }

    /**
     * @param string $fileExtension
     * @return string
     * @throws \Exception
     */
    private function getExcelReaderType(string $fileExtension): string
    {
        if (!isset(self::EXCEL_READERS[$fileExtension])) {
            throw new \Exception("Could not find excel reader for '$fileExtension' files");
        }

        return self::EXCEL_READERS[$fileExtension];
    }
}

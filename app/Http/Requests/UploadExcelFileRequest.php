<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Requests\Base\ApiRequest;

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
}

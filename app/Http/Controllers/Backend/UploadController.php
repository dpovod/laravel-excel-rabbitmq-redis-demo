<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadExcelFileRequest;
use App\Jobs\ParseExcelFileToDb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

/**
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends Controller
{
    /**
     * @param UploadExcelFileRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function submitFile(UploadExcelFileRequest $request): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();

        if (!$path = $file->storeAs('uploaded_files', time() . '-' . $fileName)) {
            throw new \Exception('Unable to store the file');
        }

        ParseExcelFileToDb::dispatch($path);

        return response()->json([
            'success' => true,
            'path' => $path,
        ]);
    }
}

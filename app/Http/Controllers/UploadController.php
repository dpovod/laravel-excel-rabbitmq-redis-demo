<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UploadExcelFileRequest;
use App\Jobs\ParseExcelFileToDb;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

/**
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function showUploadForm()
    {
        return view('upload.upload-form');
    }

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

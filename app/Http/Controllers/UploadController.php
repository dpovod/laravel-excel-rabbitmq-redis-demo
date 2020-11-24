<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadExcelFileRequest;
use Illuminate\Http\UploadedFile;

class UploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload.upload-form');
    }

    public function submitFile(UploadExcelFileRequest $request)
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();

        return response()->json([
            'success' => true,
            'path' => $file->storeAs('uploaded_files', time() . '-' . $fileName),
        ]);
    }
}

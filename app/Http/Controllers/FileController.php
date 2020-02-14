<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Controller constructors.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return the file requested from local storage
     */
    public function __invoke($folderPath, $filePath)
    {
        $path = $folderPath.DIRECTORY_SEPARATOR.$filePath;
        if(!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $localPath = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR.$path;
        
        return response()->file($localPath);
    }
}
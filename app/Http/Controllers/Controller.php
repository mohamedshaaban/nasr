<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $successStatus = 200;

    protected $vendor_guard = 'vendor';

    public function afterString($string, $inthat)
    {
        if (!is_bool(strpos($inthat, $string)))
            return substr($inthat, strpos($inthat, $string) + strlen($string));
    }

    public function moveFile($oldPath, $newPath)
    {
        $full_path_source = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($oldPath);
        $full_path_dest = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($newPath);
        if (!File::exists(dirname($full_path_dest))) {
            File::makeDirectory(dirname($full_path_dest),0755, true);
        }
        File::move($full_path_source, $full_path_dest);
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class MoveProductImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $isMainImage;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, $isMainImage = true)
    {
        $this->product = $product;
        $this->isMainImage = $isMainImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isMainImage) {
            $this->moveMainImage();
        } else {
            $this->moveImages();
        }
    }

    public function moveMainImage()
    {
        $product = $this->product;

        $oldPath = $product->main_image;
        $newPath = date('Y') . '/products/' . $product->id . '/' . $this->afterString(date('Y') . '/products/', $product->main_image);

        $product->main_image = $newPath;
        $product->save();

        $this->moveFile($oldPath, $newPath);
    }

    public function moveImages()
    {
        $product = $this->product;
        $oldPathsArray = $product->images;
        $newPathsArray = [];
        foreach ($oldPathsArray as $oldPath) {
            if (file_exists(public_path() . '/uploads/' . $oldPath)) {
                $isNewImage = substr($this->afterString('products/', $oldPath), 0, strlen($product->id) + 1) != $product->id . '/' ? true : false;
                if ($isNewImage) {
                    $newPath = date('Y') . '/products/' . $product->id . '/' . $this->afterString('products/', $oldPath);
                    $this->moveFile($oldPath, $newPath);
                    $newPathsArray[] = $newPath;
                } else {
                    $newPathsArray[] = $oldPath;
                }
            }
        }
        $product->images = $newPathsArray;
        $product->save();
    }

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
            File::makeDirectory(dirname($full_path_dest), 0755, true);
        }
        File::move($full_path_source, $full_path_dest);
    }
}

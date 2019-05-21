<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Photos;

use Image;
use App\Http\Requests;
use Bpocallaghan\Titan\Models\Article;
use Bpocallaghan\Titan\Models\News;
use Bpocallaghan\Titan\Models\Photo;
use Bpocallaghan\Titan\Models\Product;
use Bpocallaghan\Titan\Models\PhotoAlbum;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class CropperController extends AdminController
{
    private $LARGE_SIZE = [800, 800];

    private $THUMB_SIZE = [400, 400];

    /**
     * @param       $photoable
     * @param Photo $photo
     * @return this
     */
    private function showCropper($photoable, Photo $photo)
    {
        return $this->view('titan::photos.cropper')->with('photoable', $photoable)->with('photo', $photo);
    }

    /**
     * @param News  $news
     * @param Photo $photo
     * @return this
     */
    public function showNewsPhoto(News $news, Photo $photo)
    {
        return $this->showCropper($news, $photo);
    }

    /**
     * @param PhotoAlbum $album
     * @param Photo      $photo
     * @return this
     */
    public function showAlbumsPhoto(PhotoAlbum $album, Photo $photo)
    {
        return $this->showCropper($album, $photo);
    }

    /**
     * @param Article $article
     * @param Photo   $photo
     * @return this
     */
    public function showArticlesPhoto(Article $article, Photo $photo)
    {
        return $this->showCropper($article, $photo);
    }

    /**
     * @param Product $product
     * @param Photo   $photo
     * @return this
     */
    public function showProductPhoto(Product $product, Photo $photo)
    {
        return $this->showCropper($product, $photo);
    }


    /**
     * Crop a photo
     * @param Photo $photo
     * @return \Illuminate\Http\JsonResponse
     */
    public function cropPhoto(Photo $photo)
    {
        $photoable = input('photoable_type')::find(input('photoable_id'));

        // if relationship not found
        if (!$photoable) {
            return json_response_error('Whoops', 'We could not find the photoable.');
        }

        // get the large and thumb sizes
        if (isset($photoable::$LARGE_SIZE)) {
            $largeSize = $photoable::$LARGE_SIZE;
            $thumbSize = $photoable::$THUMB_SIZE;
        }
        else {
            $largeSize = $this->LARGE_SIZE;
            $thumbSize = $this->THUMB_SIZE;
        }

        // open file image resource
        $path = upload_path('photos');
        $originalImage = Image::make($photo->original_url);

        // get the crop data
        $x = intval(input('x'));
        $y = intval(input('y'));
        $width = intval(input('width'));
        $height = intval(input('height'));

        // generate new name (bypass cache)
        $photo->update([
            'filename' => token() . "{$photo->extension}"
        ]);

        // save original image with new name
        $originalImage->save($path . $photo->original_filename);

        // crop image on cropped area
        $imageTmp = $originalImage->crop($width, $height, $x, $y);

        // resize the image to large size
        $imageTmp->resize($largeSize[0], null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . $photo->filename);

        // resize the image to thumb size
        $imageTmp->resize($thumbSize[0], null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . $photo->thumb);

        return json_response('success');
    }
}
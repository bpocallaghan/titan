<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Photos;

use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\News;
use Bpocallaghan\Titan\Models\Page;
use Bpocallaghan\Titan\Models\Photo;
use Bpocallaghan\Titan\Http\Requests;
use Bpocallaghan\Titan\Models\Article;
use Bpocallaghan\Titan\Models\PhotoAlbum;
use Bpocallaghan\Titan\Models\PageContent;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class PhotosOrderController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Photo::orderBy('list_order')->get();

        return $this->view('titan::photos.order')->with('items', $items);
    }

    /**
     * Show the Photoable's photos
     * Create / Edit / Delete the photos
     * @param $photoable
     * @param $photos
     * @return mixed
     */
    private function showPhotoable($photoable, $photos)
    {
        save_resource_url();


        return $this->view('titan::photos.order')
            ->with('videos', $photoable->videos)
            ->with('photoable', $photoable)
            ->with('photos', $photos)
            ->with('items', $photoable->photos);
    }

    /**
     * @param Page $page
     * @param PageContent $content
     * @return mixed
     */
    public function showPageContentPhotos(Page $page, PageContent $content)
    {
        return $this->showPhotoable($content, $content->photos);
    }

    /**
     * Show the News' photos
     * @param News $news
     * @return mixed
     */
    public function showNewsPhotos(News $news)
    {
        return $this->showPhotoable($news, $news->photos);
    }

    /**
     * Show the album's photos
     * @param PhotoAlbum $album
     * @return mixed
     */
    public function showAlbumPhotos(PhotoAlbum $album)
    {
        return $this->showPhotoable($album, $album->photos);
    }

    /**
     * Show the article's photos
     * @param Article $article
     * @return mixed
     */
    public function showArticlePhotos(Article $article)
    {
        return $this->showPhotoable($article, $article->photos);
    }

    /**
     * Update the order
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {

        $items = json_decode($request->get('list'), true);

        foreach ($items as $key => $item) {
            $photo = Photo::find($item['id'])->update(['list_order' => ($key + 1)]);
        }

        return ['result' => 'success'];
    }
}

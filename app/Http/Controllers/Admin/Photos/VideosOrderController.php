<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Photos;

use Bpocallaghan\Titan\Models\Video;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\News;
use Bpocallaghan\Titan\Models\Page;
use Bpocallaghan\Titan\Models\Photo;
use Bpocallaghan\Titan\Http\Requests;
use Bpocallaghan\Titan\Models\Article;
use Bpocallaghan\Titan\Models\PhotoAlbum;
use Bpocallaghan\Titan\Models\PageContent;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class VideosOrderController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Video::orderBy('list_order')->get();

        return $this->view('titan::photos.videos.order')->with('items', $items);
    }

    /**
     * Show the Videoable's videos
     * Create / Edit / Delete the videos
     * @param $videoable
     * @param $videos
     * @return mixed
     */
    private function showVideoable($videoable, $videos)
    {
        save_resource_url();

        return $this->view('titan::photos.videos.order')
            ->with('videos', $videos)
            ->with('videoable', $videoable)
            ->with('items', $videos);
    }

    /**
     * Show the News' photos
     * @return mixed
     */
    public function showVideos($id)
    {
        $model = app(session('photoable_type'));
        $model = $model->find($id);

        return $this->showVideoable($model, $model->videos);
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
            $photo = Video::find($item['id'])->update(['list_order' => ($key + 1)]);
        }

        return ['result' => 'success'];
    }
}

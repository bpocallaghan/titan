<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Pages;

use Bpocallaghan\Titan\Models\Banner;
use Redirect;
use App\Http\Requests;
use Bpocallaghan\Titan\Models\Page;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class PagesController extends AdminController
{
    /**
     * Display a listing of page.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        $items = Page::with('parent')->get();

        return $this->view('titan::pages.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new page.
     *
     * @return Response
     */
    public function create()
    {
        $parents = Page::getAllList();
        $banners = Banner::getAllList();

        return $this->view('titan::pages.create_edit', compact('parents', 'banners'));
    }

    /**
     * Store a newly created page in storage.
     *
     * @return Response
     */
    public function store()
    {
        $attributes = request()->validate(Page::$rules, Page::$messages);

        $attributes['is_header'] = boolval(input('is_header'));
        $attributes['is_hidden'] = boolval(input('is_hidden'));
        $attributes['is_footer'] = boolval(input('is_footer'));
        $attributes['is_featured'] = boolval(input('is_featured'));
        $attributes['url_parent_id'] = ($attributes['url_parent_id'] == 0 ? $attributes['parent_id'] : $attributes['url_parent_id']);

        $page = $this->createEntry(Page::class, $attributes);

        if ($page) {
            $page->updateUrl()->save();
            $page->banners()->sync(input('banners'));
        }

        return redirect_to_resource();
    }

    /**
     * Display the specified page.
     *
     * @param Page $page
     * @return Response
     */
    public function show(Page $page)
    {
        return $this->view('titan::pages.show')->with('item', $page);
    }

    /**
     * Show the form for editing the specified page.
     *
     * @param Page $page
     * @return Response
     */
    public function edit(Page $page)
    {
        // fix for when you
        // edit sections from the edit page
        // or delete photo/document then
        // need to update resource url
        save_resource_url();

        $parents = Page::getAllList();
        $banners = Banner::getAllList();

        return $this->view('titan::pages.create_edit', compact('parents', 'banners'))->with('item', $page);
    }

    /**
     * Update the specified page in storage.
     *
     * @param Page $page
     * @return Response
     */
    public function update(Page $page)
    {
        $attributes = request()->validate(Page::$rules, Page::$messages);

        $attributes['is_header'] = boolval(input('is_header'));
        $attributes['is_hidden'] = boolval(input('is_hidden'));
        $attributes['is_footer'] = boolval(input('is_footer'));
        $attributes['is_featured'] = boolval(input('is_featured'));

        $page = $this->updateEntry($page, $attributes);
        $page->updateUrl()->save();
        $page->banners()->sync(input('banners'));

        return redirect_to_resource();
    }

    /**
     * Remove the specified page from storage.
     *
     * @param Page $page
     * @return Response
     */
    public function destroy(Page $page)
    {
        $this->deleteEntry($page, request());

        return redirect_to_resource();
    }
}

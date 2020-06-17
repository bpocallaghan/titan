<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\General\FAQ;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\FaqCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class CategoriesController extends AdminController
{
    /**
     * Display a listing of faq_category.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        return $this->view('titan::faqs.categories.index')->with('items', FaqCategory::all());
    }

    /**
     * Show the form for creating a new faq_category.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('titan::faqs.categories.create_edit');
    }

    /**
     * Store a newly created faq_category in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, FaqCategory::$rules, FaqCategory::$messages);

        $this->createEntry(FaqCategory::class, $request->only('name'));

        return redirect_to_resource();
    }

    /**
     * Display the specified faq_category.
     *
     * @param FaqCategory $category
     * @return Response
     */
    public function show(FaqCategory $category)
    {
        return $this->view('titan::faqs..categories.show')->with('item', $category);
    }

    /**
     * Show the form for editing the specified faq_category.
     *
     * @param FaqCategory $category
     * @return Response
     */
    public function edit(FaqCategory $category)
    {
        return $this->view('titan::faqs.categories.create_edit')->with('item', $category);
    }

    /**
     * Update the specified faq_category in storage.
     *
     * @param FaqCategory $category
     * @param Request     $request
     * @return Response
     */
    public function update(FaqCategory $category, Request $request)
    {
        $this->validate($request, FaqCategory::$rules, FaqCategory::$messages);

        $this->updateEntry($category, $request->only('name'));

        return redirect_to_resource();
    }

    /**
     * Remove the specified faq_category from storage.
     *
     * @param FaqCategory $category
     * @param Request     $request
     * @return Response
     */
    public function destroy(FaqCategory $category, Request $request)
    {
        $this->deleteEntry($category, $request);

        return redirect_to_resource();
    }
}

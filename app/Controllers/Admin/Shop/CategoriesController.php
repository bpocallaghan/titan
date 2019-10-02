<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Shop;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\ProductCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class CategoriesController extends AdminController
{
    /**
     * Display a listing of product_category.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        $items = ProductCategory::with('parent')->get();

        return $this->view('titan::shop.categories.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new product_category.
     *
     * @return Response
     */
    public function create()
    {
        $parents = ProductCategory::getAllList();

        return $this->view('titan::shop.categories.create_edit')->with('parents', $parents);
    }

    /**
     * Store a newly created product_category in storage.
     *
     * @return Response
     */
    public function store()
    {
        $attributes = request()->validate(ProductCategory::$rules, ProductCategory::$messages);

        $category = $this->createEntry(ProductCategory::class, $attributes);

        $category->updateUrl()->save();;

        return redirect_to_resource();
    }

    /**
     * Display the specified product_category.
     *
     * @param ProductCategory $category
     * @return Response
     */
    public function show(ProductCategory $category)
    {
        return $this->view('titan::shop.categories.show')->with('item', $category);
    }

    /**
     * Show the form for editing the specified product_category.
     *
     * @param ProductCategory $category
     * @return Response
     */
    public function edit(ProductCategory $category)
    {
        $parents = ProductCategory::getAllList();

        return $this->view('titan::shop.categories.create_edit')
            ->with('item', $category)
            ->with('parents', $parents);
    }

    /**
     * Update the specified product_category in storage.
     *
     * @param ProductCategory $category
     * @return Response
     */
    public function update(ProductCategory $category)
    {
        $attributes = request()->validate(ProductCategory::$rules, ProductCategory::$messages);

        $category = $this->updateEntry($category, $attributes);

        $category->updateUrl()->save();;

        return redirect_to_resource();
    }

    /**
     * Remove the specified product_category from storage.
     *
     * @param ProductCategory $category
     * @return Response
     */
    public function destroy(ProductCategory $category)
    {
        $this->deleteEntry($category, request());

        return redirect_to_resource();
    }
}

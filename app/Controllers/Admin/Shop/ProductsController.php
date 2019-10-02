<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Shop;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\Product;
use Bpocallaghan\Titan\Models\ProductCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class ProductsController extends AdminController
{
    /**
     * Display a listing of product.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        $items = Product::with('category')->get();

        return $this->view('titan::shop.products.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return Response
     */
    public function create()
    {
        $categories = ProductCategory::getAllList();

        return $this->view('titan::shop.products.create_edit')
            ->with('categories', $categories);
    }

    /**
     * Store a newly created product in storage.
     *
     * @return Response
     */
    public function store()
    {
        $attributes = request()->validate(Product::$rules, Product::$messages);

        $attributes['in_stock'] = (bool) input('in_stock');
        $product = $this->createEntry(Product::class, $attributes);

        return redirect_to_resource();
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        return $this->view('titan::shop.products.show')->with('item', $product);
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        save_resource_url();

        $categories = ProductCategory::getAllList();

        return $this->view('titan::shop.products.create_edit')
            ->with('item', $product)
            ->with('categories', $categories);
    }

    /**
     * Update the specified product in storage.
     *
     * @param Product $product
     * @return Response
     */
    public function update(Product $product)
    {
        $attributes = request()->validate(Product::$rules, Product::$messages);

        $attributes['in_stock'] = (bool) input('in_stock');
        $product = $this->updateEntry($product, $attributes);

        return redirect_to_resource();
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        $this->deleteEntry($product, request());

        return redirect_to_resource();
    }
}

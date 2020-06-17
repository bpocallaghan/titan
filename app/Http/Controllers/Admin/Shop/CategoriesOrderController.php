<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Shop;

use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\ProductCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class CategoriesOrderController extends AdminController
{
    private $defaultParent = 0;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $itemsHtml = $this->getListOrderHtml($this->defaultParent);

        return $this->view('titan::shop.categories.order', compact('itemsHtml'));
    }

    /**
     * Update the order of navigation
     *
     * @param Request $request
     * @return array
     */
    public function updateListOrder(Request $request)
    {
        $navigation = json_decode($request->get('list'), true);

        foreach ($navigation as $key => $nav) {

            $idd = $this->defaultParent ? $this->defaultParent->id : 0;
            $row = $this->updateResourceOrder($nav['id'], ($key + 1), $idd);

            $this->updateIfHasChildren($nav);
        }

        return ['result' => 'success'];
    }

    /**
     * Generate the nestable html
     *
     * @param null $parent
     *
     * @return string
     */
    private function getListOrderHtml($parent = null)
    {
        $html = '<ol class="dd-list">';

        $parentId = ($parent ? $parent->id : 0);
        $items = ProductCategory::where('parent_id', $parentId)->orderBy('list_order')->get();

        foreach ($items as $key => $nav) {
            $html .= '<li class="dd-item" data-id="' . $nav->id . '">';
            $html .= '<div class="dd-handle">';
            $html .= $nav->name . ' <span style="float:right"> ' . $nav->url . ' </span></div>';
            $html .= $this->getListOrderHtml($nav);
            $html .= '</li>';
        }

        $html .= '</ol>';

        return (count($items) >= 1 ? $html : '');
    }

    /**
     * Loop through children and update list order (recursive)
     *
     * @param $nav
     */
    private function updateIfHasChildren($nav)
    {
        if (isset($nav['children']) && count($nav['children']) > 0) {
            $children = $nav['children'];
            foreach ($children as $c => $child) {
                $row = $this->updateResourceOrder($child['id'], ($c + 1), $nav['id']);

                $this->updateIfHasChildren($child);
            }
        }
    }

    /**
     * Update Navigation Item, with new list order and parent id (list and parent can change)
     *
     * @param     $id
     * @param     $listOrder
     * @param int $parentId
     *
     * @return mixed
     */
    private function updateResourceOrder($id, $listOrder, $parentId = 0)
    {
        $row = ProductCategory::find($id);
        $row->parent_id = $parentId;
        $row->updateUrl();
        $row['list_order'] = $listOrder;
        $row->save();

        return $row;
    }
}
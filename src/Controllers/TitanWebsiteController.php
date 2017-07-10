<?php

namespace Titan\Controllers;

use App\Http\Requests;
use App\Models\Advertisement;
use App\Models\Banner;
use App\Models\NavigationWebsite;
use Titan\Controllers\Traits\BreadcrumbWebsite;
use Titan\Controllers\Traits\PopupEntry;

class TitanWebsiteController extends TitanController
{
    use BreadcrumbWebsite, PopupEntry;

    protected $baseViewPath = 'website.';

    protected $baseViewSubPath = '';

    protected $pageTitle;

    protected $breadcrumb = '';

    function __construct()
    {
        $this->setSelectedNavigation();

        $this->generateBreadcrumb();
    }

    /**
     * Get the HTML Title
     * @return string
     */
    protected function getPageTitle()
    {
        return (strlen($this->pageTitle) < 2 ? $this->selectedNavigation['html_title'] : $this->pageTitle);
    }

    /**
     * Return / Render the view
     * @param       $view
     * @param array $data
     * @return $this
     */
    protected function view($view, $data = [])
    {
        return view($this->baseViewPath . $this->baseViewSubPath . $view, $data)
            ->with('HTMLTitle', $this->getTitle())
            ->with('HTMLDescription', $this->getDescription())
            ->with('HTMLImage', $this->getImage())
            ->with('navigation', $this->generateNavigation())
            ->with('breadcrumb', $this->breadcrumbHTML())
            ->with('pageTitle', $this->getPageTitle())
            ->with('banners', $this->getBanners())
            ->with('selectedNavigation', $this->selectedNavigation);
    }

    /**
     * Get the html title (check for crud reserve word)
     * @return string
     */
    protected function getTitle()
    {
        $navigation = array_reverse($this->urlParentNavs);
        $this->title = strlen($this->title) > 5 ? $this->title . ' - ' : '';

        foreach ($navigation as $key => $nav) {
            $this->title .= $nav['html_title'] . ($key + 1 < count($navigation) ? ' - ' : '');
        }

        return parent::getTitle();
    }

    /**
     * Get the html title (check for crud reserve word)
     * @return string
     */
    protected function getDescription()
    {
        // this just allows the controller to overide the description
        if (strlen($this->description) <= 5) {
            $this->description = $this->selectedNavigation['html_description'];
        }

        return parent::getDescription();
    }

    /**
     * Get the selected navigation
     * @return mixed
     */
    protected function setSelectedNavigation()
    {
        $url = $this->getCurrentUrl();
        $sections = $this->getCurrentUrlSections();

        // laravel removes last / get home / dashboard
        if ($url === false) {
            $nav = NavigationWebsite::where('slug', '/')->get()->first();
        }
        else {
            // find nav with url - get last (parent can have same url)
            $nav = NavigationWebsite::where('url', $url)
                ->orderBy('is_hidden', 'DESC')
                ->orderBy('url_parent_id')
                ->orderBy('list_main_order')
                ->get()
                ->last();
        }

        // we assume some params / reserved word is at the end
        if (!$nav && strlen($url) > 2) {
            // keep cutting off from url until we find him in the db
            foreach ($sections as $key => $slug) {
                $url = substr($url, 0, strripos($url, '/'));

                // find nav with url - get last (parent can have same url)
                $nav = NavigationWebsite::whereUrl($url)->get()->last();
                if ($nav) {
                    break;
                }
            }
        }

        // when nothing - fall back to home
        if (!$nav) {
            $nav = NavigationWebsite::find(1);
            if (config('app.env') == 'local' && !$nav) {
                dd('Whoops. Navigation not found - please see if url is in database (navigation_website)');
            }
        }

        // load banners relationship
        $this->selectedNavigation = $nav;

        // get all navigations -> ON parent_id
        $this->parentNavs = $nav->getParentsAndYou();

        // get all navigations -> ON url_parent_id
        $this->urlParentNavs = $nav->getUrlParentsAndYou();

        return $this->selectedNavigation;
    }

    /**
     * Generate the Main Navigation's HTML + show Active
     * @return string
     */
    protected function generateNavigation()
    {
        $html = '';
        $navigation = NavigationWebsite::mainNavigation();

        foreach ($navigation as $key => $nav) {

            $active = (array_search_value($nav->id,
                $this->urlParentNavs) ? 'active ' : '');

            $children = $this->generateNavigationChildren($nav);

            $link = (strlen($children) < 2 ? url($nav->url) : '#');
            $childrenClass = (strlen($children) < 2 ? '' : ' dropdown ');
            $childrenClassAnchor = (strlen($children) < 2 ? '' : ' class="dropdown-toggle" data-toggle="dropdown" ');

            $html .= '<li class="' . $active . $childrenClass . '"><a href="' . $link . '" ' . $childrenClassAnchor . '>';
            $html .= $nav->title;
            $html .= (strlen($children) < 2 ? '' : ' <b class="caret"></b>');
            $html .= '</a>' . $children . '</li>';
        }

        return $html;
    }

    /**
     * Recursive generate the menu for all the children of given $nav
     * @param $parent
     * @return string
     */
    private function generateNavigationChildren($parent)
    {
        $html = '';
        $navigation = NavigationWebsite::whereParentIdORM($parent->id);

        $html .= '<ul class="dropdown-menu">';
        foreach ($navigation as $key => $nav) {

            $url = (is_slug_url($nav->slug) ? $nav->slug : url($nav->url));
            $children = NavigationWebsite::whereParentIdORM($nav->id);

            $html .= '<li>';
            $html .= '<a tabindex="-1" href="' . (count($children) > 0 ? '#' : $url) . '">' . $nav->title . '</a>';

            // if children
            if (count($children) > 0) {
                $html .= '<ul>';
            }

            foreach ($children as $c => $child) {
                $url = (is_slug_url($child->slug) ? $child->slug : url($child->url));

                $html .= '<li><a tabindex="-1" href="' . $url . '">' . $child->title . '</a></li>';
            }

            // if children
            if (count($children) > 0) {
                $html .= '</ul>';
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return (count($navigation) > 0 ? $html : '');
    }

    protected function getBanners()
    {
        $items = Banner::active()->get();

        return $items;
    }
}
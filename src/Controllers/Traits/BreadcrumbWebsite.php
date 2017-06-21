<?php

namespace Titan\Controllers\Traits;

trait BreadcrumbWebsite
{
    /**
     * Init and Generate the website's breadcrumb nav bar
     */
    private function generateBreadcrumb()
    {
        $this->breadcrumbMenus = collect();
        $this->addBreadcrumbLink('Home', '/', 'home');

        $prevTitle = 'Home';
        $navs = $this->selectedNavigation->getParentsAndYou();
        foreach ($navs as $k => $nav) {

            if ($nav->title != $prevTitle) {
                $url = (is_slug_url($nav->slug) ? $nav->slug : url($nav->url));
                $this->addBreadcrumbLink($nav->title, $url);
            }

            $prevTitle = $nav->title;
        }
    }

    /**
     * Generate the html for the breadcrumb
     * TODO - send this to view to add flexibility - or view composer
     * @return string
     */
    private function breadcrumbHTML()
    {
        $html = '';
        $total = count($this->breadcrumbMenus) - 1;

        foreach ($this->breadcrumbMenus as $k => $menu) {

            if ($k == $total) {
                $html .= '<li>' . $menu['title'] . '</li>';
            }
            else {
                $html .= '<li><a tabindex="-1" href="' . $menu['url'] . '" class="' . $menu['class'] . '">' . $menu['title'] . '</a></li>';
            }
        }

        return $html;
    }

    /**
     * Add a link to the breadcrumb
     * @param        $title
     * @param        $url
     * @param string $class
     */
    public function addBreadcrumbLink($title, $url, $class = '')
    {
        $this->breadcrumbMenus->push(['title' => $title, 'url' => $url, 'class' => $class]);
    }
}
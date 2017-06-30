<?php

namespace Titan\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use LaravelAnalytics;

/**
 * https://github.com/spatie/laravel-analytics
 * http://www.colorhexa.com
 *
 * Class Analytics
 * @package Titan\Controllers\Traits
 */
trait AnalyticsHelper
{
    protected $datasets = [
        [
            'label'                => "",
            'fillColor'            => "rgba(60, 141, 188, 0.1)",
            'strokeColor'          => "rgba(60, 141, 188, 1)",
            'pointColor'           => "#3b8bba",
            'pointStrokeColor'     => "rgba(60,141,188,1)",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220, 220, 220, 1)",
            'data'                 => [],
        ],
        [
            'label'                => "",
            'fillColor'            => "rgba(0, 141, 76, 0.1)",
            'strokeColor'          => "rgba(0, 141, 76, 1)",
            'pointColor'           => "rgba(0, 141, 76, 1)",
            'pointStrokeColor'     => "#fff",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220, 220, 220, 1)",
            'data'                 => [],
        ],
        [
            'label'                => "",
            'fillColor'            => "rgba(60, 141, 188, 0.1)",
            'strokeColor'          => "rgba(60, 141, 188, 1)",
            'pointColor'           => "#3b8bba",
            'pointStrokeColor'     => "rgba(60,141,188,1)",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220, 220, 220, 1)",
            'data'                 => [],
        ]
    ];

    protected $pieData = [
        [
            'color'     => "#dd4b39",
            'highlight' => "#e15f4f",
        ],
        [
            'color'     => "#00a65a",
            'highlight' => "#00c068",
        ],
        [
            'color'     => "#f39c12",
            'highlight' => "#f4a62a",
        ],
        [
            'color'     => "#00c0ef",
            'highlight' => "#09cfff",
        ],
        [
            'color'     => "#ff2e9f",
            'highlight' => "#ff3434",
        ],
        [
            'color'     => "#307095",
            'highlight' => "#367ea8",
        ],
        [
            'color'     => "#d2d6de",
            'highlight' => "#c3c9d3",
        ],
    ];

    /**
     * Get this months Visitors
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function getVisitors()
    {
        return $this->monthlyComparison('ga:users');
    }

    /**
     * Get this months Unique Visitors
     * @return int|string
     */
    public function getUniqueVisitors()
    {
        return $this->monthlyComparison('ga:newUsers');
    }

    /**
     * Get this months Visitors
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function getBounceRate()
    {
        return $this->monthlyComparison('ga:bounceRate');
    }

    /**
     * Get this months average page load time
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function getAvgPageLoad()
    {
        return json_response($this->monthlySummary('ga:avgPageLoadTime'));
    }

    /**
     * Get the top keywords for duration
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getKeywords()
    {
        $dates = $this->getStartEndDate();

        $items = LaravelAnalytics::getTopKeyWordsForPeriod($dates['start'], $dates['end']);

        return $items;
    }

    /**
     * Get the top referrers for duration
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getReferrers()
    {
        $dates = $this->getStartEndDate();

        $items = LaravelAnalytics::getTopReferrersForPeriod($dates['start'], $dates['end'], 30);

        return $items;
    }

    /**
     * Get the active users currently viewing the website
     * @return int
     */
    public function getActiveVisitors()
    {
        $total = LaravelAnalytics::getActiveUsers();

        return json_response($total);
    }

    /**
     * Get the visitors and page views for duration
     * Format result for CartJS
     * @return string
     */
    public function getVisitorsAndPageViews()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::getVisitorsAndPageViewsForPeriod($dates['start'], $dates['end']);

        $totalViews = ['labels' => []];
        $visitors = [];
        $pageviews = [];
        foreach ($data as $k => $item) {
            array_push($totalViews['labels'], $item['date']->format('d M'));

            array_push($visitors, $item['visitors']);
            array_push($pageviews, $item['pageViews']);
        }

        $totalViews['datasets'][] = $this->getDataSet('Page Views', $pageviews, 0);
        $totalViews['datasets'][] = $this->getDataSet('Visitors', $visitors, 1);

        return json_encode($totalViews);
    }

    /**
     * Get the most visited pages for duration
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getVisitedPages()
    {
        $dates = $this->getStartEndDate();

        $items = LaravelAnalytics::getMostVisitedPagesForPeriod($dates['start'], $dates['end']);

        return $items;
    }

    /**
     * Get the top browsers for duration
     * Format results for pie chart
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getBrowsers()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::getTopBrowsersForPeriod($dates['start'], $dates['end'], 7)
            ->toArray();

        $items = [];
        shuffle($data); // shuffle results / randomimize chart color sections
        foreach ($data as $k => $item) {
            $items[] = $this->getPieDataSet($item['browser'], $item['sessions'], $k);
        }

        return $items;
    }

    /**
     * Get the gender comparisons
     * @return array
     */
    public function getUsersGender()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::performQuery($dates['start'], $dates['end'], 'ga:sessions',
            ['dimensions' => 'ga:userGender']);

        $items = [];
        $rows = $data->rows;
        if (is_null($rows)) {
            return [];
        }
        shuffle($rows); // shuffle results / randomimize chart color sections
        foreach ($rows as $k => $item) {
            $items[] = $this->getPieDataSet(ucfirst($item[0]), $item[1]);
        }

        return $items;
    }

    /**
     * Get the users' age comparisons
     * @return array
     */
    public function getUsersAge()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::performQuery($dates['start'], $dates['end'], 'ga:sessions',
            ['dimensions' => 'ga:userAgeBracket']);

        $labels = [];
        $datasets = [];
        $rows = $data->rows;
        if (is_null($rows)) {
            return ['labels' => [], 'datasets' => []];
        }
        foreach ($rows as $k => $item) {
            $labels[] = ucfirst($item[0]);
            $datasets[] = $item[1];
        }

        $datasets = [$this->getDataSet('Ages', $datasets, 0)];

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    /**
     * Get the the users interests - affinity
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getInterestsAffinity()
    {
        return $this->getInterests('ga:interestAffinityCategory');
    }

    /**
     * Get the the users interests - market
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getInterestsMarket()
    {
        return $this->getInterests('ga:interestInMarketCategory');
    }

    /**
     * Get the the users interests - affinity
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getInterestsOther()
    {
        return $this->getInterests('ga:interestOtherCategory');
    }

    /**
     * Get all the devices by sessions
     * @return mixed
     */
    public function getDevices()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::performQuery($dates['start'], $dates['end'], 'ga:sessions', [
            'dimensions'  => 'ga:mobileDeviceInfo',
            'sort'        => '-ga:sessions',
            'max-results' => 30
        ]);

        if ($data->rows) {
            return $data->rows;
        }

        return [];
    }

    /**
     * Get the desktop vs mobile vs tablet
     */
    public function getDeviceCategory()
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::performQuery($dates['start'], $dates['end'], 'ga:sessions', [
            'dimensions' => 'ga:deviceCategory'
        ]);

        $items = [];
        foreach ($data->rows as $k => $item) {
            $items[] = $this->getPieDataSet(ucfirst($item[0]), $item[1]);
        }

        return $items;
    }

    /**
     * Get the the users interests
     * @param $dimensions
     * @return \Spatie\LaravelAnalytics\Collection
     */
    public function getInterests($dimensions)
    {
        $dates = $this->getStartEndDate();

        $data = LaravelAnalytics::performQuery($dates['start'], $dates['end'], 'ga:sessions', [
            'dimensions'  => $dimensions,
            'sort'        => '-ga:sessions',
            'max-results' => 30
        ]);

        if (is_null($data->rows)) {
            return [];
        }

        return $data->rows;
    }

    /**
     * Helper to get the months analytics
     * @param string $metrics
     * @param string $month
     * @return \Illuminate\Http\JsonResponse|int
     */
    private function monthlySummary($metrics = 'ga:users', $month = 'month')
    {
        if ($month == '-month_1') {
            $end = Carbon::now()->subMonth()->endOfMonth();
            $start = Carbon::now()->subMonth()->startOfMonth();
        }
        else {
            $end = Carbon::now();
            $start = Carbon::now()->startOfMonth();
        }

        $data = LaravelAnalytics::performQuery($start->startOfDay(), $end->endOfDay(), $metrics);

        if ($data->rows && count($data->rows) >= 1 && count($data->rows[0]) >= 1) {
            return $data->rows[0][0];
        }

        return 0;
    }

    /**
     * Get this and last month of the metrics for a comparison
     * @param string $metrics
     * @return \Illuminate\Http\JsonResponse
     */
    private function monthlyComparison($metrics = 'ga:users')
    {
        $thisMonth = $this->monthlySummary($metrics);
        $lastMonth = $this->monthlySummary($metrics, '-month_1');

        return json_response([
            'month'      => [
                'value'     => $thisMonth,
                'color'     => "#00c0ef",
                'highlight' => "#00a7d0",
                'label'     => "Current"
            ],
            'last_month' => [
                'value'     => $lastMonth,
                'color'     => "#00a65a",
                'highlight' => "#008d4c",
                'label'     => "Previous"
            ]
        ]);
    }

    /**
     * Get the start and end duration
     * @return array
     */
    private function getStartEndDate()
    {
        $start = input('start', date('Y-m-d', strtotime('-29 days')));
        $end = input('end', date('Y-m-d'));

        if (is_string($start)) {
            $start = \DateTime::createFromFormat('Y-m-d', $start);
        }

        if (is_string($end)) {
            $end = \DateTime::createFromFormat('Y-m-d', $end);
        }

        return compact('start', 'end');
    }

    /**
     * Get the line dataset opbject
     * @param     $label
     * @param     $data
     * @param int $index
     * @return mixed
     */
    private function getDataSet($label, $data, $index = 0)
    {
        $set = $this->datasets[$index];
        $set['label'] = $label;
        $set['data'] = $data;

        return $set;
    }

    /**
     * Get the pie chart data
     * @param     $label
     * @param     $data
     * @param int $index
     * @return mixed
     */
    private function getPieDataSet($label, $data, $index = -1)
    {
        if ($index < 0) {
            $index = rand(0, count($this->pieData) - 1);
        }

        $set = $this->pieData[$index];
        $set['label'] = $label;
        $set['value'] = $data;

        return $set;
    }
}
<?php

namespace Titan\Controllers\Traits;

use Analytics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;

/**
 * https://github.com/spatie/laravel-analytics
 * http://www.colorhexa.com
 *
 * Class Analytics
 * @package Titan\Controllers\Traits
 */
trait GoogleAnalyticsHelper
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
            // red
            'color'     => "#dd4b39",
            'highlight' => "#e15f4f",
        ],
        [
            // green
            'color'     => "#00a65a",
            'highlight' => "#00c068",
        ],
        [
            // orange
            'color'     => "#f39c12",
            'highlight' => "#f4a62a",
        ],
        [
            // blue
            'color'     => "#00c0ef",
            'highlight' => "#09cfff",
        ],
        [
            // pink
            'color'     => "#f012be",
            'highlight' => "#f012be",
        ],
        [
            // dark blue
            'color'     => "#307095",
            'highlight' => "#367ea8",
        ],
        [
            // gray
            'color'     => "#d2d6de",
            'highlight' => "#c3c9d3",
        ],
        [
            // navy
            'color'     => "#001f3f",
            'highlight' => "#001f3f",
        ],
        [
            // purple
            'color'     => "#605ca8",
            'highlight' => "#605ca8",
        ],
        [
            // dark red
            'color'     => "#3d9970",
            'highlight' => "#3d9970",
        ],
        [
            // fuchsia
            'color'     => "#f012be",
            'highlight' => "#f012be",
        ],
        [
            // olive
            'color'     => "#3d9970",
            'highlight' => "#3d9970",
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
     * @return \Spatie\Analytics\Collection
     */
    public function getKeywords()
    {
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions', [
            'max-results' => 30,
            'dimensions'  => 'ga:keyword',
            'sort'        => '-ga:sessions',
            //'filters'     => 'ga:keyword!=(not set);ga:keyword!=(not provided)'
        ]);

        $items = [];
        // if not null
        if (!is_null($data->rows)) {
            foreach ($data->rows as $row) {
                $items[] = ['keyword' => $row[0], 'sessions' => $row[1]];
            }
        }

        return $items;
    }

    /**
     * Get the top referrers for duration
     * @return \Spatie\Analytics\Collection
     */
    public function getReferrers()
    {
        $period = $this->analyticsDuration();

        $items = Analytics::fetchTopReferrers($period, 30);

        return $items;
    }

    /**
     * Get the active users currently viewing the website
     * @return int
     */
    public function getActiveVisitors()
    {
        // TODO
        // spatie analytics does not allow this anymore
        // need to implement this myself
        $period = $this->analyticsDuration();
        //$total = Analytics::performQuery($period, 'rt:activeUsers');

        $total = 'TODO';

        return json_response($total);
    }

    /**
     * Get the visitors and page views for duration
     * Format result for CartJS
     * @return string
     */
    public function getVisitorsAndPageViews()
    {
        $period = $this->analyticsDuration();
        $data = Analytics::fetchTotalVisitorsAndPageViews($period);

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
     * @return \Spatie\Analytics\Collection
     */
    public function getVisitedPages()
    {
        $period = $this->analyticsDuration();
        $items = Analytics::fetchMostVisitedPages($period);

        return $items;
    }

    /**
     * Get the top browsers for duration
     * Format results for pie chart
     * @return \Spatie\Analytics\Collection
     */
    public function getBrowsers()
    {
        $period = $this->analyticsDuration();
        $data = Analytics::fetchTopBrowsers($period)->toArray();

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
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions', ['dimensions' => 'ga:userGender']);

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
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions',
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
     * @return \Spatie\Analytics\Collection
     */
    public function getInterestsAffinity()
    {
        return $this->getInterests('ga:interestAffinityCategory');
    }

    /**
     * Get the the users interests - market
     * @return \Spatie\Analytics\Collection
     */
    public function getInterestsMarket()
    {
        return $this->getInterests('ga:interestInMarketCategory');
    }

    /**
     * Get the the users interests - affinity
     * @return \Spatie\Analytics\Collection
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
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions', [
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
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions', [
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
     * @return \Spatie\Analytics\Collection
     */
    public function getInterests($dimensions)
    {
        $period = $this->analyticsDuration();

        $data = Analytics::performQuery($period, 'ga:sessions', [
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

        $period = Period::create($start, $end);

        $data = Analytics::performQuery($period, $metrics);

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
     * Get the duration for the analytics
     * @return Period
     */
    private function analyticsDuration()
    {
        $start = input('start', date('Y-m-d', strtotime('-29 days')));
        $end = input('end', date('Y-m-d'));

        if (is_string($start)) {
            $start = \DateTime::createFromFormat('Y-m-d', $start);
        }

        if (is_string($end)) {
            $end = \DateTime::createFromFormat('Y-m-d', $end);
        }

        return Period::create($start, $end);
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
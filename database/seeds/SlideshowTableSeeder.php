<?php

namespace Bpocallaghan\Titan\Seeds;

use Illuminate\Database\Seeder;
use Bpocallaghan\Titan\Models\Banner;

class SlideshowTableSeeder extends Seeder
{
    public function run()
    {
        Banner::truncate();
        //$csvPath = database_path() . '/seeds/csv/' . 'banners.csv';
        //$items = csv_to_array($csvPath);

        $items = collect([
            [
                'name' => 'Banner 1',
                'image' => 'banner-1.jpg',
                'is_website' => true,
                'hide_name' => true,
            ],
            [
                'name' => 'Banner 2',
                'image' => 'banner-2.jpg',
                'is_website' => true,
                'hide_name' => true,
            ],
            [
                'name' => 'Banner 3',
                'image' => 'banner-3.jpg',
                'is_website' => true,
                'hide_name' => true,
            ]
        ]);

        $items->map(function ($item) {
            Banner::create([
                'name'        => $item['name'],
                'image'       => $item['image'],
                'active_from' => \Carbon\Carbon::now(),
                'is_website'  => $item['is_website'],
                'hide_name'   => $item['hide_name'],
            ]);
        });

        //foreach ($items as $key => $item) {
        //    Banner::create([
        //        'id'          => $item['id'],
        //        'name'        => $item['name'],
        //        'summary'     => $item['summary'],
        //        'action_name' => $item['action_name'],
        //        'action_url'  => $item['action_url'],
        //        'image'       => $item['image'],
        //        'active_from' => \Carbon\Carbon::now(),
        //        'is_website'  => $item['is_website'],
        //        'hide_name'   => $item['hide_name'],
        //    ]);
        //}
    }
}

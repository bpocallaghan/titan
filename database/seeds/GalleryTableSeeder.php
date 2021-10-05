<?php
namespace Bpocallaghan\Titan\Seeds;
use Bpocallaghan\Titan\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Bpocallaghan\Titan\Models\Article;

class GalleryTableSeeder extends Seeder
{
    public function run(Faker\Generator $faker)
    {
        Article::truncate();
        ArticleCategory::truncate();

        for ($i = 0; $i < 5; $i++) {
            $category = ArticleCategory::create([
                'name' => $faker->sentence(2)
            ]);
        }


        for ($i = 0; $i < 20; $i++) {
            $item = Article::create([
                'title'       => $faker->sentence(3),
                'content'     => "<p>{$faker->paragraph(3)}</p>",
                'active_from' => $faker->dateTimeBetween('-5 weeks', '-1 weeks')->format('Y-m-d'),
                //'active_to' => $faker->dateTimeBetween('+5 weeks')
                'category_id' => $faker->numberBetween(1, 5),
                'slug'        => 'asd'
            ]);
        }

    }
}
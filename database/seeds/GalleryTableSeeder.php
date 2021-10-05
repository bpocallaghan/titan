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

    }
}
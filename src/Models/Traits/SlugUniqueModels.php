<?php

namespace Titan\Models\Traits;

use App\Models\BodyStyle;
use App\Models\Category;
use App\Models\Color;
use App\Models\Condition;
use App\Models\Exterior;
use App\Models\FuelType;
use App\Models\Interior;
use App\Models\Lifestyle;
use App\Models\Make;
use App\Models\Modell;
use App\Models\SellerType;
use App\Models\Transmission;
use ReflectionClass;

trait SlugUniqueModels
{
    // list of models to look to make unique slug
    protected $models = [
        Category::class,
        Make::class,
        Modell::class,
        Condition::class,
        Transmission::class,
        Color::class,
        FuelType::class,
        Lifestyle::class,
        Interior::class,
        Exterior::class,
        BodyStyle::class,
        SellerType::class,
    ];

    /**
     * On create and update, set the slug
     *
     * @return void
     */
    protected static function bootSlugUniqueModels()
    {
        static::creating(function ($model) {
            $model->setSlugAttribute($model->title);
        });

        static::updating(function ($model) {
            $model->setSlugAttribute($model->title);
        });
    }

    /**
     * Set the slug attribute
     *
     * @param $slug
     */
    function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = $this->makeSlugUnique($slug);
    }

    /**
     * Make Slug Unique in same table
     * @param $slug
     * @return string
     */
    private function makeSlugUnique($slug)
    {
        $slug = str_slug($slug);

        // check updating
        $slugUpdate = $this->checkUpdatingSlug($slug);

        if ($slugUpdate !== false) {
            return $slugUpdate;
        }

        // get existing slugs
        $list = $this->getExistingSlugs($slug);

        if ($list->count() === 0) {
            return $slug;
        }

        // generate unique suffix
        return $this->generateSuffix($slug, $list);
    }

    /**
     * Get existing slugs matching slug
     *
     * @param $slug
     * @return \Illuminate\Support\Collection|static
     */
    private function getExistingSlugs($slug)
    {
        $list = collect();
        foreach ($this->models as $class) {

            // get entries matching slug
            $slugs = $this->eloqent($class)
                ->whereRaw("slug LIKE '$slug%'")
                ->withTrashed()// trashed, when entry gets activated
                ->get()
                ->pluck('slug');

            $list = $list->merge($slugs);
        }

        return $list;
    }

    /**
     * Suffix unique index to slug
     *
     * @param $slug
     * @param $list
     * @return string
     */
    private function generateSuffix($slug, $list)
    {
        // loop through list and get highest index number
        $index = $list->map(function ($s) use ($slug) {
            // str_replace instead of explode('-');
            return intval(str_replace($slug . '-', '', $s));
        })->sort()->last();

        return $slug . '-' . ($index + 1);
    }

    /**
     * Get the Eloquent Class
     * @param $class
     * @return object
     */
    private function eloqent($class)
    {
        return (new ReflectionClass($class))->newInstanceArgs();
    }

    /**
     * Check if we are updating
     * Find entries with same slug
     * Exlude current model's entry
     *
     * @param $slug
     * @return bool
     */
    private function checkUpdatingSlug($slug)
    {
        if ($this->id >= 1) {

            $found = false;
            foreach ($this->models as $class) {

                // find entries matching slug, exclude updating entry
                $builder = $this->eloqent($class)->where('slug', $slug);

                // same model - exlude check
                if ($class == self::class) {
                    $builder = $builder->where('id', '!=', $this->id);
                }

                // found same slug - break, need new slug
                if ($found = $builder->first()) {
                    break;
                }
            }

            // save to use current slug for update
            if ($found == false || $found == null) {
                return $slug;
            }
        }

        // new unique slug needed
        return false;
    }
}
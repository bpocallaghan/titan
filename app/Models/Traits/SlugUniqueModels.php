<?php

namespace Bpocallaghan\Titan\Models\Traits;

trait SlugUniqueModels
{
    /**
     * Move this function to parent model / helper / trait
     * Add the list of models - to make the slug unique
     *
     * List of Models
     * SlugUniqueModels Trait will look in all the below tables
     * To make sure the slug is unique across tables
     * @return array
     */
    /*public function uniqueModels()
    {
        return [
            //User::class,
        ];
    }*/

    /**
     * On create and update, set the slug
     *
     * @return void
     */
    protected static function bootSlugUniqueModels()
    {
        // on model's creating and updating event
        foreach (['creating', 'updating'] as $event) {
            static::$event(function ($model) use ($event) {
                // if custom build slug from column not set
                if (!property_exists($model, 'buildSlugFrom')) {
                    $model->setSlugAttribute($model->title);
                }
                else { // use specified column
                    $column = $model->buildSlugFrom;
                    $model->setSlugAttribute($model->{$column});
                }
            });
        }
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
        foreach ($this->uniqueModels() as $class) {

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
        return app($class);
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
            foreach ($this->uniqueModels() as $class) {

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
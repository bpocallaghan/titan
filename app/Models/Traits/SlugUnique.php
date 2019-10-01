<?php

namespace Bpocallaghan\Titan\Models\Traits;

trait SlugUnique
{
    /**
     * On create and update, set the slug
     *
     * @return void
     */
    protected static function bootSlugUnique()
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
        $slug = \Illuminate\Support\Str::slug($slug);

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
        $slugs = self::whereRaw("slug LIKE '$slug%'")
            ->withTrashed()// trashed, when entry gets activated
            ->get()
            ->pluck('slug');

        return $slugs;
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
            // find entries matching slug, exclude updating entry
            $exist = self::where('slug', $slug)->where('id', '!=', $this->id)->first();

            // no entries, save to use current slug
            if (!$exist) {
                return $slug;
            }
        }

        // new unique slug needed
        return false;
    }
}

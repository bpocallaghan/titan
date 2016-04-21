<?php

namespace Titan\Models\Traits;

trait Sluggable
{
    /**
     * On create and update, set the slug
     *
     * @return void
     */
    protected static function bootSluggable()
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
     * @param $slug
     */
    function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = str_slug($slug);
    }

    /**
     * Query scope for finding a model by its slug.
     *
     * @param $scope
     * @param $slug
     * @return mixed
     */
    public function scopeWhereSlug($scope, $slug)
    {
        return $scope->where('slug', $slug);
    }

    /**
     * Find a model by slug.
     * @param       $slug
     * @param array $columns
     * @return mixed
     */
    public static function findBySlug($slug, array $columns = ['*'])
    {
        return self::where('slug', $slug)->first($columns);
    }

    /**
     * Find a model by slug or fail.
     *
     * @param       $slug
     * @param array $columns
     * @return mixed
     */
    public static function findBySlugOrFail($slug, array $columns = ['*'])
    {
        return self::where('slug', $slug)->firstOrFail($columns);
    }
}
<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
trait Favourable
{
    /**
     * Boot the trait.
     */
    protected static function bootFavourable()
    {
        static::deleting(function ($model) {
            $model->favourites->each->delete();
        });
    }
    /**
     * A reply can be favourited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }
    /**
     * favourite the current reply.
     *
     * @return Model
     */
    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (! $this->favourites()->where($attributes)->exists()) {
            if ($this->user_id != auth()->id()) {
                $this->user()->increment('reputation',3);
            }
            return $this->favourites()->create($attributes);
        }
    }
    /**
     * Unfavourite the current reply.
     */
    public function unfavourite()
    {
        $attributes = ['user_id' => auth()->id()];
        $this->favourites()->where($attributes)->get()->each->delete();
        if ($this->user_id != auth()->id()) {
            $this->user()->decrement('reputation',3);
        }
    }
    /**
     * Determine if the current reply has been favourited.
     *
     * @return boolean
     */
    public function isFavourited()
    {
        return ! ! $this->favourites->where('user_id', auth()->id())->count();
    }
    /**
     * Fetch the favourited status as a property.
     *
     * @return bool
     */
    public function getIsFavouritedAttribute()
    {
        return $this->isfavourited();
    }
    /**
     * Get the number of favourites for the reply.
     *
     * @return integer
     */
    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }
}

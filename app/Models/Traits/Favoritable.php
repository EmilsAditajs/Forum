<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Favorites;

trait Favoritable
{
    /**
     * @return MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorites::class, 'favorited');
    }

    /**
     * Favorite a model
     *
     * @return void
     */
    public function favorite(): void
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    /**
     * Check if model has been favorited
     *
     * @return boolean
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * @return integer
     */
    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }
}

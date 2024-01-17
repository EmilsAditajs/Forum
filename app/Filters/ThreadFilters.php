<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ThreadFilters extends Filters
{
    /**
     * Filters that can be applied
     *
     * @var array
     */
    protected $filters = [
        'by',
        'popular'
    ];

    /**
     * Filter threads by username
     *
     * @param string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter threads by popularity
     *
     * @return Builder
     */
    protected function popular(): Builder
    {
        return $this->builder->orderBy('replies_count', 'desc');
    }
}

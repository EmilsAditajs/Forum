<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\Favoritable;
use App\Models\Traits\RecordsActivities;


class Reply extends Model
{
    use HasFactory, Favoritable, RecordsActivities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'body',
        'user_id',
    ];

    /**
     * Related models to be eager loaded
     *
     * @var array
     */
    protected $with = [
        'owner',
        'favorites'
    ];

    /**
     * Get the user model that created created the reply
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get parent thread of the reply
     *
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}

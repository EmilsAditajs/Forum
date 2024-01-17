<?php

namespace App\Models;

use App\Filters\ThreadFilters;
use App\Models\Traits\RecordsActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reply;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Thread extends Model
{
    use HasFactory, RecordsActivities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'channel_id',
        'title',
        'body',
    ];

    /**
     * Related models to be eager loaded
     *
     * @var array
     */
    protected $with = [
        'creator',
        'channel'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function (Builder $builder) {
            $builder->withCount('replies');
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * Get path
     *
     * @return string
     */
    public function path(): string
    {
        return '/threads/' . $this->channel->slug . '/' . $this->id;
    }

    /**
     * Get all replies
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Get the user who created the thread
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Create a new reply to a thread
     *
     * @param [type] $reply
     * @return void
     */
    public function addReply($reply): void
    {
        $this->replies()->create($reply);
    }

    /**
     * Get the channel that the thread belongs to
     *
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Undocumented function
     *
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, ThreadFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activities extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'user_id',
        'subject_id',
        'subject_type',
        'created_at'
    ];

    /**
     * @return MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Return recorded activities of the user
     *
     * @param User $user
     * @param integer $take
     * @return void
     */
    public static function feed($user, int $take = 10)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}

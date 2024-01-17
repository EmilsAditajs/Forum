<?php

namespace App\Models\Traits;

use App\Models\Activities;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordsActivities
{
    /**
     * @return void
     */
    protected static function bootRecordsActivities()
    {
        if (auth()->guest())
            return;

        foreach (static::getRecordEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Return list of activities to be recorded
     *
     * @return array
     */
    private static function getRecordEvents()
    {
        return [
            'created',
        ];
    }


    /**
     * @param string $event
     * @return void
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth('')->id(),
        ]);
    }

    /**
     * @return MorphMany
     */
    private function activity()
    {
        return $this->morphMany(Activities::class, 'subject');
    }

    /**
     * @param string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return $event . '_' . $type;
    }
}

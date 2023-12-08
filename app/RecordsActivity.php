<?php

namespace App;

use App\Models\Activity;

trait RecordsActivity
{

    public $oldAttributes = [];

    public static function bootRecordsActivity(){
        static::updating(function($model){
            $model->oldAttributes = $model->getOriginal();
        });

        foreach(self::recordableEvents() as $event)
        {
            static::$event(function($model) use ($event){
                $model->recordActivity($model->activityDescription($event));
            });
        }
    }

    protected static function recordableEvents()
    {
        if(isset(static::$recordableEvents)){
            $recordableEvents = static::$recordableEvents;
        }
        else{
            $recordableEvents = ['created','updated'];
        }

        return $recordableEvents;
    }

    protected function activityDescription($description)
    {
        if(class_basename($this) !== 'Project'){
            return "{$description}_".strtolower(class_basename($this));
        }
        return $description;
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => $this->activityOwner()->id,
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === "Project" ? $this->id : $this->project->id
        ]);
        /*Activity::create([
            'project_id' => $this->id,
            'description' => $type
        ]);*/
    }

    public function activityOwner()
    {
        /*if(class_basename($this) === "Project"){
            return $this->owner;
        }
        return $this->project->owner;*/

        $owner = ($this->project ?? $this)->owner;
        if($owner->id == auth()->id()){
            return $owner;
        }
        else{
            return (($this->project ?? $this)->members->contains(auth()->user()) ? auth()->user() : '');
        }
    }

    public function activity()
    {
        if(class_basename($this) === "Project"){
            return $this->hasMany(Activity::class)->latest();
        }
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    protected function activityChanges()
    {
        if($this->wasChanged()){
            return [
                'before' => \Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), ['updated_at']),
                'after' => \Arr::except($this->getChanges(), ['updated_at'])
                // 'after' => array_diff($this->getAttributes(), $this->old)
            ];
        }
    }
}
?>
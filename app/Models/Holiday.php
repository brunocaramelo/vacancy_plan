<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Holiday extends Model
{
    use  HasUuid, SoftDeletes;

    protected $table = 'holiday_plans';
    protected $fillable = [
        'title',
        'date',
        'description',
        'location',
    ];

    protected $dates = [
        'date',
    ];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class)
                    ->using(HolidayParticipant::class)
                    ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
    use  HasUuid, SoftDeletes;

    protected $table = 'participants';
    protected $fillable = [
        'name',
        'last_name',
        'email',
    ];

    public function holidays(): BelongsToMany
    {
        return $this->belongsToMany(Holiday::class)
                    ->using(HolidayParticipant::class)
                    ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasUuid;

use App\Models\StrategyWms;
class HolidayParticipant extends Pivot
{
    use  HasUuid, SoftDeletes;
    protected $table = 'holiday_participant';

}

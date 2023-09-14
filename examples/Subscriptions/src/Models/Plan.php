<?php

namespace Thunk\Verbs\Examples\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\Examples\Subscriptions\Events\PlanReportGenerated;
use Thunk\Verbs\FromState;

class Plan extends Model
{
    use HasFactory;
    use FromState;

    public function generateReport()
    {
        return PlanReportGenerated::fire($this->id)
            ->state
            ->summary;
    }
}

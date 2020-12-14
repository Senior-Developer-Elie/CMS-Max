<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReportLossItem extends Model
{
    protected $fillable = [
        "name",
        "value",
        "financial_report_id",
    ];
}

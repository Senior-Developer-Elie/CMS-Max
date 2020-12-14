<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReportProfitItem extends Model
{
    protected $fillable = [
        "name",
        "value",
        "financial_report_id",
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $fillable = [
        'date'
    ];

    public function profitItems()
    {
        return $this->hasMany(FinancialReportProfitItem::class);
    }

    public function expenseItems()
    {
        return $this->hasMany(FinancialReportLossItem::class);
    }
}

<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class WebsiteBudgetExport implements FromCollection
{
    use Exportable;

    protected $websites;

    public function __construct(Collection $websites)
    {
        $this->websites = $websites;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rows = [];
        $rows[] = [
            'Name',
            'Url',
            'Budget',
        ];

        foreach ($this->websites as $website) {
            $rows[] = [
                $website->name,
                $website->website,
                "" . floatval($website->total_budget),
            ];
        }

        return collect($rows);
    }
}

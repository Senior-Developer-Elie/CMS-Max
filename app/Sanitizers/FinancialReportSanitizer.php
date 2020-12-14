<?php

namespace App\Sanitizers;

class FinancialReportSanitizer extends BaseSanitizer
{
    /**
     * An array of sanitizer methods to be executed.
     *
     * @var array
     */
    protected $sanitizers = [
        'Date',
    ];

    protected function sanitizeDate()
    {
        if (! empty($this->get('date'))) {
            $startDate = \Carbon\Carbon::createFromFormat('m/Y', $this->get('date'));
            $this->set('date', $startDate->format('Y-m') . '-01');
        }
    }
}

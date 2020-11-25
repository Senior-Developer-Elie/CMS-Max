<?php

namespace App\Sanitizers;

class WebsiteSanitizer extends BaseSanitizer
{
    /**
     * An array of sanitizer methods to be executed.
     *
     * @var array
     */
    protected $sanitizers = [
        'Website',
        'SyncFromClient',
        'Chargebee',
        'IsBlogClient',
        'CompletedAt',
        'ControlScanRenewalDate',
        'StartDate'
    ];

    protected function sanitizeWebsite()
    {
        $this->set('website', \getCleanUrl($this->get('website')));
    }

    protected function sanitizeSyncFromClient()
    {
        $this->checkbox('sync_from_client');
    }

    protected function sanitizeChargebee()
    {
        $this->checkbox('chargebee');
    }

    protected function sanitizeIsBlogClient()
    {
        $this->checkbox('is_blog_client');
    }

    protected function sanitizeCompletedAt()
    {
        $this->dateify('completed_at');
    }

    protected function sanitizeControlScanRenewalDate()
    {
        $this->dateify('control_scan_renewal_date');
    }

    protected function sanitizeStartDate()
    {
        if (! empty($this->get('start_date'))) {
            $startDate = \Carbon\Carbon::createFromFormat('m/Y', $this->get('start_date'));
            $this->set('start_date', $startDate->format('Y-m') . '-01');
        }
    }
}

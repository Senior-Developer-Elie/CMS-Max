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
        'StartDate',
        'WebsiteProducts',
        'AssigneeId',
        'UsesOurCreditCard',
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
        if (empty($this->get('completed_at'))) {
            $this->nullify('completed_at');
        } else {
            $this->dateify('completed_at');
        }
    }

    protected function sanitizeControlScanRenewalDate()
    {
        if (empty($this->get('control_scan_renewal_date'))) {
            $this->nullify('control_scan_renewal_date');
        } else {
            $this->dateify('control_scan_renewal_date');
        }
    }

    protected function sanitizeStartDate()
    {
        if (! empty($this->get('start_date'))) {
            $startDate = \Carbon\Carbon::createFromFormat('m/Y', $this->get('start_date'));
            $this->set('start_date', $startDate->format('Y-m') . '-01');
        } else {
            $this->nullify('start_date');
        }
    }

    protected function sanitizeWebsiteProducts()
    {
        $websiteProducts = [];
        foreach ($this->get('website_products') as $crmProductKey => $websiteProduct) {
            $websiteProducts[$crmProductKey] = [
                'frequency' => intval($websiteProduct['frequency']),
                'value' => floatval($websiteProduct['value']),
            ];
        }

        $this->set('website_products', $websiteProducts);
    }

    protected function sanitizeAssigneeId()
    {
        $this->nullify('assignee_id');
    }

    protected function sanitizeUsesOurCreditCard()
    {
        $this->checkbox('uses_our_credit_card');
    }
}

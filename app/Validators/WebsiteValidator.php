<?php

namespace App\Validators;

class WebsiteValidator extends BaseValidator
{
    /**
     * Validation rules for this validator
     *
     * @var array
     */
    public $rules = [

        'create' => [
            'client_id' => 'required|exists:clients,id',
            'name' => 'required',
            'website' => 'required',
            'target_area' => 'required',
            'blog_industry_id' => 'nullable|exists:blog_industries,id',
            'type' => 'required',
            'completed_at' => 'date',
            'affiliate' => 'nullable|exists:affiliates,id',
            'dns' => 'nullable|exists:dns,id',
            'email' => 'nullable',
            'on_portfolio' => 'nullable',
            'sitemap' => 'nullable',
            'left_review' => 'nullable',
            'payment_gateway' => 'nullable|array',
            'payment_gateway.*' => 'exists:payment_gateways,id',
            'chargebee' => 'required|in:0,1',
            'sync_from_client' => 'required|in:0,1',
            'is_blog_client' => 'required|in:0,1',
            'assignee_id' => 'required_if:is_blog_client,1|exists:users,id',
            'frequency' => 'required_if:is_blog_client,1',
            'start_date' => 'required_if:is_blog_client,1|date',
        ],

        'update' => [
            'client_id' => 'required|exists:clients,id',
            'name' => 'required',
            'website' => 'required',
            'target_area' => 'required',
            'blog_industry_id' => 'nullable|exists:blog_industries,id',
            'type' => 'required',
            'completed_at' => 'date',
            'affiliate' => 'nullable|exists:affiliates,id',
            'dns' => 'nullable|exists:dns,id',
            'email' => 'nullable',
            'on_portfolio' => 'nullable',
            'sitemap' => 'nullable',
            'left_review' => 'nullable',
            'payment_gateway' => 'nullable|array',
            'payment_gateway.*' => 'exists:payment_gateways,id',
            'chargebee' => 'required|in:0,1',
            'sync_from_client' => 'required|in:0,1',
            'is_blog_client' => 'required|in:0,1',
            'assignee_id' => 'required_if:is_blog_client,1|exists:users,id',
            'frequency' => 'required_if:is_blog_client,1',
            'start_date' => 'required_if:is_blog_client,1|date',
        ],
    ];
}

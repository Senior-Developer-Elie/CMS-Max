<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Http\Helpers\WebsiteHelper;

class Website extends Model
{
    const SOCIAL_MEDIA_SERVICE_ORGANIC = 'organic';
    const SOCIAL_MEDIA_SERVICE_PAID = 'paid';
    const SOCIAL_MEDIA_SERVICE_PAID_AND_ORGANIC = 'paid-and-organic';

    protected $fillable = [
        'name',
        'website',
        'frequency',
        'target_area',
        'start_date',
        'blog_industry_id',
        'assignee_id',
        'notes',
        'contacts',
        'is_blog_client',
        'client_id',
        'type',
        'affiliate',
        'dns',
        'payment_gateway',
        'email',
        'merchant_center',
        'flow_chart',

        "completed_at",

        "archived",
        "archived_at",
        "payroll_archived",
        "payroll_archived_at",

        "mailgun_sender",

        "mid",
        "control_scan_user",
        "control_scan_pass",
        "control_scan_renewal_date",

        "credit_card_archived",
        "credit_card_notes",

        "drive",

        'sync_from_client',
        'sitemap',
        'left_review',
        'on_portfolio',
        'stage_id',
        'priority',
        'post_live',
        'marketing_notes',
        'post_live_check_archived',

        'billing_type',
        'billing_amount',

        // Social Media
        'social_calendar',
        'manual_social_plan',
        'social_ad_spend',
        'social_management_fee',
        'uses_our_credit_card',
        'social_media_stage_id',
        'social_media_stage_order',
        "social_media_archived",
        "social_media_notes",
        "social_media_assignee",
        "social_media_reviewer",
        "social_media_service",

        "linkedin_url",
        "youtube_url",
        "twitter_url",
        "facebook_url",
        "instagram_url",
        "pinterest_url",
    ];

    protected $casts = [
        'payment_gateway'   => 'array',
        'post_live'         => 'array'
    ];

    protected static $socialMediaServices = [
        self::SOCIAL_MEDIA_SERVICE_ORGANIC => 'Organic',
        self::SOCIAL_MEDIA_SERVICE_PAID => 'Paid',
        self::SOCIAL_MEDIA_SERVICE_PAID_AND_ORGANIC => 'Organic & Paid',
    ];

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    public static function socialMediaServices()
    {
        return self::$socialMediaServices;
    }

    public function scopeNotArchived($query)
    {
        $query->where('archived', 0);

        return $query;
    }

    //Event Handler
    public static function boot() {
        parent::boot();

        //Soft delete
        static::deleting(function($website) {
            $website->jobs()->delete();
            $website->blogs()->delete();
        });
    }

    /**
     * Get the blogs for the blog client.
     */
    public function blogs()
    {
        return $this->hasMany('App\Blog', 'website_id');
    }

    /**
     * Get the jobs for the blog client.
     */
    public function jobs()
    {
        return $this->hasMany('App\InnerBlog', 'website_id');
    }

    /**
     * Get the Assinee
     */
    public function assignee()
    {
        return $this->belongsTo('App\User', 'assignee_id')->get()->first();
    }

    /**
     * Get the Assinee
     */
    public function industry()
    {
        return $this->belongsTo('App\BlogIndustry', 'blog_industry_id')->get()->first();
    }

    /**
     * Get Client
     */
    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id')->get()->first();
    }

    public function socialMediaCheckLists()
    {
        return $this->hasMany(WebsiteSocialMediaCheckList::class);
    }

    public function getProductValues($crmProductKeys){

        $fees = [];
        $sum = 0;
        foreach( $crmProductKeys as $crmProductKey ){
            $fees[$crmProductKey] = $this->getProductValue($crmProductKey);
            $sum += $fees[$crmProductKey] > 0 ? $fees[$crmProductKey] : 0;
        }
        $fees['total'] = $sum > 0 ? $sum : 0;
        
        return $fees;
    }

    public function paymentGatewayString()
    {
        if( is_null($this->payment_gateway) )
            return "";
        $string = "";
        $allPaymentGateways = WebsiteHelper::getAllPaymentGateways();
        foreach( $this->payment_gateway as $gateway ) {
            $string .= $allPaymentGateways[$gateway] . ", ";
        }
        if( strlen($string) >= 2 )
            $string = substr($string, 0, -2);

        return $string;
    }

    /**
     * Get blogs in future
     * @param int $months
     */
    public function futureBlogs()
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->startOfYear()->addMonths(12);

        $blogs = $this->blogs()
                    ->where('blogs.desired_date', '>=', (string)$startDate)
                    ->where('blogs.desired_date', '<', (string)$endDate)
                    ->get();
        return $blogs;
    }

    /**
     * Get Available Months
     * @param int $months
     */
    public function availableMonths()
    {
        $duration = $this->getDuration();
        $availableMonths = [];

        $createdDate = (new Carbon($this->start_date))->startOfMonth();
        for( $i = 0; $i < 12; $i++ ) {
            $date = Carbon::now()->startOfYear()->addMonths($i);

            if( $date < $createdDate )
                continue;
            $months_diff = $date->diffInMonths($createdDate);

            if( $months_diff % $duration == 0 )
                $availableMonths[] = $date;

        }
        return $availableMonths;
    }

    /**
     * Get Duration from frequency
     */
    public function getDuration()
    {
        $duration = 1;
        if( $this->frequency == 'monthly' )
            $duration = 1;
        else if( $this->frequency == 'bi-monthly' )
            $duration = 2;
        else if( $this->frequency == 'quarterly' )
            $duration = 3;
        else if( $this->frequency == '6 months' )
            $duration = 6;
        return $duration;
    }

    /**
     * Check if website is completed for post check list
     */
    public function completedPostWebsite(){

        if( !is_array($this->post_live) )
            return false;

        $allPostLiveOptions = WebsiteHelper::getAllPostLiveOptions();

        foreach ( $allPostLiveOptions as $postLiveOption => $optionText) {
            if ((!isset($this->post_live[$postLiveOption]) || $this->post_live[$postLiveOption] == 'no' ))
                return false;
        }
        return true;
    }

    /**
     * public function update post_live option
     */
    public function updatePostLiveOption($option, $value)
    {
        $postLive = $this->post_live;
        if( !is_array($postLive) )
            $postLive = [];

        $postLive[$option] = $value;
        $this->post_live = $postLive;
        $this->save();
    }

    public function apiProducts()
    {
        return $this->hasMany(\App\WebsiteApiProduct::class);
    }

    public function saveProduct(string $crmProductKey, $data)
    {
        if (! in_array($crmProductKey, AngelInvoice::crmProductKeys())) {
            return null;
        }

        return $this->apiProducts()->updateOrCreate([
            'key' => $crmProductKey
        ], $data);
    }

    public function getWebsiteApiProduct(string $crmProductKey)
    {
        if (! in_array($crmProductKey, AngelInvoice::crmProductKeys())) {
            return null;
        }

        return $this->apiProducts()->firstOrCreate([
            'key' => $crmProductKey
        ], [
            'value' => 0,
            'frequency' => 1
        ]);
    }

    public function getProductValue(string $crmProductKey)
    {
        $websiteApiProduct = $this->getWebsiteApiProduct($crmProductKey);

        if ($websiteApiProduct->value < 0) {
            return $websiteApiProduct->value;
        }

        if ($this->sync_from_client) {
            return $this->client()->getProductValue($crmProductKey);
        }

        return $websiteApiProduct->value / $websiteApiProduct->frequency;
    }

    public static function getDefaultProducts()
    {
        $websiteProducts = [];
        $crmProductKeys = AngelInvoice::crmProductKeys();

        foreach ($crmProductKeys as $crmProductKey) {
            $websiteProducts[$crmProductKey] = [
                'value' => 0,
                'frequency' => 1,
            ];
        }

        return $websiteProducts;
    }

    public function getProductsWithDefault()
    {
        $websiteProducts = [];
        $crmProductKeys = AngelInvoice::crmProductKeys();

        foreach ($crmProductKeys as $crmProductKey) {
            $websiteApiProduct = $this->getWebsiteApiProduct($crmProductKey);

            $websiteProducts[$crmProductKey] = [
                'value' => $websiteApiProduct->value,
                'frequency' => $websiteApiProduct->frequency,
            ];
        }

        return $websiteProducts;
    }

    public function getSocialPlanAttribute()
    {
        foreach (AngelInvoice::SOCIAL_PLANS_CRM_PRODUCT_KEYS as $crmProductKey) {
            if ($this->getProductValue($crmProductKey) > 0) {
                return $crmProductKey;
            }
        }

        return null;
    }

    public function getActiveSocialMediaCheckListTargets()
    {
        $targets = [SocialMediaCheckList::CHECKLIST_TYPE_CORE];

        foreach (SocialMediaCheckList::checkListTypes() as $key => $name) {
            if ($key == SocialMediaCheckList::CHECKLIST_TYPE_CORE) {
                continue;
            }

            $attributeName = $key . "_url";
            if (! empty($this->$attributeName)) {
                $targets[] = $key;
            }
        }

        return $targets;
    }

    public function getActiveSocialMediaCheckListCount()
    {
        $activeTargets = $this->getActiveSocialMediaCheckListTargets();

        return SocialMediaCheckList::whereIn('target', $activeTargets)
            ->count();
    }
}

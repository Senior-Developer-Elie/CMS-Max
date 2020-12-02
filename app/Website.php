<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Http\Helpers\WebsiteHelper;

class Website extends Model
{

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

        "data_studio_link",

        "social_media_archived",
        "social_media_notes",

        "credit_card_archived",
        "credit_card_notes",

        "drive",

        'sync_from_client',
        'sitemap',
        'left_review',
        'on_portfolio',
        'shipping_method',
        'stage_id',
        'priority',
        'post_live',
        'marketing_notes',
        'post_live_check_archived',
    ];

    protected $casts = [
        'payment_gateway'   => 'array',
        'post_live'         => 'array'
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
    public function futureBlogs($months)
    {
        $startDate = (new Carbon('first day of this month'))->startOfDay();
        $endDate = (new Carbon('first day of this month'))->startOfDay()->addMonths($months);

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
    public function availableMonths($months)
    {
        $duration = $this->getDuration();
        $availableMonths = [];

        $createdDate = (new Carbon($this->start_date))->startOfMonth();
        for( $i = 0; $i < $months; $i++ ) {
            $date = (new Carbon('first day of this month'))->startOfDay()->addMonths($i);

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

        foreach( $allPostLiveOptions as $postLiveOption => $optionText) {
            if( ( !isset($this->post_live[$postLiveOption]) || $this->post_live[$postLiveOption] == 'no' )  && $postLiveOption != 'hide-on-marketing' )
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

    /**
     * public function is hide on google
     */
    public function isHideOnMarketing()
    {
        return isset($this->post_live['hide-on-marketing'])
            && $this->post_live['hide-on-marketing'] == 'yes';
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
        
        $websiteApiProduct->value / $websiteApiProduct->frequency;
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
}

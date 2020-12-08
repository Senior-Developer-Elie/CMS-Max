<?php

namespace App\Console\Commands;

use App\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateWebsiteVariables extends Command
{
    protected $dnsMaps = [];
    protected $affiliateMaps = [];
    protected $paymentGatewayMaps = [];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:website-variables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->prepareMaps();

        $oldWebsites = DB::connection('mysql_old_crm')->select("SELECT * FROM websites");

        foreach ($oldWebsites as $oldWebsite) {

            $this->line($oldWebsite->website);
            if (! $website = Website::where('website', $oldWebsite->website)->first()) {
                $this->warn("Website does not exist");
                continue;
            }

            $this->migrateVariables($website, $oldWebsite);
            $this->info("Done");
        }
    }

    protected function prepareMaps()
    {
        $this->affiliateMaps = [
            2 => 1,
            10 => 2,
            3 => 14,
            1 => 5,
            11 => 15,
            4 => 8,
            5 => 11,
            6 => 10,
            7 => 7,
            8 => 13,
            9 => 12,
        ];

        $this->dnsMaps = [
            2 => 1,
            3 => 2,
            6 => 3,
            5 => 4,
            4 => 5,
            7 => 6,
            8 => 7,
            16 => 8,
            13 => 15,
            9 => 9,
            10 => 10,
            11 => 11,
            14 => 12,
            15 => 13,
            12 => 14,
        ];

        $this->paymentGatewayMaps = [
            1 => 3,
            2 => 4,
            3 => 5,
            8 => 1,
            9 => 2,
            4 => 7,
            10 => 8,
            5 => 9,
            6 => 10,
            7 => 11,
        ];
    }

    protected function migrateVariables(&$website, $oldWebsite)
    {
        $website->frequency = $oldWebsite->frequency;
        $website->save();
        return;
        $this->fillSimpleEntity($website, $oldWebsite, 'target_area');
        $this->fillSimpleEntity($website, $oldWebsite, 'start_date');
        $this->fillSimpleEntity($website, $oldWebsite, 'notes');
        $this->fillSimpleEntity($website, $oldWebsite, 'contacts');
        $this->fillSimpleEntity($website, $oldWebsite, 'is_blog_client', false);
        $this->fillSimpleEntity($website, $oldWebsite, 'type');
        $this->fillSimpleEntity($website, $oldWebsite, 'email');
        $this->fillSimpleEntity($website, $oldWebsite, 'completed_at');
        $this->fillSimpleEntity($website, $oldWebsite, 'payroll_archived', false);
        $this->fillSimpleEntity($website, $oldWebsite, 'payroll_archived_at');
        $this->fillSimpleEntity($website, $oldWebsite, 'mailgun_sender');
        $this->fillSimpleEntity($website, $oldWebsite, 'mid');
        $this->fillSimpleEntity($website, $oldWebsite, 'control_scan_user');
        $this->fillSimpleEntity($website, $oldWebsite, 'control_scan_pass');
        $this->fillSimpleEntity($website, $oldWebsite, 'control_scan_renewal_date');
        $this->fillSimpleEntity($website, $oldWebsite, 'data_studio_link');
        $this->fillSimpleEntity($website, $oldWebsite, 'social_media_archived', false);
        $this->fillSimpleEntity($website, $oldWebsite, 'social_media_notes');
        $this->fillSimpleEntity($website, $oldWebsite, 'credit_card_archived', false);
        $this->fillSimpleEntity($website, $oldWebsite, 'credit_card_notes');
        $this->fillSimpleEntity($website, $oldWebsite, 'sitemap');
        $this->fillSimpleEntity($website, $oldWebsite, 'left_review');
        $this->fillSimpleEntity($website, $oldWebsite, 'on_portfolio');
        $this->fillSimpleEntity($website, $oldWebsite, 'stage_id');
        $this->fillSimpleEntity($website, $oldWebsite, 'priority');
        $this->fillSimpleEntity($website, $oldWebsite, 'post_live');
        $this->fillSimpleEntity($website, $oldWebsite, 'marketing_notes');
        $this->fillSimpleEntity($website, $oldWebsite, 'post_live_check_archived', false);

        // DNS
        if (empty($website->dns) && ! empty($oldWebsite->dns) && isset($this->dnsMaps[intval($oldWebsite->dns)])) {
            $website->dns = $this->dnsMaps[intval($oldWebsite->dns)];
            $website->save();
        }

        // Affiliate
        if (empty($website->affiliate) && ! empty($oldWebsite->affiliate) && isset($this->affiliateMaps[intval($oldWebsite->affiliate)])) {
            $website->affiliate = $this->affiliateMaps[intval($oldWebsite->affiliate)];
            $website->save();
        }

        // Payment Gateways
        if (empty($website->payment_gateway) && ! empty($oldWebsite->payment_gateway)) {
            if (! empty($oldPaymentGateways = json_decode($oldWebsite->payment_gateway, TRUE))) {
                $paymentGateways = [];
                foreach ($oldPaymentGateways as $oldPaymentGateway) {
                    if (isset($this->paymentGatewayMaps[intval($oldPaymentGateway)])) {
                        $paymentGateways[] = $this->paymentGatewayMaps[intval($oldPaymentGateway)];
                    }
                }

                if (! empty($paymentGateways)) {
                    $website->payment_gateway = $paymentGateways;
                    $website->save();
                }
            }
        }
    }

    protected function fillSimpleEntity(&$website, $oldWebsite, $column, $checkIfEmpty = true)
    {
        if ($checkIfEmpty) {
            if (empty($website->$column) && ! empty($oldWebsite->$column)) {
                $website->$column = $oldWebsite->$column;
                $website->save();
            }
        } else {
            $website->$column = $oldWebsite->$column;
            $website->save();
        }
    }
}

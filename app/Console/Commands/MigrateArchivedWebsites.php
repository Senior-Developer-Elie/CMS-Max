<?php

namespace App\Console\Commands;

use App\BlogIndustry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Website;
use App\Client;

class MigrateArchivedWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:archived-websites';

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
        $archivedWebsites = DB::connection('mysql_old_crm')->select("SELECT websites.*, clients.name as client_name, blog_industries.name as industry_name FROM websites LEFT JOIN clients ON clients.id = websites.client_id LEFT JOIN blog_industries ON blog_industries.id = websites.blog_industry_id WHERE websites.archived = 1");
        
        foreach ($archivedWebsites as $archivedWebsite) {
            $this->line("Migrating" . $archivedWebsite->website . "...");

            if (Website::where('website', $archivedWebsite->website)->count() > 0) {
                $this->warn('Website already exist!');
                continue;
            }

            if (! $client = Client::where('name', $archivedWebsite->client_name)->first()) { // If client not exist then create
                $this->warn('Client not exist #' . $archivedWebsite->client_name . " - Creating...");
                $client = Client::create([
                    'name' => $archivedWebsite->client_name,
                    'archived' => 1
                ]);
            }

            $blogIndustry = BlogIndustry::where('name', $archivedWebsite->industry_name)->first();

            $client->websites()->create([
                'name' => $archivedWebsite->name,
                'website' => $archivedWebsite->website,
                'frequency' => $archivedWebsite->frequency,
                'target_area' => $archivedWebsite->target_area,
                'notes' => $archivedWebsite->notes,
                'is_blog_client' => $archivedWebsite->is_blog_client,
                'type' => $archivedWebsite->type,
                'email' => $archivedWebsite->email,
                'sync_from_client' => $archivedWebsite->sync_from_client,
                'sitemap' => $archivedWebsite->sitemap,
                'left_review' => $archivedWebsite->left_review,
                'on_portfolio' => $archivedWebsite->on_portfolio,
                'marketing_notes' => $archivedWebsite->marketing_notes,
                'completed_at' => $archivedWebsite->completed_at,
                'archived' => $archivedWebsite->archived,
                'archived_at' => $archivedWebsite->archived_at,
                'payroll_archived' => $archivedWebsite->payroll_archived,
                'payroll_archived_at' => $archivedWebsite->payroll_archived_at,
                'control_scan_user' => $archivedWebsite->control_scan_user,
                'control_scan_pass' => $archivedWebsite->control_scan_pass,
                'control_scan_renewal_date' => $archivedWebsite->control_scan_renewal_date,
                'social_media_archived' => $archivedWebsite->social_media_archived,
                'social_media_notes' => $archivedWebsite->social_media_notes,
                'credit_card_archived' => $archivedWebsite->credit_card_archived,
                'credit_card_notes' => $archivedWebsite->credit_card_notes,
                'drive' => $archivedWebsite->drive,
                'post_live_check_archived' => $archivedWebsite->post_live_check_archived,
                'data_studio_link' => $archivedWebsite->data_studio_link,
                'blog_industry_id' => $blogIndustry ? $blogIndustry->id : NULL
            ]);

            $this->info("Done");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Website;

class MigrateWebsitesPostLiveCheckList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:website-post-live-checklist';

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
        $oldWebsites = DB::connection('mysql_old_crm')->select("SELECT * FROM websites");

        foreach ($oldWebsites as $oldWebsite) {

            $this->info($oldWebsite->website);
            $this->line($oldWebsite->website);
            if (! $website = Website::where('website', $oldWebsite->website)->first()) {
                $this->warn("Website does not exist");
                continue;
            }

            if (! empty($oldWebsite->post_live)) {
                $website->post_live = json_decode($oldWebsite->post_live, TRUE);    
                $website->save();
            }           
            
            $this->info("Done");
        }
    }
}

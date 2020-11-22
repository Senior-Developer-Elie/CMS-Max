<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncSuppressions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suppression:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync All suppressions for mailgun api keys';

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
     * @return mixed
     */
    public function handle()
    {
        $mailgunApiKeys = \App\MailgunApiKey::all();

        foreach( $mailgunApiKeys as $mailgunApiKey ){
            echo $mailgunApiKey->domain . " is syncing suppressions.\r\n";
            $mailgunHelper = new \App\Http\Helpers\MailgunHelper($mailgunApiKey->key);
            $mailgunHelper->refreshSuppressions($mailgunApiKey, 'bounce');
            $mailgunHelper->refreshSuppressions($mailgunApiKey, 'compliant');
        }
    }
}

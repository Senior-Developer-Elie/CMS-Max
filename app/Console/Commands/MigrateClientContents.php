<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Client;

class MigrateClientContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:client-contents';

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
        $oldClients = DB::connection('mysql_old_crm')->select("SELECT * FROM clients");

        foreach ($oldClients as $oldClient) {
            
            $this->line($oldClient->name);
            
            if (! $client = Client::where('name', $oldClient->name)->first()) {
                $this->warn("Client doesn't exist!");
                continue;
            }

            if (empty($client->contacts)) {
                $client->contacts = $oldClient->contacts;
            }

            if (empty($client->notes)) {
                $client->notes = $oldClient->notes;
            }

            $client->save();
        }
    }
}

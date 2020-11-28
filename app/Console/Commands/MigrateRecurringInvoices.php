<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InvoiceNinja\Config as NinjaConfig;
use InvoiceNinja\Models\Client;
use InvoiceNinja\Models\Invoice;

class MigrateRecurringInvoices extends Command
{
    const INVOICE_FILLABLE_FIELDS = [
        'invoice_status_id',
        'discount',
        'po_number',
        'invoice_date',
        'due_date',
        'terms',
        'public_notes',
        'private_notes',
        'invoice_type_id',
        'is_recurring',
        'frequency_id',
        'start_date',
        'end_date',
        'is_amount_discount',
        'invoice_footer',
    ];

    const PRODUCT_KEY_MAPS = [
        "Pinterest Ads" => "Pinterest - Spend",
        "Pinterest Management" => "Pinterest - Management",
        "LinkedIn Ads" => "LinkedIn Ads - Spend",
        "LinkedIn Management" => "LinkedIn Ads - Management",
        "Graphics" => "Graphic Design",
        "Google Ads" => "Google Ads - Spend",
        "Google Management Fee" => "Google Ads - Management",
    ];

    const COMPLETED_CLIENTS = [
        38, 37, 36, 33, 32, 31, 30, 27, 23, 22, 21, 20, 18, 17, 16, 13, 15, 14, 4, 5, 2
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice-ninja:migrate:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $clientIdsMap = [];
    protected $failedInvoiceIds = [];

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
        $this->prepareClientIdMaps();

        $oldRecurringInvoices = $this->getOldRecurringInvoices();

        $failedInvoiceIds = [];

        NinjaConfig::setURL('https://billing.evolutionmarketing.com/api/v1');
        NinjaConfig::setToken('5hfjqhupy4mckigy2k2jssysov53mgrb');

        $this->info(count($oldRecurringInvoices) . " invoices migration started...");

        foreach ($oldRecurringInvoices as $oldInvoice) {
            $this->info("Migrating Invoice #" . $oldInvoice->id);

            if (! isset($this->clientIdsMap[$oldInvoice->client_id])) {
                $failedInvoiceIds[] = $oldInvoice->id;
                $this->warn("Matched client name not found!");
                continue;
            }

            if (in_array($this->clientIdsMap[$oldInvoice->client_id], self::COMPLETED_CLIENTS)) {
                $this->line("Already created!");
                continue;
            }

            if (empty($client = Client::find($this->clientIdsMap[$oldInvoice->client_id]))) {
                $failedInvoiceIds[] = $oldInvoice->id;
                $this->warn("Cannot fetch client.");
                continue;
            }

            $invoice = $client->createInvoice();
            foreach (self::INVOICE_FILLABLE_FIELDS as $field) {
                $invoice->$field = $oldInvoice->$field;
            }
            foreach($oldInvoice->invoice_items as $oldInvoiceItem) {
                $productKey = $oldInvoiceItem->product_key;
                if (isset(self::PRODUCT_KEY_MAPS[$oldInvoiceItem->product_key])) {
                    $productKey = self::PRODUCT_KEY_MAPS[$oldInvoiceItem->product_key];
                }
                $invoice->addInvoiceItem($productKey, $oldInvoiceItem->notes, $oldInvoiceItem->cost, $oldInvoiceItem->qty);
            }
            
            $invoice->save();

            $this->info('Done #' . $invoice->id);
        }

        $this->line('');
        var_dump($failedInvoiceIds);
    }

    protected function prepareClientIdMaps()
    {
        NinjaConfig::setURL('https://app.freedominvoicing.com/api/v1');
        NinjaConfig::setToken('wh5vfug52llpks08l9cmgwhkmq0ahbd0');
        $oldClients = Client::all();

        NinjaConfig::setURL('https://billing.evolutionmarketing.com/api/v1');
        NinjaConfig::setToken('5hfjqhupy4mckigy2k2jssysov53mgrb');
        $newClients = Client::all();

        foreach ($oldClients as $oldClient) {
            if ($oldClient->is_deleted) {
                continue;
            }

            foreach($newClients as $newClient) {
                if (trim($oldClient->name) == trim($newClient->name)) {
                    $this->clientIdsMap[$oldClient->id] = $newClient->id;
                    break;
                }
            }
        }
    }

    protected function getOldRecurringInvoices()
    {
        NinjaConfig::setURL('https://app.freedominvoicing.com/api/v1');
        NinjaConfig::setToken('wh5vfug52llpks08l9cmgwhkmq0ahbd0');
        
        return array_filter(Invoice::all(['is_recurring' => 1]), function($invoice) {
            return ! $invoice->is_deleted;
        });
        
    }
}

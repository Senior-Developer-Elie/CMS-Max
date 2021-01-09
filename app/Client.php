<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AngelInvoice;

use Carbon\Carbon;
class Client extends Model
{
    protected $fillable = [
        'name',
        'contacts',
        'notes',
        'api_id',
        'synced_at',
        'api_updated_at',
        'archived',
        'archived_at',
        'client_lead',
        'project_manager',
    ];

    public function websites(){
        return $this->hasMany('App\Website', 'client_id');
    }

    public function activeWebsites(){
        return $this->hasMany('App\Website', 'client_id')->where('archived', 0);
    }

    public function archivedWebsites(){
        return $this->hasMany('App\Website', 'client_id')->where('archived', 1);
    }

    public function syncingWebsites(){
        return $this->websites()->where('sync_from_client', 1);
    }

    public function updateWebsitesProducts(string $crmProductKey, array $data)
    {
        foreach ($this->syncingWebsites()->get() as $website) {
            $website->saveProduct($crmProductKey, $data);
        }
    }

    public function clientLead()
    {
        return $this->belongsTo(User::class, 'client_lead');
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    //Event Handler
    public static function boot() {
        parent::boot();

        //Soft delete
        static::deleting(function($client) {

            //Delete Inner Blog Files
            $client->websites()->delete();
        });

        static::updating(function($client)
        {
            $originalClient = $client->getOriginal();

            if( $originalClient['archived'] == false && $client->archived == true ){
                foreach( $client->websites()->get() as $website )
                {
                    $website->archived = true;
                    $website->archived_at = Carbon::now();
                    $website->save();
                }
            }

            if (! empty($originalClient['notes']) && $originalClient['notes'] != $client->notes) {
                $client->notesVersions()->create([
                    'notes' => $originalClient['notes']
                ]);;
            }
        });
    }

    public function updateWebsitesFeeValue($column, $value)
    {
        $this->websites()
            ->where('sync_from_client', 1)
            ->update([$column   => $value]);
    }

    public function apiProducts()
    {
        return $this->hasMany(\App\ClientApiProduct::class);
    }

    public function saveProduct(string $crmProductKey, float $value) : bool
    {
        if (! in_array($crmProductKey, AngelInvoice::crmProductKeys())) {
            return false;
        }

        $this->apiProducts()->updateOrCreate([
            'key' => $crmProductKey,
        ], [
            'value' => $value
        ]);

        return true;
    }

    public function getProductValue(string $crmProductKey)
    {
        $clientApiProduct = $this->apiProducts()->firstOrCreate([
            'key' => $crmProductKey
        ], [
            'value' => 0
        ]);

        return $clientApiProduct->value;
    }

    public function notesVersions()
    {
        return $this->hasMany(ClientNotesVersion::class);
    }
}

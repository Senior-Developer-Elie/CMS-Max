<?php

namespace App\Services;

use App\Proposal;
use Illuminate\Support\Collection;

class GetProposalWithClientNamesService
{
    public static function call(): Collection
    {
        $proposals = Proposal::all()->map(function($proposal) {
            return (object) [
                'id' => $proposal->id,
                'client_name' => $proposal->request['clientName'] ?? '',
            ];
        })->sortBy('client_name');

        return $proposals;
    }
}

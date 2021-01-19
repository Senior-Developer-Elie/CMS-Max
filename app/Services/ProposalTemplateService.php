<?php

namespace App\Services;

use App\Proposal;

class ProposalTemplateService
{
    const TEMPLATE_DATA = [
        Proposal::TEMPLATE_TYPE_EVOLUTION_MARKETING => [
            'phone' => "(585) 348-8127",
            'email' => "info@evolutionmarketing.com",
            'logo' => "evolution_marketing.logo"
        ],
        Proposal::TEMPLATE_TYPE_EVOLUTION_MARKETING_FLORIDA => [
            'phone' => "954-932-4499",
            'email' => "info@evolutionsouthflorida.com",
            'logo' => "evolution_marketing_florida.logo"
        ],
        Proposal::TEMPLATE_TYPE_VENICE_ONWARD => [
            'phone' => "941-315-4004",
            'email' => "sam@veniceonward.com",
            'logo' => "venice_onward.logo"
        ]
    ];

    public static function getTemplateContent($templateType)
    {
        $templateContent = self::TEMPLATE_DATA[$templateType];
        $templateContent['logo'] = file_get_contents(base_path() . "/resources/logos/" . $templateContent['logo']);

        return $templateContent;
    }
}

<?php
namespace App\Http\Helpers;

use App\PaymentGateway;
use App\ShippingMethod;
use App\Affiliate;
use App\AngelInvoice;
use App\Dns;

class WebsiteHelper {

    public static function getAllWebsiteTypes()
    {
        return [
            "regular"               => "Regular",
            "ecommerce"             => "eCommerce",
            "shopping-center-ceo"   => "Shopping Center CEO",
            "chamber-cms"           => "Chamber CMS",
            "liquor-rmh"            => "Liquor CMS",
            "no-website"            => "No Website",
            "redirect-website"      => "Redirect",
            "cbd-cms"               => "CBD CMS",
            "country-club-cms"      => "Country Club CMS"
        ];
    }

    public static function getAllWebsiteAffiliates()
    {
        $affiliates = Affiliate::orderBy('name')->get();
        $prettyAffiliates = [];
        foreach( $affiliates as $affiliate ) {
            $prettyAffiliates[$affiliate->id] = $affiliate->name;
        }
        return $prettyAffiliates;
    }

    public static function getAllWebsiteDNS()
    {
        $dnss = Dns::orderBy('name')->get();
        $prettyDnss = [];
        foreach( $dnss as $dns ) {
            $prettyDnss[] = [
                'value' => $dns->id,
                'text'  => $dns->name
            ];
        }
        return $prettyDnss;
    }

    public static function getShippingMethodTypes()
    {
        $shippingMethods = ShippingMethod::orderBy('name')->get();
        $prettyMethods = [];
        foreach( $shippingMethods as $method ) {
            $prettyMethods[$method->id] = $method->name;
        }
        return $prettyMethods;
    }

    public static function getAllEmailTypes()
    {
        return [
            "g-suite"       => "Google Workspace",
            "godaddy"       => "GoDaddy",
            "gmail"         => "GMAIL",
            "office-365"    => "Office 365",
            "rackspace"     => "Rackspace",
            "zoho"          => "Zoho",
            'n/a'           => 'N/A'
        ];
    }

    public static function getAllSitemapTypes()
    {
        return [
            "installed"     => "Installed",
            "not-installed" => "Not Installed",
            "n/a"           => "N/A",
        ];
    }

    public static function getAllLeftReviewTypes()
    {
        return [
            "google"    => "Google",
            "facebook"  => "Facebook",
            "thumbtack" => "Thumbtack",
            "yelp"      => "Yelp",
            "n/a"       => "n/a"
        ];
    }

    public static function getOnPortfolioTypes()
    {
        return [
            "yes"       => "Yes",
            "dont-add"  => "Don't add"
        ];
    }

    public static function getAllPaymentGateways()
    {
        $paymentGateways = PaymentGateway::orderBy('name')->get();
        $prettyGateways = [];
        foreach( $paymentGateways as $gateway ) {
            $prettyGateways[$gateway->id] = $gateway->name;
        }
        return $prettyGateways;
    }

    public static function getAllPostLiveOptions()
    {
        return [
            //"launch-site"               => "Launch Site",
            //"install-ssl"               => "Install SSL",
            //"configure-mailgun"         => "Configure Mailgun",
            // "setup-google-analytics"    => "Setup Google Analytics",
            "configure-search-console"  => "Configure Search Console",
            "install-and-run-sitemap"   => "Install and run Sitemap",
        ];
    }

    public static function getAllBillingtypes()
    {
        return [
            'cms-max' => 'Invoice Ninja',
            'chargebee' => 'Chargebee',
            'n/a' => 'N/A'
        ];
    }

    public static function getYextTypes()
    {
        return [
            0   => 'Available',
            -1  => 'N/A',
            -2  => 'Need to Sell',
            -3  => 'Not Interested'
        ];
    }

    public static function getBlogFrequencies()
    {
        return [
            "monthly" => 'Monthly',
            "bi-monthly" => 'Bi-Monthly',
            "quarterly" => 'Quarterly',
            "6 months" => '6 Months',
        ];
    }
}

<?php

use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            [
                'type'      => 'one-time',
                'name'      => 'website-development',
                'label'     => 'Website Development',
                'price'     => 4400,
                'content'   => 'Website Development
                                <ul>
                                    <li>Professional, SEO Friendly Copy Writing and Design of Homepage</li>
                                    <li>Homepage Video Production (Including YouTube Distribution)</li>
                                    <li>SEO Focused, Professional Copy Writing for Inner Pages as Needed</li>
                                    <li>Mobile Responsive Design</li>
                                    <li>Contact and Sign Up Forms Created and Linked to Emails</li>
                                    <li>Secure, Data Collection Gateways Set-Up and Tested</li>
                                    <li>Google Analytics Installed for Tracking Website Statistics</li>
                                    <li>All Existing Social Media Platforms Linked to Website</li>
                                    <li>Blogging Functionality</li>
                                    <li>Submission to Search Engines for Indexing</li>
                                    <li>Custom Content Management System</li>
                                </ul>'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'hosting',
                'label'     => 'Hosting',
                'price'     => 40,
                'content'   => 'Website Hosting, Backups, CDN Media & Website PageSpeed Performance including basic ADA Compliance.'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'ssl',
                'label'     => 'SSL',
                'price'     => 10,
                'content'   => 'Website Security & SSL Certificate'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'listings-management',
                'label'     => 'Listings Management',
                'price'     => 65,
                'content'   => 'Yext Knowledge Network Professional Package'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'google-ads',
                'label'     => 'Google Ads Spend',
                'price'     => 0,
                'content'   => 'Total Ad Spend'
            ],

            [
                'type'      => 'recurring',
                'name'      => 'google-ads-management',
                'label'     => 'Google Ads Management Fee',
                'price'     => 0,
                'content'   => 'Management fee for running Google Ads'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'internet-marketing',
                'label'     => 'Internet Marketing (SEO)',
                'price'     => 400,
                'content'   => 'Website Maintenance, Support, SEO Services & Updates etc.'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'website-maintenance',
                'label'     => 'Website Maintenance & Support',
                'price'     => 400,
                'content'   => 'Monthly Maintenance & Support
                                <ul>
                                    <li> CMS Technology Updates</li>
                                    <li> Ongoing Site Maintenance</li>
                                    <li> Ongoing SEO Based on Analytics Reports and CMS Testing Tool</li>
                                    <li> Monthly Change Support (2 Hrs./Month)</li>
                                    <li> Professional Blog Writing / Content Creation (1/quarter)</li>
                                    <li> Ongoing On-Page Optimization for Search Engines</li>
                                    <li> Ongoing Maintenance of Mobile Responsive Platform</li>
                                </ul>'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'g-suite',
                'label'     => 'Google Workspace',
                'price'     => 10,
                'content'   => 'Google\'s Cloud Based Email & Apps'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'social-media-setup',
                'label'     => 'Social Media Setup',
                'price'     => 300,
                'content'   => 'Setup, design * optimize Facebook, Google Business, Twitter, Instagram and YouTube'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'photography',
                'label'     => 'Photography',
                'price'     => 0,
                'content'   => 'Setup, design * optimize Facebook, Google Business, Twitter, Instagram and YouTube'
            ],
            [
                'type'      => 'recurring',
                'name'      => 'home-page-video',
                'label'     => 'Home Page Video',
                'price'     => 0,
                'content'   => 'Free video included with website purchase (Valued at $1800)'
            ],
            [
                'type'      => 'bottom-description',
                'content'   => '<strong>**Important Terms of Service:</strong> There are no minimum contract terms to the monthly agreement. If, at any time, the "client" decides to terminate service with CMS Max, Inc. any and all content created for the website, whether by CMS Max, Inc. or the "client", will become the property of the "client" directly and they will be entitled to move that content to a new platform without penalty. CMS Max, Inc. would move similar design & content to WordPress for a one-time fee of $600, if requested upon cancellation.',
                'name'      => '',
                'label'     => '',
                'price'     => 0,
            ]
        ]
        );
    }
}

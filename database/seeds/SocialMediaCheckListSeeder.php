<?php

use Illuminate\Database\Seeder;
use App\SocialMediaCheckList;

class SocialMediaCheckListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkLists = [
            SocialMediaCheckList::CHECKLIST_TYPE_CORE => [
                'Add client contact info to CRM',
                'Social team engages with a client discovery meeting',
                'Add to Hootsuite/eClincher',
                'Research the company & brand and service areas etc',
                'Research competitors',
                'Define social channels to use',
                'Refer to agreement or Define # of organic posts per channel per month',
                'Define ad spend and maintenance',
                'Add all items to TapClicks',
            ],
            SocialMediaCheckList::CHECKLIST_TYPE_FACEBOOK => [
                'Gain access/setup FB page',
                'Gain access/setup to their Ad Account',
                'Gain access/setup Pixel',
                'Fully optimize FB Page (all 13 steps)',
                'Upload Cover photo and thumbnail',
                'Check OG coding on fb cache debugger',
                'Find out if they are on Yext, if not try to sell client on Yext',
                'Sync to Yext',
                'Ensure client has set up their business manager in their name and verified the business info',
                'Link Instagram to Page',
                'DNS Verification',
            ],
            SocialMediaCheckList::CHECKLIST_TYPE_INSTAGRAM => [
                'Add client login to CRM',
                'Make sure it’s a business page',
                'Review all prior posts to mirror brand style',
                'Sync to Facebook',
                'Add profile picture and description',
            ],
            SocialMediaCheckList::CHECKLIST_TYPE_YOUTUBE => [
                'Add client login to CRM',
                'Update primary page video with latest brand content',
                'Gain access as an owner/manager',
                'Upload Brand thumbnail image',
                'Upload cover photo',
                'Add email address',
                'Add about/description',
                'Default settings to United States',
                'Add channel keywords',
            ],
            SocialMediaCheckList::CHECKLIST_TYPE_PINTEREST => [
                'Add client login to CRM',
                'DNS Verification', 
            ],
            SocialMediaCheckList::CHECKLIST_TYPE_TWITTER => [
                'Add client login to CRM',
                'Upload profile pic',
                'Upload cover photo',
                'Add bio and website URL',
            ],
        ];

        foreach ($checkLists as $target => $checkLists) {
            foreach ($checkLists as $index => $text) {
                SocialMediaCheckList::create([
                    'target' => $target,
                    'text' => $text,
                    'order' => $index + 1,
                ]);
            }
        }
    }
}

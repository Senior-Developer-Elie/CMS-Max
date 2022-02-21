<?php

namespace App\Http\Controllers\CmsMax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\WebsiteHelper;
use App\BlogIndustry;
use App\Website;
use App\User;

class CmsMaxController extends Controller
{
    protected $data = [];

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->data = [];

        $this->data['blogIndustries']          = $this->getBlogIndustriesForFilter();
        $this->data['blogIndustriesForInline'] = array_map(function($blogIndustry){
            return [
                'value' => $blogIndustry['id'],
                'text'  => $blogIndustry['name']
            ];
        }, $this->data['blogIndustries']->toArray());

        $this->data['currentSection']           = 'cms-max';
        $this->data['initialExpandOnHover']     = true;
        $this->data['websites']                 = $this->getActiveWebsites();
        $this->data['blogIndustries']           = $this->getBlogIndustriesForFilter();
        $this->data['admins']                   = User::get();

        return view('cms-max.index', $this->data);
    }

    protected function getActiveWebsites()
    {
        $query = Website::where('archived', 0);

        if (! empty(request()->input('blog_industry_id'))) {
            $query->where('blog_industry_id', request()->input('blog_industry_id'));
        }

        if (! empty(request()->input('affilliate_id'))) {
            $query->where('affiliate', request()->input('affilliate_id'));
        }

        if (! empty(request()->input('website_type'))) {
            $query->where('type', request()->input('website_type'));
        }

        if (! empty($syncStatus = request()->input('sync_status'))) {
            if ($syncStatus == 'synced') {
                $query->where('sync_from_client', 1);
            } else {
                $query->where('sync_from_client', 0);
            }
        }

        return $query->get();
    }

    protected function getBlogIndustriesForFilter()
    {
        return BlogIndustry::orderBy('name')
            ->with('websites')
            ->get()
            ->map(function($blogIndustry) {
                $blogIndustry->active_websites_count = $blogIndustry->websites->filter(function($website) {
                    return ! $website->archived;
                })->count();

                return $blogIndustry;
            });
    }

}

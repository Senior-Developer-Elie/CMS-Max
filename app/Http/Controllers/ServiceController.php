<?php

namespace App\Http\Controllers;

use App\Service;

use Illuminate\Http\Request;
use Session;
use Auth;

class ServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Manage Default Text
     */
    public function manageDefaultText(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage Default Text') )
            return redirect('/webadmin');

        $data = [
            'currentSection' => 'manage-default'
        ];

        $services = Service::getServices();
        $data['services'] = $services;

        return view("service.manage-default", $data);
    }


    /**
     * Add Service
     */
    public function addService(Request $request)
    {
        if( $request->isMethod('get') ) {
            $data = [
                'currentSection'    => 'manage-default',
            ];
            return view("service.edit", $data);
        }
        else {
            $dataToSave = $request->all();
            $dataToSave['name'] = uniqid();
            $service = new Service($dataToSave);
            $service->save();

            Session::flash('message', 'Service is added successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect("manage-default-text");
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editService(Request $request, $service_id)
    {
        $service = Service::find($service_id);
        if( !$service_id || !$service)
            abort(404);

        if( $request->isMethod("get") ){
            $data = [
                'currentSection'    => 'manage-default',
                'service'           => $service
            ];

            return view("service.edit", $data);
        }
        if( $request->isMethod("post") ){

            $service->name = $request->name;
            $service->type = $request->type;
            $service->label = $request->label;
            $service->price = $request->price;
            $service->content = $request->content;
            $service->save();

            Session::flash('message', 'Service is updated successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect("manage-default-text");
        }
    }

    /**
     * Delete Service
     */
    public function deleteService(Request $request, $service_id)
    {
        $service = Service::find($service_id);
        if( !$service_id || !$service)
            abort(404);

        if( $request->isMethod('get') )
        {
            $data = [
                'currentSection'    => 'manage-default',
                'service'           => $service
            ];
            return view("service.delete", $data);
        }
        else
        {
            $service->delete();

            Session::flash('message', 'Service is deleted successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/manage-default-text');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mockup;

use Illuminate\Support\Facades\Storage;

class MockupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => [
            'show',
        ]]);
    }

    /**
     * Upload Mockup Interface
     *
     * @return View
     */
    public function index()
    {
        return view('mockups.index', [
            'currentSection'    => 'mockup-generate'
        ]);
    }

    /**
     * Create Mockup
     *
     */
    public function create(Request $request)
    {
        if ( !$request->hasFile('images') )
            return response()->json([
                'status'    => 'error',
                'message'   => 'File not uploaded!'
            ]);

        $data = [
            'ip_address'    => $request->ip(),
            'title'         => $request->input('title'),
            'color'         => $request->input('color'),
            'align'         => $request->input('align'),
            'email'         => $request->input('email'),
            'image_path'    => ''
        ];

        //Generate random mockup id
        $data['mockup_id'] = uniqid();
        $data['url'] = $data['mockup_id']. '-' . str_replace(" ", "-", wordwrap(preg_replace('!\s+!', ' ', $data['title']), 28));

        $imagePaths = [];
        //Save Image File
        if ($request->hasFile('images')) {
            $files = request()->file('images');

            foreach( $files as $file )
            {
                $imagePaths[] = $file->store('mockups');
            }
        }
        $data['image_path'] = implode(",", $imagePaths);

        //Send Email
        if( !is_null($data['email']) )
        {
            //Send Email With Attachment
            \Mail::send('mockups.mail', $data, function($message) use ($data){
                $message->from(env('MAIL_FROM_ADDRESS', 'info@cmsmax.com'), 'CMS Max');
                $message->to($data['email'], 'CMSMax')->subject
                    ('New Website Mockup for ' . $data['title'] . ' from CMS Max');
            });
        }

        $mockup = new Mockup($data);

        $mockup->save();

        return response()->json([
            'status'    => 'success',
            'url'       => urlencode($data['url'])
        ]);
    }

    /**
     * Show Mockup
     *
     * @param string $url
     */
    public function  show(string $url, Request $request)
    {

        $mockup = Mockup::where([
            'url'   => urldecode($url)
        ])
        ->get()
        ->toArray();

        if( count($mockup) == 0 ) {
            echo "Sorry, the resource you are tyring to reach is not allowed.";
            die();
        }
        $mockup = $mockup[0];

        $imagesPaths = explode(",", $mockup['image_path']);
        $mockup['images'] = [];

        foreach( $imagesPaths as $imagePath )
        {
            $imageSize = getimagesize(Storage::url($imagePath));
            $mockup['images'][] = [
                'public_image_url'  => Storage::url($imagePath),
                'imageHeight'       => $imageSize[1],
                'imageWidth'        => $imageSize[0]
            ];
        }

        $data = [
            'mockup'        => [
                'title'     => $mockup['title'],
                'color'     => $mockup['color'],
                'align'     => $mockup['align'],
                'images'    => $mockup['images'],
            ]
        ];

        return view('mockups.show', $data);
    }

}

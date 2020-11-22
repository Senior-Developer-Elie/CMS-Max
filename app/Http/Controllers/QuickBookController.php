<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Auth;

class QuickBookController extends Controller
{
    protected $tmpPath = "data/";
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
     * Upload CSV
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('QuickBooks Import') )
            return redirect('/webadmin');

        return view("quickbooks-import/upload", [
            'currentSection'    => 'quickbooks-import',
        ]);
    }

    /**
     * Upload CSV
     */
    public function showTable(Request $request)
    {
        if ( !$request->hasFile('file') ) {
            abort(404);
        }

        $file = $request->file('file');
        $file_path = $file->getPathName();

        $csvContent = array_map('str_getcsv', file($file_path));

        $clientNames = array_slice($csvContent[0], 1);

        $values = [];
        //Search for net income row
        for( $i = count($csvContent) - 1; $i > 0; $i-- ){
            if( strtolower(trim($csvContent[$i][0])) =='net income' )
            {
                $values = array_slice($csvContent[$i], 1);
                break;
            }
        }

        if( count($values) == 0 ){
            echo "Cannot Find Net Income Row!";
            exit;
        }

        $prices = [];
        $total = 0;
        foreach( $clientNames as $index=>$clientName ){

            $cleanStr = str_replace('$', '', $values[$index]);
            $cleanStr = str_replace(',', '', $cleanStr);
            $cleanStr = str_replace('(', '', $cleanStr);
            $cleanStr = str_replace(')', '', $cleanStr);
            $cleanStr = str_replace(' ', '', $cleanStr);
            $cleanStr = str_replace('-', '', $cleanStr);
            $value = floatval($cleanStr);
            $prices[] = [
                'client'    => $clientName,
                'price'     => $value
            ];
            $total += $value;
        }

        usort($prices, function($x, $y){
            if( $x['price'] == $y['price'] ) return 0;
            if( $x['price'] < $y['price'] ) return 1;
            return -1;
        });

        $prices[] = [
            'client'    => 'Total',
            'price'     => $total
        ];

        return view("quickbooks-import/show", [
            'currentSection'    => 'quickbooks-import',
            'prices'            => $prices
        ]);
    }
}

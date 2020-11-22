<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PdfConvertHelper;
use Illuminate\Http\Request;

class PdfConvertController extends Controller
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

    public function index()
    {
        return view('pdf-convert.index', [
            'currentSection'    => 'pdf-to-image'
        ]);
    }

    public function process(Request $request)
    {

        if( $request->hasFile('file') )
        {
            $pdfFile = request()->file('file')->store('pdf-temp', ['disk' => 'local']);
            $pdfFile = storage_path('app/' . $pdfFile);

            //Width
            $width = 0;
            if( isset($_POST['width']) && !is_null($_POST['width']) && intval($_POST['width']) > 0)
            {
                $width = intval($_POST['width']);
            }

            //Space
            $space = 20;
            if( isset($_POST['space']) && !is_null($_POST['space']) && strlen($_POST['space']) > 0 )
            {
                $space = intval($_POST['space']);
            }

            //Quality
            $quality = 80;
            if( isset($_POST['quality']) && !is_null($_POST['quality']) && strlen($_POST['quality']) > 0 )
            {
                $quality = intval($_POST['quality']);
                if($quality > 100)
                    $quality = 100;
            }

            //Get If rotate
            $rotate = false;
            if( isset($_POST['rotate']) && $_POST['rotate'] == 'true' )
                $rotate = true;

            //Process image from PDF file
            try{
                $concatedImage =   PdfConvertHelper::getConcatedImageFromPDF($pdfFile, $width, $space, $quality, $rotate);
            }
            catch(\Exception $e)
            {
                $concatedImage =   false;
            }

            //Remove uploaded pdf file
            unlink($pdfFile);

            if( $concatedImage == false )
            {
                abort(404);
            }
            else
            {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=".$concatedImage);
                header("Content-Transfer-Encoding: binary ");
                readfile($concatedImage);
                unlink($concatedImage);
            }
        }
    }
}

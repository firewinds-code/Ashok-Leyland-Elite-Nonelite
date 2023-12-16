<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportPsfModel;
use App\Imports\ImportPsf;
use DB;
use Excel;

class PsfImportController extends Controller
{

     public function import(Request $request)
     {
        try {
             return view('psf.psfimport');
        } catch (\Exception $ex) {

        }
     }


     public function storeImportExcel(Request $request)
     {
        try{
                if($request->hasFile('import_file')){
                    $extension = $request->file('import_file')->getClientOriginalExtension();
                    if($extension != 'csv'){
                        $notification = array(
                            'message' => "File extension is not a CSV file",
                            'alert-type' => 'error'
                        );
                        return back()->with($notification);
                    }
                    $imageSize = $request->file('import_file')->getSize();
                    $fil = number_format($imageSize / 1048576,2);
                    if((float)$fil>1.3){
                        $notification = array(
                            'message' => "File should be less than 1.3 MB.",
                            'alert-type' => 'error'
                        );
                        return back()->with($notification);
                    }

                    $path = $request->file('import_file')->getRealPath();
                    $rowData = Excel::import(new ImportPsf, $path);
                }
                 $notification = array(
                     'message' => "PSF Data import successfully",
                     'alert-type' => 'success'
                 );
                 return back()->with($notification);

        }catch (\Exception $ex) {
             $notification = array(
                 'message' => $ex->getMessage().'Line: '.$ex->getLine().' Code: '.$ex->getCode(),
                 'alert-type' => 'error'
             );
             return back()->with($notification);
         }
     }



}

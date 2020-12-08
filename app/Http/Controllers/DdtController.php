<?php

namespace App\Http\Controllers;

use App\Models\Filiale;
use function compact;
use function dd;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use File;


class DdtController extends Controller
{
    public function index(Filiale $filiale, Request $request)
    {
        //$cartella = "ddt/".$filiale->nome;
        //$files = collect(Storage::files($cartella));

        $cartella = "storage/ddt/".$filiale->nome;
        $files = collect(File::files($cartella))
            ->sortByDesc(function ($file) {
                return $file->getCTime();
            });
       //dd($files);

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($files);

        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        /*$trimmed = str_replace($cartella."/", '', $files[0]) ;
        dd($trimmed) ;*/

        //dd(str_replace("/ddt/".$filiale->nome, "", $files[0]));
        //dd($paginatedItems);
        return view('ddt.ddt', compact('filiale', 'paginatedItems'));
    }
}

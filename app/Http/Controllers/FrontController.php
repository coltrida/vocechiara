<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index() {
        $oggi = Carbon::now()->format('Y-m-d');
        //dd($oggi);
        $clientRecall = Client::where([
            ['recall', 'x'],
            ['datarecall', '<', $oggi],
        ])
            ->paginate(10);
        //->get();
        // dd($clientRecall);
        return view('home.homeAdmin', compact('clientRecall'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Fattura;
use App\Models\Filiale;
use App\Models\Product;
use App\Models\Prova;
use App\Models\User;
use function compact;
use function dd;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FattureController extends Controller
{
    public function index(Filiale $filiale)
    {
        return view('fatture.fatture', compact('filiale'));
    }

    public function getFatture(Filiale $filiale)
    {

        $fatture = Prova::with('audio')
            ->where('stato', 'fatturato')
            ->whereHas("audio", function($q) use($filiale){
                $q->where("magazzino", $filiale->nome);
            })
            ->orderBy('updated_at', 'DESC')->get();

        //dd($fatture);

        $result = DataTables::of($fatture)
            ->addColumn('Azioni', function ($fattura) use ($filiale) {
                $nomefile = 'storage/fatture/'.$filiale->nome.'/'.$fattura->id.'.pdf';
                return '<div style="display: flex; justify-content: center">
                <a title="info" id="inf'.$fattura->id.'" href="'.route('fatture.infoFattura', $fattura->id).'" class="btn btn-primary info" data-toggle="modal" data-target="#infoFattura"><i class="fas fa-info"></i></a>
                
                <a target="_blank" title="stampa" href="'.'/'.$nomefile.'" class="btn btn-success"><i class="fas fa-print"></i></a>
                
            </div>';
            })
            ->editColumn('id_audio', function (Prova $prova){
                return $prova->audio->name;
            })
            ->editColumn('id_cliente', function (Prova $prova){
                return $prova->client->nome.' '.$prova->client->cognome;
            })
            ->editColumn('updated_at', function (Prova $prova){
                return $prova->updated_at->format('d/m/Y');
            })
            ->rawColumns(['Azioni'])   /*per renderizzare html nel datatable, serve rawColumns e la lista delle colonne*/

            ->make(true);

        return $result;
    }

    public function info($id)
    {
        $fattura = Prova::with('provaprodotti', 'prodottiprova', 'products')->find($id);
        return $fattura;
    }

    public function assegnapagamento(Request $request)
    {

        $fattura = new Fattura();
        $fattura->id_prova = $request->input('id_prova');
        $fattura->acconto = $request->input('acconto');
        $fattura->nr_rate = $request->input('nr_rate');
        $res = $fattura->save();
        return "".$res;
    }
}

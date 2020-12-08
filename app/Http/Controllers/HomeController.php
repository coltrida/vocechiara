<?php

namespace App\Http\Controllers;

use App\Models\Audiometria;
use App\Models\Client;
use App\Models\Fattura;
use App\Models\Filiale;
use App\Models\Font;
use App\Models\Listino;
use App\Models\Note;
use App\Models\Product;
use App\Models\Prova;
use App\Models\ProvaProdotti;
use App\Models\User;
use function auth;
use Carbon\Carbon;
use function compact;
use function dd;
use Illuminate\Http\Request;
use const null;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // --------------------- HOME AUDIOPROTESISTA ------------------------//
        if (auth()->user()->isAudio()){
            $id = auth()->user()->id;
            $oggi = Carbon::now()->format('Y-m-d');
            $limite = Carbon::now()->subMonth(3);
            $clientRecall = Client::where([
                ['recall', 'x'],
                ['datarecall', '<=', $oggi],
                ['user_id', $id]
            ])->paginate(10);

            $recallAutomatici = Client::where([
                ['tipo', 'PC'],
                ['updated_at', '<=', $limite],
                ['user_id', $id]
            ])->paginate(10);
            return view('home.homeAudio', compact('clientRecall', 'recallAutomatici'));
        // --------------------- FINE HOME AUDIOPROTESISTA ------------------------//

        // --------------------- HOME AMMINISTRATIVE ------------------------//
        }elseif(auth()->user()->isAmministrativa()) {
            // --------------------- HOME CARLA ------------------------//
            if (auth()->user()->isCarla()){
                return view('home.homeCarla');
            }else {
            // --------------------- FINE HOME CARLA ------------------------//
                dd('Amministrativa');
            }
        // --------------------- FINE HOME AMMINISTRATIVE ------------------------//

        }elseif(auth()->user()->isAdmin()){
            return view('home.homeAdmin');
        }

    }

    public function getSpedizioni()
    {
        $spedizioni = Prova::where([
            ['stato', 'prova']
        ])
            ->orWhere('stato', 'richiesta')
            ->doesnthave('products')
            ->orderBy('id_audio')
            ->orderBy('created_at', 'DESC')
            ->get();

        $result = DataTables::of($spedizioni)
            ->addColumn('Azioni', function (Prova $prova) {
                return '<div style="display: flex; justify-content: center">
                <!--<a title="DDT" href="#" class="btn btn-primary"><i class="fas fa-truck-moving"></i></a> 
                    &nbsp; -->
                <a title="info" href="'.route('prove.info', $prova->id).'" data-magazzino="'.$prova->audio->magazzino.'" class="btn btn-primary info" data-toggle="modal" data-target="#infoProva"><i class="fas fa-truck-moving"></i></a>
                
            </div>';
            })
            ->editColumn('id_audio', function (Prova $prova){
                return $prova->audio->name;
            })
            ->editColumn('id_cliente', function (Prova $prova){
                if($prova->id_cliente){
                    return $prova->client->nome.' '.$prova->client->cognome;
                }else{
                    return " ";
                }

            })
            ->editColumn('created_at', function (Prova $prova){
                return $prova->created_at->format('d/m/Y');
            })
            ->rawColumns(['Azioni'])   /*per renderizzare html nel datatable, serve rawColumns e la lista delle colonne*/

            ->make(true);

        return $result;

        /*$dafatturare = Prova::with('client')
            ->where([
                ['stato', 'vendita']
            ])
            ->orderBy('id_audio')
            ->paginate(10);
        return compact('spedizioni', 'dafatturare');*/
    }

    public function getFatture()
    {
        $fatture = Prova::where([
            ['stato', 'vendita']
        ])
            ->orderBy('id_audio')
            ->orderBy('created_at', 'DESC')
            ->get();

        $result = DataTables::of($fatture)
            ->addColumn('Azioni', function (Prova $prova) {
                return '<div style="display: flex; justify-content: center">
                <a title="Fattura" href="'.route('prove.fattura', $prova->id).'" target="_blank" class="btn btn-success fattura"><i class="fas fa-wallet"></i></a> 
                    &nbsp; 
                <a title="info" href="'.route('prove.info', $prova->id).'" class="btn btn-primary infofattura" data-toggle="modal" data-target="#infoFattura"><i class="fas fa-search"></i></a>
                
            </div>';
            })
            ->editColumn('id_audio', function (Prova $prova){
                return $prova->audio->name;
            })
            ->editColumn('id_cliente', function (Prova $prova){
                return $prova->client->nome.' '.$prova->client->cognome;
            })
            ->editColumn('created_at', function (Prova $prova){
                return $prova->updated_at->format('d/m/Y');
            })
            ->rawColumns(['Azioni'])   /*per renderizzare html nel datatable, serve rawColumns e la lista delle colonne*/

            ->make(true);

        return $result;
    }

    public function resetDB()
    {
        Audiometria::truncate();
        Font::truncate();
        Client::truncate();
        Fattura::truncate();
        Listino::truncate();
        Note::truncate();
        Product::truncate();
        Prova::truncate();
        ProvaProdotti::truncate();
        User::where('ruolo', '!=', 'Admin')->delete();
        return view('home.homeAdmin');
    }
}

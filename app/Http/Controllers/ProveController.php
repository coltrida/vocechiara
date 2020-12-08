<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Listino;
use App\Models\Product;
use App\Models\Prova;
use App\Models\ProvaProdotti;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use function compact;
use function count;
use function dd;
use Illuminate\Http\Request;
use function intval;
use const null;
use Yajra\DataTables\DataTables;
use Log;

class ProveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prove.prove');
    }

    public function info($id)
    {
        $prova = Prova::with('provaprodotti', 'prodottiprova', 'products')->find($id);
        return $prova;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idaudio = auth()->user()->id;
        $anno = Carbon::now()->year;
        $ultima = Prova::with('audio')
            ->select(['nr_ordine'])
            ->whereYear('created_at',$anno)
            ->whereHas('audio', function ($query) {
                $magazzino = auth()->user()->magazzino;
                $query->where('magazzino', $magazzino);
            })
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($ultima){
            $numero = (int)$ultima->nr_ordine + 1;
        }else{
            $numero = 1;
        }

        $prova = new Prova();
        $prova->fill($request->only(['id_cliente', 'tot', 'stato']));
        $prova->id_audio = $idaudio;
        $prova->nr_ordine = $numero;
        $res = $prova->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato la prova per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' - con numero ordine: '.$prova->nr_ordine;
        Log::Info($messaggio);

        for ($i=0; $i<count($request->input('ids')); $i++)
        {
            $provaprodotto = new ProvaProdotti();
            $provaprodotto->id_prova = $prova->id;
            $provaprodotto->id_listino = $request->input('ids')[$i];
            $provaprodotto->prezzo = $request->input('prezzi')[$i];
            $provaprodotto->quantita = $request->input('quantita')[$i];
            $provaprodotto->save();
        }

        if ($request->input('matricole')){
            for ($i=0; $i<count($request->input('matricole')); $i++)
            {
                $product = Product::where('matricola', $request->input('matricole')[$i])->first();
                $product->stato = 'prova';
                $product->id_prova = $prova->id;
                $product->save();

                $messaggio = 'L\'utente: '.auth()->user()->name.' ha inserito nella prova per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' e con numero ordine: '.$prova->nr_ordine.' - il prodotto: '.$product->listi->descrizione.' - con matricola: '.$product->matricola;
                Log::Info($messaggio);
            }
        }

        return ''.$res;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        $prodottiListino = Listino::where('categoria', 'APA')->orderBy('descrizione')->get();
        $optListino = Listino::where('categoria', 'OPT')->orderBy('descrizione')->get();
        return view('prove.editprova', compact('client', 'prodottiListino', 'optListino'));
    }

    public function getMatricolepresenti($id)
    {
        $magazzino = auth()->user()->magazzino;
        $matricole = Product::where([
                ['id_listino', $id],
                ['destinazione', $magazzino],
                ['stato', null]
            ])
            ->orWhere([
                ['id_listino', $id],
                ['destinazione', $magazzino],
                ['stato', 'reso']
            ])
            ->get();
        return compact('matricole');
    }

    public function getMatricole($id)
    {
        /*$magazzino = auth()->user()->magazzino;*/
        $matricole = Product::where([
            ['id_listino', $id],
            ['destinazione', null],
            ['stato', null]
        ])
/*            ->orWhere([
                ['id_listino', $id],
                ['destinazione', $magazzino],
                ['stato', 'reso']
            ])*/
            ->get();
        return compact('matricole');
    }

    public function getProve($id)
    {
         //dd($id);
        $prove = Prova::with('products')->where('id_cliente', $id)
            ->orderBy('created_at', 'DESC')->get();
        return compact('prove');
    }

    public function vendita(Prova $prova)
    {
            $prova->stato = 'vendita';
            $res = $prova->save();

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha convertito in vendita la prova per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' - con numero ordine: '.$prova->nr_ordine;
            Log::Info($messaggio);

            $now = Carbon::now();
            $prodotti = Product::where('id_prova', $prova->id)->get();
            foreach ($prodotti as $item){
                $item->stato = 'vendita';
                $item->updated_at = $now;
                $item->save();

                $messaggio = 'L\'utente: '.auth()->user()->name.' ha venduto il prodotto: '.$item->listi->descrizione.' - con matricola: '.$item->matricola;
                Log::Info($messaggio);
            }


        return ''.$res;
    }

    public function reso(Prova $prova)
    {
        $prova->stato = 'reso';
        $res = $prova->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha convertito in reso la prova per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' - con numero ordine: '.$prova->nr_ordine;
        Log::Info($messaggio);

        $prodotti = Product::where('id_prova', $prova->id)->get();
        foreach ($prodotti as $item){
            $item->stato = 'reso';
            $item->save();

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha reso il prodotto: '.$item->listi->descrizione.' - con matricola: '.$item->matricola;
            Log::Info($messaggio);
        }

        return ''.$res;
    }

    public function annulla(Prova $prova)
    {
        $prova->stato = 'annullato';
        $res = $prova->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha annullato la prova per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' - con numero ordine: '.$prova->nr_ordine;
        Log::Info($messaggio);

        return ''.$res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Prova $prova)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getClients()
    {
        $id = Auth::user()->id;
        $ruolo = Auth::user()->ruolo;
        if ($ruolo == 'Audio'){
            $clients = Client::with('user')->with('prove')
                ->select(['id','nome','cognome','indirizzo','cap','citta','provincia','telefono','tipo','fonte', 'user_id', 'recall', 'datarecall'])
                ->where('user_id', $id)
                ->whereHas('prove', function ($query) {
                    $query->where('stato', 'prova')
                        ->orWhere('stato', 'provacorso');
                })
                ->orderBy('cognome')
                ->get();
        } else {
            $clients = Client::with('user')->with('prove')
                ->select(['id','nome','cognome','indirizzo','cap','citta','provincia','telefono','tipo','fonte', 'user_id', 'recall', 'datarecall'])
                ->whereHas('prove', function ($query) {
                    [
                        [$query->where('stato', 'prova')]
                    ];
                })
                ->orderBy('cognome')
                ->get();
        }


        $result = DataTables::of($clients)
            ->editColumn('user_id', function (Client $client){
                return $client->user->name;
            })
            ->editColumn('ultimav', function (Client $client){
                return $datavendita = Prova::where([
                    ['id_cliente', $client->id],
                    ['stato', 'vendita']
                ])
                    ->orderBy('updated_at', 'DESC')->first();
            })
            ->addColumn('Azioni', function ($client) {
                $giorno=date_create($client->datarecall);
                $datarec = date_format($giorno,"d/m/Y");
                //$datarec = 'ciao';
                if ($client->recall){
                    return '<div style="display: flex; justify-content: flex-start">
                <a title="elimina" id="del'.$client->id.'" href="'.route('clients.destroy', $client->id).'" class="btn-sm btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$client->id.'" href="'.route('clients.edit', $client->id).'" class="btn-sm  btn-primary"><i class="fas fa-edit"></i></a>
                &nbsp; 
                <a title="info" id="info'.$client->id.'" href="'.route('clients.info', $client->id).'" class="btn-sm  btn-success" data-toggle="modal" data-target="#infoClient"><i class="fas fa-info-circle"></i></a>
                &nbsp; 
                <a title="note" id="note'.$client->id.'" href="#" class="btn-sm  note" data-toggle="modal" style="background-color: yellow" data-target="#noteClient"><i class="far fa-sticky-note"></i></a>
                &nbsp; 
                <a title="apa" id="apa'.$client->id.'" href="'.route('provesel.show', $client->id).'" class="btn-sm  apa" style="background-color: #4c110f"><i class="fas fa-assistive-listening-systems"></i></a>
                &nbsp;
                <a title="'.$datarec.'" id="'.$client->id.'" href="'.route('clients.phone', $client->id).'" class="btn-sm  phone" data-toggle="modal" style="background-color: #856404" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                
            </div>';
                }
                else{
                    return '<div style="display: flex; justify-content: flex-start">
                <a title="elimina" id="del'.$client->id.'" href="'.route('clients.destroy', $client->id).'" class="btn-sm  btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$client->id.'" href="'.route('clients.edit', $client->id).'" class="btn-sm  btn-primary"><i class="fas fa-edit"></i></a>
                &nbsp; 
                <a title="info" id="info'.$client->id.'" href="'.route('clients.info', $client->id).'" class="btn-sm  btn-success" data-toggle="modal" data-target="#infoClient"><i class="fas fa-info-circle"></i></a>
                &nbsp; 
                <a title="note" id="note'.$client->id.'" href="#" class="btn-sm  note" data-toggle="modal" style="background-color: yellow" data-target="#noteClient"><i class="far fa-sticky-note"></i></a>
                &nbsp; 
                <a title="apa" id="apa'.$client->id.'" href="'.route('provesel.show', $client->id).'" class="btn-sm  apa" style="background-color: #4c110f"><i class="fas fa-assistive-listening-systems"></i></a>
                &nbsp;
                <a title="phone" id="'.$client->id.'" href="'.route('clients.phone', $client->id).'" class="btn-sm  phone" style="background-color: lightblue" data-toggle="modal" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                
            </div>';
                }

            })
            ->rawColumns(['Azioni'])   /*per renderizzare html e stile nel datatable, serve rawColumns e la lista delle colonne*/
            ->make(true);

        return $result;
    }

    public function mese()
    {
        $mese = Carbon::now()->month;
        $audio = User::where('ruolo', 'Audio')->orderBy('name')->get();
        $fatturati = array();
        $prove = array();

        for ($j = 0; $j < count($audio); $j++){
            $tot = 0;
            $vendite = Prova::where([
                ['stato', 'fatturato'],
                ['id_audio', $audio[$j]->id]
            ])
                ->whereMonth('updated_at', $mese)
                ->get();

            if (count($vendite) > 0){
                foreach ($vendite as $vendita){
                    $tot += intval($vendita->tot);
                }
            } else{
                $tot = 0;
            }
            array_push($fatturati, $tot);

            $incorso = Prova::where([
                ['stato', 'prova'],
                ['id_audio', $audio[$j]->id]
            ])
                ->whereMonth('updated_at', $mese)
                ->get();
            array_push($prove, count($incorso));
        }

        return view('prove.statistiche', compact('mese',  'audio', 'fatturati', 'prove'));
    }

    public function anno()
    {
        $anno = Carbon::now()->year;
        $mese = Carbon::now()->month;
        $vendite = array();
        $fatturati = array();
        $audio = User::where('ruolo', 'Audio')->orderBy('name')->get();
//dd($audio[0]->name);
        for ($j=0; $j< count($audio); $j++){
            $vendite[$j] = array();
            $fatturati[$j] = array();

            array_push($fatturati[$j], $audio[$j]->name);

            for ($i=1; $i <= $mese; $i++){
                $tot = 0;
                $vendite[$j][$i] = Prova::where([
                    ['stato', 'fatturato'],
                    ['id_audio', $audio[$j]->id]
                ])
                    ->whereMonth('updated_at', $i)
                    ->get();

                if (count($vendite[$j][$i]) > 0){
                    foreach ($vendite[$j][$i] as $vendita){
                        $tot += intval($vendita->tot);
                    }
                } else{
                        $tot = 0;
                    }
                    //dd($tot);
                array_push($fatturati[$j], $tot);

            }
        }

        //dd($fatturati);
        return view('prove.statisticheanno', compact('fatturati', 'anno', 'mese'));
    }
}

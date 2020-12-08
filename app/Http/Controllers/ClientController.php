<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientFormRequest;
use App\Models\Audiometria;
use App\Models\Client;
use App\Models\Font;
use App\Models\Note;
use App\Models\Prova;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use function compact;
use function count;
use function dd;
use Illuminate\Http\Request;
use const null;
use function redirect;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;
use Log;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clienti.clienti');
    }

    public function getClients()
    {
        $id = Auth::user()->id;
        $ruolo = Auth::user()->ruolo;

        if ($ruolo == 'Audio') {
            $clients = Client::with('user')
                ->select(['id', 'nome', 'cognome', 'cod_fisc', 'indirizzo', 'cap', 'citta', 'provincia', 'telefono', 'tipo', 'fonte', 'user_id', 'recall', 'datarecall'])
                ->where('user_id', $id)
                ->orderBy('cognome')
                ;
        } else {
            $clients = Client::with('user')
                ->select(['id', 'nome', 'cognome', 'cod_fisc', 'indirizzo', 'cap', 'citta', 'provincia', 'telefono', 'tipo', 'fonte', 'user_id', 'recall', 'datarecall'])
                ->orderBy('cognome')
                ;
        }
        $result = DataTables::of($clients)
            ->editColumn('user_id', function (Client $client){
                return $client->user->name;
            })
            ->editColumn('ultimav', function (Client $client){
                if (count($client->prove)){
                    $vendita = Prova::where([
                        ['id_cliente', $client->id],
                        ['stato', 'vendita']
                    ])->orderBy('updated_at', 'DESC')->first();
                    if ($vendita){
                        return $vendita->updated_at->format('d/m/Y');
                    }else{
                        return '';
                    }

                } else{
                    return '';
                }
                //return $client->prove;
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
                <a title="'.$datarec.'" href="#" id="'.$client->id.'" class="btn-sm  phone" data-toggle="modal" style="background-color: #856404" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                
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
                <a title="phone" href="#" id="'.$client->id.'" class="btn-sm  phone" style="background-color: lightblue" data-toggle="modal" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                
            </div>';
                }

            })
            ->rawColumns(['Azioni'])   /*per renderizzare html e stile nel datatable, serve rawColumns e la lista delle colonne*/
            ->make(true);

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client();
        $audios = User::where('ruolo', 'Audio')->get();
        $fonts = Font::orderBy('name')->get();
        return view('clienti.editcliente', compact('client', 'audios', 'fonts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientFormRequest $request)
    {
        //dd($request->all());
        $client = new Client();
        $client->fill($request->only(['nome', 'cognome', 'cod_fisc', 'indirizzo', 'cap', 'citta', 'provincia', 'telefono', 'tipo', 'fonte', 'user_id']));
        $res = $client->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato il cliente: '.$client->nome.' '.$client->cognome.' - '.$client->cod_fisc;
        Log::Info($messaggio);

        if(request()->input('audiogrammachange') == 1){
            $audiometria = new Audiometria();
            $audiometria->fill($request->only(['_250s', '_250d', '_500s', '_500d', '_1000s', '_1000d', '_2000s', '_2000d', '_4000s', '_4000d', '_8000s', '_8000d']));
            $audiometria->id_cliente = $client->id;
            $audiometria->save();
        }

        $messaggio = $res ? 'Cliente inserito correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('clients.index');
    }

    public function phonestore(Request $request)
    {
       // dd($request->all());
        $client = Client::find($request->input('id_cliente'));
        if($request->input('recall')){
            $client->datarecall = $request->input('recall');
            $client->recall = 'x';
            $client->save();

            $giorno=date_create($client->datarecall);
            $datarec = date_format($giorno,"d/m/Y");

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha inserito un recall per il cliente: '.$client->nome.' '.$client->cognome.' - '.$client->cod_fisc.' - per il giorno: '.$datarec;
            Log::Info($messaggio);
        }
        return redirect()->route('clients.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return [$client, $client->audiometria->last()];
    }

    public function phone(Client $client)
    {
        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $audios = User::where('ruolo', 'Audio')->get();
        $fonts = Font::orderBy('name')->get();
        $audiometria = $client->audiometria->last();
       // dd($audiometria);
        return view('clienti.editcliente', compact('client', 'audios', 'fonts', 'audiometria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientFormRequest $request, Client $client)
    {
        $client->fill($request->only(['nome', 'cognome', 'cod_fisc', 'indirizzo', 'cap', 'citta', 'provincia', 'telefono', 'tipo', 'fonte', 'user_id']));

        //$client->fill($request->all());
        $res = $client->save();
        if(request()->input('audiogrammachange') == 1){
            $audiometria = new Audiometria();
            $audiometria->fill($request->only(['_250s', '_250d', '_500s', '_500d', '_1000s', '_1000d', '_2000s', '_2000d', '_4000s', '_4000d', '_8000s', '_8000d']));
            $audiometria->id_cliente = $client->id;
            $audiometria->save();
        }

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha aggiornato il cliente: '.$client->nome.' '.$client->cognome.' - '.$client->cod_fisc;
        Log::Info($messaggio);

        $messaggio = $res ? 'Cliente aggiornato correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('clients.edit', ['id' => $client->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato il cliente: '.$client->nome.' '.$client->cognome.' - '.$client->cod_fisc;
        Log::Info($messaggio);

        $res = $client->delete();
        return ''.$res;
    }

    public function destroyCall(Client $client)
    {
        $client->recall = null;
        $client->datarecall = null;
        $res = $client->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato il recall per il cliente: '.$client->nome.' '.$client->cognome;
        Log::Info($messaggio);

        return ''.$res;
    }

    public function destroyCallAuto(Client $client)
    {
        $client->updated_at = Carbon::now();
        $res = $client->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato il recall per il cliente: '.$client->nome.' '.$client->cognome;
        Log::Info($messaggio);

        return ''.$res;
    }

    public function import()
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha importato il file prova.xlsx';
        Log::Info($messaggio);

        Excel::import(new ClientsImport, 'prova.xlsx');
        return view('clienti.clienti');
    }


}

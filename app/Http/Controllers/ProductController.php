<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\ProductFormRequest;
use App\Models\Client;
use App\Models\Filiale;
use App\Models\Listino;
use App\Models\Product;
use App\Models\Prova;
use App\Models\ProvaProdotti;
use function array_push;
use function auth;
use Carbon\Carbon;
use function compact;
use function dd;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Log;
use App\Imports\ProductImport;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = new Product();
        return view('products.products', compact('product'));
    }

    public function altroindex(Filiale $filiale)
    {
        return view('products.altriproducts', compact('filiale'));
    }

    public function richiedi()
    {
        return view('products.richiediprodotti');
    }

    public function getProdottiRichiesti($id)
    {
        /*$prodotti = Product::with('listi')
        ->where([
            ['destinazione', $magazzino],
            ['stato', 'richiesta']
        ])
            ->orderBy('created_at', 'DESC')->get();
        return compact('prodotti');*/

        $prove = Prova::with('products')
            ->where([
                ['id_audio', $id],
                ['stato', 'richiesta']
                ])
            ->orderBy('created_at', 'DESC')->get();
        return compact('prove');
    }

    public function storeRichiedi(Request $request)
    {
        $idaudio = auth()->user()->id;

        $prova = new Prova();
        $prova->fill($request->only(['stato']));
        $prova->id_audio = $idaudio;
        $res = $prova->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato la prova ';
        Log::Info($messaggio);

        for ($i=0; $i<count($request->input('ids')); $i++)
        {

            $idlistino = $request->input('ids')[$i];
            $listino = Listino::find($idlistino);
            $quantita = $request->input('quantita')[$i];

            if (($listino->categoria != 'CON') and ($quantita > 1)){
                for ($j=0; $j<$quantita; $j++){
                    $provaprodotto = new ProvaProdotti();
                    $provaprodotto->id_prova = $prova->id;
                    $provaprodotto->id_listino = $idlistino;
                    $provaprodotto->quantita = '1';
                    $provaprodotto->save();
                }
            }else{
                $provaprodotto = new ProvaProdotti();
                $provaprodotto->id_prova = $prova->id;
                $provaprodotto->quantita = $quantita;
                $provaprodotto->id_listino = $idlistino;
                $provaprodotto->save();
            }

        }

        return ''.$res;
    }

    public function getProducts()
    {
        if (auth()->user()->isAudio()){
            auth()->user()->name;
            $magazzino = auth()->user()->magazzino;
            $products = Product::with('listi')
                ->where([
                    ['destinazione', $magazzino],
                    ['stato', '!=', 'vendita']
                    ])
                ->orwhere([
                    ['destinazione', $magazzino],
                    ['stato', null]
                ])
                ->orderBy('id_listino')
                ->get();
        }else{
            $products = Product::with('listi')
                /*->where('stato', '<>', 'vendita')*/
                ->WhereNull('destinazione')
                ->orderBy('id_listino')
                /*->orderBy('destinazione')*/
                ->get();
        }


        $result = DataTables::of($products)

            ->editColumn('costo', function (Product $product){
                return $product->listi['costo'];
            })
            ->editColumn('id_listino', function (Product $product){
                return $product->listi['descrizione'];
            })
            ->editColumn('categoria', function (Product $product){
                return $product->listi['categoria'];
            })
            ->editColumn('prezzo', function (Product $product){
                return $product->listi['prezzolistino'];
            })
            ->editColumn('iva', function (Product $product){
                return $product->listi['iva'];
            })
            ->editColumn('destinazione', function (Product $product){
                if (isset($product->destinazione)){
                    $magazzino = $product->destinazione;
                }else{
                    $magazzino = '';
                }
                return $magazzino;
            })
            ->addColumn('Azioni', function ($product) {

                /*return '<div style="display: flex; justify-content: flex-start">
                <a title="elimina" id="del'.$product->id.'" href="'.route('products.destroy', $product->id).'" class="btn-sm btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$product->id.'" href="'.route('products.edit', $product->id).'" class="btn-sm  btn-primary"><i class="fas fa-edit"></i></a>                                
                
            </div>';*/
                if ($product->stato == 'prova'){
                    $result = '<div style="display: flex; justify-content: center"><a data-toggle="modal" data-target="#infoProductProva" title="info" id="info'.$product->id.'" href="'.route('products.show', $product->id).'" class="btn-sm btn-success"><i class="fa fa-info-circle" ></i></a> </div>';
                } else {
                    $result = '';
                }
            return $result;
            })
            ->rawColumns(['Azioni', 'destinazione'])
            ->make(true);
        return $result;
    }

    public function getAltriProducts(Filiale $filiale)
    {
            $products = Product::with('listi')
                ->where([
                    ['destinazione', $filiale->nome],
                    ['stato', '!=', 'vendita']
                ])
                ->orwhere([
                    ['destinazione', $filiale->nome],
                    ['stato', null]
                ])
                ->orderBy('id_listino')
                ->get();

        $result = DataTables::of($products)

            ->editColumn('costo', function (Product $product){
                return $product->listi['costo'];
            })
            ->editColumn('id_listino', function (Product $product){
                return $product->listi['descrizione'];
            })
            ->editColumn('categoria', function (Product $product){
                return $product->listi['categoria'];
            })
            ->editColumn('prezzo', function (Product $product){
                return $product->listi['prezzolistino'];
            })
            ->editColumn('iva', function (Product $product){
                return $product->listi['iva'];
            })
            ->editColumn('destinazione', function (Product $product){
                if (isset($product->destinazione)){
                    $magazzino = $product->destinazione;
                }else{
                    $magazzino = '<div class="custom-control custom-checkbox"><input type="checkbox" id="'.$product->id.'" name="prodotti[]"></div>';
                }
                return $magazzino;
            })
            ->addColumn('Azioni', function ($product) {

                /*return '<div style="display: flex; justify-content: flex-start">
                <a title="elimina" id="del'.$product->id.'" href="'.route('products.destroy', $product->id).'" class="btn-sm btn-danger"><i class="fa fa-trash" ></i></a>
                    &nbsp;
                <a title="modifica" id="mod'.$product->id.'" href="'.route('products.edit', $product->id).'" class="btn-sm  btn-primary"><i class="fas fa-edit"></i></a>

            </div>';*/
                if ($product->stato == 'prova'){
                    $result = '<div style="display: flex; justify-content: center"><a data-toggle="modal" data-target="#infoProductProva" title="info" id="info'.$product->id.'" href="'.route('products.show', $product->id).'" class="btn-sm btn-success"><i class="fa fa-info-circle" ></i></a> </div>';
                } else {
                    $result = '';
                }
                return $result;
            })
            ->rawColumns(['Azioni', 'destinazione'])
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
        $product = new Product();
        $listino = Listino::orderBy('descrizione')->get();
        return view('products.editproduct', compact('product', 'listino'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->fill($request->only(['matricola', 'id_listino', 'destinazione', 'quantita']));

        $res = $product->save();

        if ($product->destinazione){
            $destinazione = ' con destinazione: '.$product->destinazione;
        }else{
            $destinazione = ' senza destinazione';
        }

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato il prodotto '.$product->listi->descrizione.' con matricola:  '.$product->matricola.$destinazione;
        Log::Info($messaggio);

        $messaggio = $res ? 'Prodotto inserito correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        //return redirect()->route('products.edit', ['id' => $product->id]);
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('prova')->Find($id);
        return $product->prova->client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $listino = Listino::orderBy('descrizione')->get();
        return view('products.editproduct', compact('listino', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->fill($request->all());
        $res = $product->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha modificato il prodotto '.$product->listi->descrizione.' con matricola:  '.$product->matricola;
        Log::Info($messaggio);

        $messaggio = $res ? 'Prodotto aggiornato correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('products.edit', ['id' => $product->id]);
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

    public function assegnaProva(Request $request)
    {
        $id_prova = $request->input('id_prova');
        $prova = Prova::find($id_prova);

        $destinazione = $request->input('destinazione');
        for ($i=0; $i<count($request->input('matricole')); $i++)
        {
            $product = Product::where('matricola', $request->input('matricole')[$i])
                                ->whereNull('destinazione')
                                ->first();
            if ($product->listi->categoria == 'CON'){
                $quantita = $request->input('quantita')[$i];
                if ($product->quantita >= $quantita){
                    $product->quantita -= $quantita;
                    $product->save();

                    $productpresente = Product::where([
                        ['matricola', $request->input('matricole')[$i]],
                        ['destinazione', $destinazione]
                        ])
                        ->first();

                    if ($productpresente){
                        $productpresente->quantita += $quantita;
                        if($prova->nr_ordine) {
                            $productpresente->stato = 'prova';
                            $productpresente->id_prova = $id_prova;
                        }
                        $productpresente->save();
                    } else{
                        $nuovoproduct = new Product();
                        $nuovoproduct->matricola = $product->matricola;
                        $nuovoproduct->quantita = $quantita;
                        if($prova->nr_ordine) {
                            $nuovoproduct->stato = 'prova';
                            $nuovoproduct->id_prova = $id_prova;
                        }
                        $nuovoproduct->destinazione = $destinazione;
                        $nuovoproduct->id_listino = $product->id_listino;

                        $nuovoproduct->save();
                    }
                }else{
                    dd('errore');
                }
            }else{
                if($prova->nr_ordine) {
                    $product->stato = 'prova';
                    $product->id_prova = $id_prova;
                }
                $product->destinazione = $destinazione;
                $product->save();
            }

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha assegnato il prodotto '.$product->listi->descrizione.' con matricola:  '.$product->matricola.' - alla prova con numero ordine: '.$product->id_prova;
            Log::Info($messaggio);
        }


        $audio = $prova->audio->name;
        $magazzino = $prova->audio->magazzino;
        if($prova->nr_ordine){
            $nr_ordine = $prova->nr_ordine;
            $cliente = $prova->client->nome.' '.$prova->client->cognome;
            $indirizzo = $prova->client->indirizzo;
            $citta = $prova->client->citta;
            $provincia = $prova->client->provincia;
            $cap = $prova->client->cap;
            $telefono = $prova->client->telefono;
            $cod_fisc = $prova->client->cod_fisc;

            $prova->stato = 'provacorso';
            $prova->save();

        }else{
            $nr_ordine = "//////";
            $cliente = "//////";
            $indirizzo = "//////";
            $citta = "//////";
            $provincia = "//////";
            $cap = "//////";
            $telefono = "//////";
            $cod_fisc = "//////";
        }

        $data = $prova->created_at->format('dmY');
        $datafirma = Carbon::now()->format('d/m/Y');



        $pdf = App::make('dompdf.wrapper');

        if($prova->nr_ordine) {
            $nomefile = $cliente.'-'.$data;
            $cartellaesiste = "storage/copiecomm/".$destinazione;

            if(!File::isDirectory($cartellaesiste)){
                File::makeDirectory($cartellaesiste, 0777, true, true);
            }

            $pdf->loadView('pdf.copiacomm',
                [
                    'audio' => $audio,
                    'magazzino' => $magazzino,
                    'cliente' => $cliente,
                    'prodottiprova' => $prova->prodottiprova,
                    'provaprodotti' => $prova->provaprodotti,
                    'datafirma' => $datafirma,
                    'nr_ordine' => $nr_ordine,
                    'indirizzo' => $indirizzo,
                    'citta' => $citta,
                    'cap' => $cap,
                    'provincia' => $provincia,
                    'telefono' => $telefono,
                    'cod_fisc' => $cod_fisc
                ])->save("storage/copiecomm/$magazzino/$nomefile.pdf");

            if ($product->quantita == '0'){
                $product->delete();
                $messaggio = 'Prodotto '.$product->listi->descrizione.' esaurito';
                session()->flash('messagefineprodotto', $messaggio);
            }

            return $file = "/storage/copiecomm/$magazzino/$nomefile.pdf";
        }else{
            $nomefile = $magazzino.'-'.$data;
            $fullfile = $destinazione."/".$nomefile;
            $cartellaesiste = "storage/ddt/".$destinazione;

            if(!File::isDirectory($cartellaesiste)){
                File::makeDirectory($cartellaesiste, 0777, true, true);
            }

            $pdf->loadView('pdf.copiacomm',
                [
                    'audio' => $audio,
                    'magazzino' => $magazzino,
                    'cliente' => $cliente,
                    'prodottiprova' => $prova->prodottiprova,
                    'provaprodotti' => $prova->provaprodotti,
                    'datafirma' => $datafirma,
                    'nr_ordine' => $nr_ordine,
                    'indirizzo' => $indirizzo,
                    'citta' => $citta,
                    'cap' => $cap,
                    'provincia' => $provincia,
                    'telefono' => $telefono,
                    'cod_fisc' => $cod_fisc
                ])->save("storage/ddt/$fullfile.pdf");

            ProvaProdotti::where('id_prova', $id_prova)->delete();
            $prova->delete();

            if ($product->quantita == '0'){
                $product->delete();
                $messaggio = 'Prodotto '.$product->listi->descrizione.' esaurito';
                session()->flash('messagefineprodotto', $messaggio);
            }

            return $file = "/storage/ddt/$fullfile.pdf";
        }

    }
/*
    public function assegnaDestinazione(Request $request)
    {
        $id_prodotti = $request->input('prodotti');
        $destinazione = $request->input('destinazione');
        $listaprodotti = array();

        foreach ($id_prodotti as $item){
            $product = Product::find($item);
            $product->destinazione = $destinazione;
            $product->save();

            array_push($listaprodotti, $product);

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha assegnato il prodotto '.$product->listi->descrizione.' con matricola:  '.$product->matricola.' - al magazzino: '.$product->destinazione;
            Log::Info($messaggio);
        }

        $filiale = Filiale::where('nome', $destinazione)->first();

        $audio = '';
        $magazzino = $destinazione;
        $nr_ordine = '//////';
        $cliente = $destinazione;
        $indirizzo = $filiale->indirizzo;
        $citta = $filiale->citta;
        $provincia = $filiale->provincia;
        $cap = $filiale->cap;
        $telefono = '';
        $cod_fisc = '';
        $data = Carbon::now()->format('dmYhms');
        $datafirma = Carbon::now()->format('d/m/Y');

        $nomefile = $cliente.'-'.$data;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.ddt',
            [
                'audio' => $audio,
                'magazzino' => $magazzino,
                'cliente' => $cliente,
                'prodottiprova' => $listaprodotti,
                'datafirma' => $datafirma,
                'nr_ordine' => $nr_ordine,
                'indirizzo' => $indirizzo,
                'citta' => $citta,
                'cap' => $cap,
                'provincia' => $provincia,
                'telefono' => $telefono,
                'cod_fisc' => $cod_fisc
            ])->save("ddt/$nomefile.pdf");

        return "/ddt/$nomefile.pdf";
    }*/

    public function import()
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha importato il file prodotti.xlsx';
        Log::Info($messaggio);

        Excel::import(new ProductImport, 'product.xlsx');
        return redirect()->route('products.index');
    }
}

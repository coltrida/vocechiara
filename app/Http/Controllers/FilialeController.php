<?php

namespace App\Http\Controllers;

use App\Models\Filiale;
use function auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Log;

class FilialeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('filiali.filiali');
    }

    public function getFiliali()
    {
        $filiali = Filiale::select(['id','nome','indirizzo','citta','cap','provincia'])->orderBy('nome')->get();
        $result = DataTables::of($filiali)
            ->addColumn('Azioni', function ($filiale) {
                return '<div style="display: flex; justify-content: center">
                <a title="elimina" id="del'.$filiale->id.'" href="'.route('filiale.destroy', $filiale->id).'" class="btn btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$filiale->id.'" href="'.route('filiale.edit', $filiale->id).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                
            </div>';
            })
            ->rawColumns(['Azioni'])   /*per renderizzare html nel datatable, serve rawColumns e la lista delle colonne*/

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
        $filiale = new Filiale();
        return view('filiali.editfiliale', compact('filiale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $filiale = new Filiale();
        $filiale->fill($request->all());
        $res = $filiale->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato la filiale: '.$filiale->nome;
        Log::Info($messaggio);

        $messaggio = $res ? 'Filiale inserita correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('filiale.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Filiale $filiale)
    {
        return view('filiali.editfiliale', compact('filiale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Filiale $filiale)
    {
        $filiale->fill($request->all());
        $res = $filiale->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha modificato la filiale: '.$filiale->nome;
        Log::Info($messaggio);

        $messaggio = $res ? 'Filiale aggiornata correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('filiale.edit', ['id' => $filiale->id]);
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
}

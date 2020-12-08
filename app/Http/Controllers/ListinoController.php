<?php

namespace App\Http\Controllers;

use App\Models\Listino;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ListinoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('listino.listino');
    }

    public function getListino()
    {
        $listino = Listino::select(['id','categoria','descrizione','costo','prezzolistino','iva'])->orderBy('descrizione')->get();

        $result = DataTables::of($listino)
            ->addColumn('Azioni', function ($list) {

                    return '<div style="display: flex; justify-content: center">
                <a title="elimina" id="del'.$list->id.'" href="'.route('listino.destroy', $list->id).'" class="btn-sm btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$list->id.'" href="'.route('listino.edit', $list->id).'" class="btn-sm  btn-primary"><i class="fas fa-edit"></i></a>
                
            </div>';

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
        $listino = new Listino();
        return view('listino.editlistino', compact('listino'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listino = new Listino();
        $listino->fill($request->all());
        $res = $listino->save();

        $messaggio = $res ? 'Prodotto inserito correttamente nel Listino' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('listino.index');
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
    public function edit(Listino $listino)
    {
        return view('listino.editlistino', compact('listino'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listino $listino)
    {
        $listino->fill($request->all());
        $res = $listino->save();
        $messaggio = $res ? 'Prodotto aggiornato correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('listino.edit', ['id' => $listino->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listino $listino)
    {
        $res = $listino->delete();
        return ''.$res;
    }

    public function getIdListino($descrizione)
    {
        $idListino = Listino::where('descrizione', $descrizione)->first();
        return $idListino->id;
    }

    public function getProdotti($categoria)
    {
        $prodotti = Listino::where('categoria', $categoria)->get();
        return $prodotti;
    }

    public function getPrezzo($descrizione)
    {
        $prodotto = Listino::where('descrizione', $descrizione)->first();
        return $prodotto;
    }
}

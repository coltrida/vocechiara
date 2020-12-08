<?php

namespace App\Http\Controllers;

use App\Models\Font;
use function auth;
use function dd;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Log;


class FontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('font.fonts');
    }

    public function getFonts()
    {
        $fonts = Font::select(['id','name','codfisc','citta','pec','univoco'])->orderBy('name')->get();
        $result = DataTables::of($fonts)
            ->addColumn('Azioni', function ($font) {
                return '<div style="display: flex; justify-content: center">
                <a title="elimina" id="del'.$font->id.'" href="'.route('fonts.destroy', $font->id).'" class="btn btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$font->id.'" href="'.route('fonts.edit', $font->id).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                
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
        $font = new Font();
        return view('font.editfont', compact('font'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $font = new Font();
        $font->fill($request->all());
        $res = $font->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato la fonte: '.$font->name;
        Log::Info($messaggio);

        $messaggio = $res ? 'Fonte inserita correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('fonts.index');
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
    public function edit(Font $font)
    {
        return view('font.editfont', compact('font'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Font $font)
    {
        $font->fill($request->all());
        $res = $font->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha modificato la fonte: '.$font->name;
        Log::Info($messaggio);

        $messaggio = $res ? 'Fonte aggiornata correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('fonts.edit', ['id' => $font->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Font $font)
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato la fonte: '.$font->name;
        Log::Info($messaggio);

        $res = $font->delete();
        return ''.$res;
    }
}

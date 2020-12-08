<?php

namespace App\Http\Controllers;

use App\Models\Filiale;
use App\Models\User;
use Illuminate\Http\Request;
use Log;
use Yajra\DataTables\DataTables;

class AudiopController extends Controller
{
    public function index()
    {
        return view('audio.audio');
    }

    public function getAudio()
    {
        $audios = User::orderBy('name')->get();
        $result = DataTables::of($audios)
            ->addColumn('Azioni', function ($audio) {
                return '<div style="display: flex; justify-content: center">
                <a title="elimina" id="del'.$audio->id.'" href="'.route('audio.delete', $audio->id).'" class="btn btn-danger"><i class="fa fa-trash" ></i></a> 
                    &nbsp; 
                <a title="modifica" id="mod'.$audio->id.'" href="'.route('audio.edit', $audio->id).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                
            </div>';
            })
            ->rawColumns(['Azioni'])   /*per renderizzare html nel datatable, serve rawColumns e la lista delle colonne*/

            ->make(true);

        return $result;
    }

    public function edit(User $user)
    {
        $filiali = Filiale::orderBy('nome')->get();
        return view('audio.editaudio', compact('user', 'filiali'));
    }

    public function update(Request $request, User $user)
    {
        $user->fill($request->all());
        $res = $user->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha modificato il dipendente: '.$user->name;
        Log::Info($messaggio);

        $messaggio = $res ? 'Utente aggiornato correttamente' : 'Errore di salvataggio';
        session()->flash('message', $messaggio);
        return redirect()->route('audio.edit', ['id' => $user->id]);
    }

    public function destroy(User $user)
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato il dipendente: '.$user->name;
        Log::Info($messaggio);

        $res = $user->delete();
        return ''.$res;
    }
}

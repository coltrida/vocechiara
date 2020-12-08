<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Note;
use function compact;
use function dd;
use Illuminate\Http\Request;
use Log;

class NoteController extends Controller
{
    public function notastore(Request $request)
    {
        if($request->input('nota')){
            $nota = new Note();
            $nota->testo = $request->input('nota');
            $nota->id_cliente = $request->input('id_cliente');
            $res = $nota->save();

            $messaggio = 'L\'utente: '.auth()->user()->name.' ha creato la nota: '.$nota->testo.' - per il cliente: '.$nota->client->nome.' '.$nota->client->cognome;
            Log::Info($messaggio);
        }
        return ''.$res;
    }

    public function getNotes(Client $client)
    {
       // dd($client->id);
        $note = Note::where('id_cliente', $client->id)->orderBy('created_at', 'DESC')->get();
        return compact('note');
    }

    public function eliminaNota(Note $nota)
    {
        $messaggio = 'L\'utente: '.auth()->user()->name.' ha eliminato la nota: '.$nota->testo.' - per il cliente: '.$nota->client->nome.' '.$nota->client->cognome;
        Log::Info($messaggio);

        $res = $nota->delete();
        return ''.$res;
    }
}

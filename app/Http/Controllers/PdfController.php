<?php

namespace App\Http\Controllers;

use App\Models\Fattura;
use App\Models\Prova;
use Carbon\Carbon;
use function dd;
use Illuminate\Http\Request;
use App;
use Log;
use File;

class PdfController extends Controller
{
    public function copiacomm(Prova $prova)
    {
        //dd($prova->provaprodotti);
        $audio = $prova->audio->name;
        $magazzino = $prova->audio->magazzino;
        $nr_ordine = $prova->nr_ordine;
        $cliente = $prova->client->nome.' '.$prova->client->cognome;
        $indirizzo = $prova->client->indirizzo;
        $citta = $prova->client->citta;
        $provincia = $prova->client->provincia;
        $cap = $prova->client->cap;
        $telefono = $prova->client->telefono;
        $cod_fisc = $prova->client->cod_fisc;
        $data = $prova->created_at->format('dmY');
        $datafirma = $prova->created_at->format('d/m/Y');

        $nomefile = $cliente.'-'.$data;

        $pdf = App::make('dompdf.wrapper');
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
            ])->save("copiecomm/$nomefile.pdf");

        return $pdf->stream("copiecomm/$nomefile.pdf");
    }

    /**
     * @param Prova $prova
     * @return mixed
     */
    public function fattura(Prova $prova)
    {
        //dd($prova);
        $magazzino = $prova->audio->magazzino;

        $prova->stato = 'fatturato';
        $res = $prova->save();

        $fattura = Fattura::where('id_prova', $prova->id)->first();
        $fattura->data_fattura = Carbon::now();
        $fattura->save();

        $messaggio = 'L\'utente: '.auth()->user()->name.' ha convertito in fattura la vendita per il cliente: '.$prova->client->nome.' '.$prova->client->cognome.' - '.$prova->client->cod_fisc.' - con numero ordine: '.$prova->nr_ordine;
        Log::Info($messaggio);

        $audio = $prova->audio->name;
        $nr_ordine = $prova->nr_ordine;
        $cliente = $prova->client->nome.' '.$prova->client->cognome;
        $indirizzo = $prova->client->indirizzo;
        $citta = $prova->client->citta;
        $provincia = $prova->client->provincia;
        $cap = $prova->client->cap;
        $telefono = $prova->client->telefono;
        $cod_fisc = $prova->client->cod_fisc;
        $data = $prova->created_at->format('dmY');
        $datafirma = Carbon::now()->format('d/m/Y');

        $nomefile = $prova->id;

        $cartellaesiste = "storage/fatture/".$magazzino;
        if(!File::isDirectory($cartellaesiste)){
            File::makeDirectory($cartellaesiste, 0777, true, true);
        }

        $pdf = App::make('dompdf.wrapper');
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
            ])->save("storage/fatture/$magazzino/$nomefile.pdf");

        return $pdf->stream("/storage/fatture/$magazzino/$nomefile.pdf");
    }

    public function ddt(Prova $prova)
    {
        //dd($prova);
        $audio = $prova->audio->name;
        $magazzino = $prova->audio->magazzino;
        $nr_ordine = $prova->nr_ordine;
        $cliente = $prova->client->nome.' '.$prova->client->cognome;
        $indirizzo = $prova->client->indirizzo;
        $citta = $prova->client->citta;
        $provincia = $prova->client->provincia;
        $cap = $prova->client->cap;
        $telefono = $prova->client->telefono;
        $cod_fisc = $prova->client->cod_fisc;
        $data = $prova->created_at->format('dmY');
        $datafirma = $prova->created_at->format('d/m/Y');

        $nomefile = $cliente.'-'.$data;

        $pdf = App::make('dompdf.wrapper');
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
            ])->save("copiecomm/$nomefile.pdf");

        return $file = "/copiecomm/$nomefile.pdf";
    }
}

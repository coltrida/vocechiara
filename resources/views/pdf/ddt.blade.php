<html>
<head>
    <style>
        table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        td{
            border: solid 1px black;
            padding: 6px;
        }
        legend{
            font-size: 12px;
            color: grey;
            font-style: oblique;
            position: absolute;
            top: -9px;
        }
        ul{
            font-size: 10px;
            color: blue;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="2">
                Vocechiara s.r.l. unipersonale<br>
                Via Cornelia, 2 (di fianco al mercato coperto)<br>
                47921 Rimini (RN)<br>
                Tel: 0541.54630<br>
            </td>
            <td>
                <a class="navbar-brand" href="#">
                    <img src="{{asset('img/logo.jpeg')}}" alt="logo" height="60">
                </a>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><legend>MAGAZZINO</legend>{{$magazzino}}</td>
            <td><legend>AUDIOPROTESISTA</legend>{{$audio}}</td>
            <td><legend>NUMERO ORDINE</legend>{{$nr_ordine}}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td><legend>CLIENTE</legend>{{$cliente}}</td>
            <td><legend>TELEFONO</legend>{{$telefono}}</td>
            <td><legend>INDIRIZZO</legend>{{$indirizzo}}</td>
            {{--<td>&nbsp;</td>--}}
        </tr>
        <tr>
            <td><legend>CITTA'</legend>{{$citta}}</td>
            <td><legend>PROVINCIA</legend>{{$provincia}}</td>
            <td><legend>CAP</legend>{{$cap}}</td>
        </tr>
        <tr>
            <td colspan="3"><legend>CODICE FISCALE</legend>{{$cod_fisc}}</td>
        </tr>
    </table>

    <table>
        <tr style="background-color: lightblue; color: blue; font-size: 10px">
            <td>ARTICOLO E DESCRIZIONE DEGLI OGGETTI ORDINATI</td>
            <td>QUANTITA'</td>
            <td>PREZZO IVA INCLUSA</td>
        </tr>
        @for ($i = 0; $i < count($prodottiprova); $i++)
            <tr>
                <td> {{$prodottiprova[$i]->listi->descrizione}}</td>
                <td>{{$prodottiprova[$i]->quantita}}</td>
                <td></td>
            </tr>
        @endfor
        <tr>
            <td> &nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td> &nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td> &nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div style="border: solid 1px black; width: 100%; height: 70px; margin-bottom: 20px">

            <p>Note:</p>

    </div>

    <table style="border: solid black 1px">
        <tr>
            <td colspan="2" style="background-color: lightblue; color: blue; font-size: 10px">
                LISTA DEI SERVIZI COMPRESI NELLA SUA SOLUZIONE UDITIVA
            </td>
        </tr>
        <tr>
            <td style="border: 0; font-size: 10px">
                L'ACCOGLIENZA E PRESA IN CARICO
                <ul>
                    <li>Anamnesi e valutazione dei bisogni</li>
                    <li>Bilancio protesico</li>
                    <li>Risultati garantiti (Prova in situazione reale. Garanzia Soddisfazione)</li>

                </ul>
            </td>
            <td style="border: 0; font-size: 10px">
                L'ADATTAMENTO E LA RIEDUCAZIONE UDITIVA IN TRE FASI
                <ul>
                    <li>Riabilitazione: regolazioni, comfort di ascolto, equilibrio stereofonico</li>
                    <li>Integrazione: miglioramento delle correzioni</li>
                    <li>Performance: regolazioni ottimizzate, miglioramento della sensazione sonora, localizzazione spaziale</li>

                </ul>
            </td>
        </tr>
        <tr>
            <td style="border: 0; font-size: 10px">
                LA PRESTAZIONE DI PROTESIZZAZIONE
                <ul>
                    <li>Presa delle impronte</li>
                    <li>Consegna delle sue protesi su misura</li>
                    <li>Taratura delle sue protesi</li>
                    <li>Aiuto nelle pratiche amministrative</li>

                </ul>
            </td>
            <td style="border: 0; font-size: 10px">
                IL CONTROLLO DELLA PROTESIZZAZIONE
                <ul>
                    <li>Il controllo dell'evoluzione dell'udito</li>
                    <li>L'ottimizzazione delle regolazioni in funzione di questa evoluzione</li>
                    <li>Il controllo regolare degli apparecchi</li>
                    <li>I consigli permanenti sulla manutenzione e sull'utilizzo</li>

                </ul>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>
                {{$datafirma}}
            </td>
            <td> &nbsp;</td>
        </tr>
    </table>

</body>
</html>
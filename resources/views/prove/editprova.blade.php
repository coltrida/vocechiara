@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-10 offset-md-1">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

                <h1>Inserisci Prova</h1>
                <form>

                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

                    <input type="hidden" value="prova" name="stato" id="stato">
                    <input type="hidden" value="{{$client->id}}" name="id_cliente" id="id_cliente">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" readonly value="{{$client->nome.' '.$client->cognome}}"
                                class="form-control" placeholder="" aria-describedby="helpId">
                    </div>

                    <div style="display: flex; justify-content: space-between">
                        <div class="custom-control custom-checkbox" style="margin-bottom: 30px">
                            <input type="checkbox" class="custom-control-input" id="matricoleCheck">
                            <label class="custom-control-label" for="matricoleCheck">Inserisco le matricole</label>
                        </div>
                        <div>
                            <a id="inserisciprodottoBtn" data-number="0" class="btn btn-primary">Inserisci Prodotto</a>
                        </div>
                    </div>

                    <div class="form-group" id="listaprodotti">
                    </div>

                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('clients.index')}}" class="btn btn-info">INDIETRO</a>
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">SAVE</button>
                    </div>

                </form>

        </div>

    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <form>
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <div id="listaprove" class="table-responsive">
                    <table class="table table-striped text-nowrap" id="prove_table" style="width:100%;">
                        <thead>
                        <tr>
                            <th style="text-align: center">Azioni</th>
                            <th>NR. ORDINE</th>
                            <th>TOTALE</th>
                            <th>DATA</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px">
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    @include('prove.partials.infoProvaModal')
    @include('prove.partials.infoPagamentoModal')
@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto
                CaricaProve($('#id_cliente').val());

                // ---------------------- CARICA PROVE ---------------------------------------//
                function CaricaProve(idCliente) {
                    $('#prove_table tbody').html('');
                    var prelinkprove = '{{ route('prove.getProve', ":id") }}';
                    var linkprove = prelinkprove.replace(':id', idCliente);

                    //alert(linknote);
                    $.ajax(linkprove,
                        {
                            method: 'GET',
                            complete : function (resp) {
                               // console.log(resp.responseJSON);
                                if((resp.responseJSON.prove).length > 0){
                                    for (var k = 0; k < (resp.responseJSON.prove).length; k++){
                                        var idprova = resp.responseJSON.prove[k].id;
                                        var idvendita = 'v'+resp.responseJSON.prove[k].id;
                                        var totale = resp.responseJSON.prove[k].tot;
                                        var nr_ordine = resp.responseJSON.prove[k].nr_ordine;
                                        var selezionato = resp.responseJSON.prove[k].created_at;
                                        var giorno = $.format.date(selezionato, "dd-MM-yyyy");
                                        var stato = resp.responseJSON.prove[k].stato;

                                        var PrimaColonna = '';
                                        var infobtn = '&nbsp;<a title="info" href="#" id="'+idprova+'" class="btn-sm  btn-success info" data-toggle="modal" data-target="#infoProva"><i class="fas fa-info-circle"></i></a>';

                                        if(stato == 'prova' || stato == 'provacorso') {
                                            var prelinkvendita = '{{ route('prove.vendita', ":idprova") }}';
                                            var linkvendita = prelinkvendita.replace(':idprova', idprova);
                                            var prelinkreso = '{{ route('prove.reso', ":idprova") }}';
                                            var linkreso = prelinkreso.replace(':idprova', idprova);
                                            var prelinkannulla = '{{ route('prove.annulla', ":idprova") }}';
                                            var linkannulla = prelinkannulla.replace(':idprova', idprova);
                                            var prelinkcc = '{{ route('prove.copiacomm', ":idprova") }}';
                                            var linkcc = prelinkcc.replace(':idprova', idprova);
                                            var prelinkbolla = '{{ route('prove.ddt', ":idprova") }}';
                                            var linkbolla = prelinkbolla.replace(':idprova', idprova);

                                            var PrimaSemiColonna = '';
                                            if (resp.responseJSON.prove[k].products.length > 0) {
                                                PrimaSemiColonna = '<a title="Vendita" href="'+linkvendita+'" class="btn-sm btn-success vendita" id="'+idvendita+'" data-toggle="modal" data-target="#infoPagamento"><i class="fa fa-dollar-sign" ></i></a>&nbsp;<a title="Reso" href="'+linkreso+'" class="btn-sm btn-danger reso"><i class="fa fa-trash" ></i></a>&nbsp;';
                                            }else {
                                                PrimaSemiColonna = '<a title="Annulla Prova" href="'+linkannulla+'" class="btn-sm btn-danger reso"><i class="fa fa-trash" ></i></a>&nbsp;';
                                            }

                                            PrimaColonna = PrimaSemiColonna+'<a target="_blank" title="Copia Commissione" href="'+linkcc+'" class="btn-sm btn-primary copiacomm"><i class="fas fa-pen-nib"></i></a>&nbsp;<a title="Bolla" href="'+linkbolla+'" class="btn-sm btn-primary bolla"><i class="fas fa-truck"></i></a>'+infobtn;
                                        } else if(stato == 'vendita') {
                                            PrimaColonna = 'Vendita'+infobtn;
                                        } else if(stato == 'reso') {
                                            PrimaColonna = 'Reso'+infobtn;
                                        } else if(stato == 'annullato') {
                                            PrimaColonna = 'Annullato'+infobtn;
                                        } else if(stato == 'fatturato') {
                                            PrimaColonna = 'Fatturato'+infobtn;
                                        }
                                        $('#prove_table tbody').append('<tr><td style="text-align:center">'+PrimaColonna+'</td><td >'+nr_ordine+'</td><td>'+totale+'</td><td>'+giorno+'</td></tr>');

                                    }
                                }
                            }
                        })
                }
                // ---------------------- FINE CARICA PROVE ---------------------------------------//


        // --------------------- pulsante NUOVA PROVA -----------------------------------//
        $('#save').on('click', function (ele) {
            ele.preventDefault();
            $('#save').attr('disabled', true);
            var numeroprodotti = $('#inserisciprodottoBtn').attr('data-number');
            var totaleprova = 0;
            var ids = [];
            var prezzi = [];
            var matricole = [];
            var quantita = [];
            for(var j = 1; j <= numeroprodotti; j++){
                ids[j-1] = $('#ele'+j).attr('data-title');
                prezzi[j-1] = $('#pre'+j).val();
                if ($('#mat'+j).val() != ''){
                    matricole[j-1] = $('#mat'+j).val();
                }
                if ($('#cat'+j).val() == 'CON'){
                    quantita[j-1] = parseInt($('#qta'+j).val());
                }else{
                    quantita[j-1] = 1;
                }
                totaleprova += parseInt($('#pre'+j).val()) * quantita[j-1];
                //alert(prezzi[j-1]+' - '+quantita[j-1]+' - '+totaleprova)
            }

            var urlProva = '{{route('prove.store')}}';
            $.ajax(urlProva,
                {
                    method: 'POST',
                    data : {
                        '_token' : $('#_token').val(),
                        'id_cliente' : $('#id_cliente').val(),
                        'tot' : totaleprova,
                        'ids[]' : ids,
                        'prezzi[]' : prezzi,
                        'quantita[]' : quantita,
                        'matricole[]' : matricole,
                        'stato' : 'prova'
                    },
                    complete : function (resp) {
                        console.log(resp);
                        if(resp.responseText == 1){
                            for (var i = 1; i <= numeroprodotti; i++){
                                $('#ele'+i).html('');
                            }
                            $('#inserisciprodottoBtn').attr('data-number',0);
                            CaricaProve($('#id_cliente').val());
                        }else{
                            alert('problemi');
                        }
                    }
                })
        });


        // --------------------- FINE pulsante NUOVA PROVA -----------------------------------//


                // --------------------------------  VERIFICA MATRICOLE -----------------------------------//
                $('#listaprodotti').on('change', '.matricola', function (e) {
                    $(this).find(":selected").each(function () {
                        var cerca = $(this).val();
                        var conta = 0;
                        //alert($(this).val());
                        $(".matricola").each(function() {
                            if (($(this).val() == cerca) && ($(this).val() !== null)){
                                conta = conta + 1;
                            }
                        });
                        //alert(conta);
                        if(conta>1){
                            alert('Le matricole devono essere univoche ');
                            $('#save').attr('disabled', true);
                        }else {
                            $('#save').attr('disabled', false);
                        }
                    });
                });
                // --------------------------------  FINE VERIFICA MATRICOLE -----------------------------------//

                // -------------------------------- CHECKBOX ABILITA MATRICOLE ------------------------//
                $("#matricoleCheck").click( function(){
                    if( $(this).is(':checked') ) {
                        $('.matricola').attr('disabled', false);
                    }else {
                        $('.matricola').attr('disabled', true);
                    }
                });
                // -------------------------------- FINE CHECKBOX ABILITA MATRICOLE  ------------------//

                // --------------------- pulsante VENDITA -----------------------------------//
                $('#prove_table').on('click', 'a.vendita', function (ele) {
                    ele.preventDefault();
                    var idprova = ($(this).attr('id')).substring(1);
                    $('#prova').val(idprova);
                    var urlProva = ($(this).attr('href'));
                    //alert(urlProva);
                    $.ajax(urlProva,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                console.log(resp);
                                if(resp.responseText == 1){
                                    CaricaProve($('#id_cliente').val());
                                }else{
                                    if (resp.responseText == 2){
                                        alert('Matricola mancante');
                                    }else {
                                        alert('problemi');
                                    }

                                }
                            }
                        })
                });


                // --------------------- FINE pulsante VENDITA -----------------------------------//

                // --------------------- pulsante RESO -----------------------------------//
                $('#prove_table').on('click', 'a.reso', function (ele) {
                    ele.preventDefault();

                    var urlProva = ($(this).attr('href'));
                    //alert(urlProva);
                    $.ajax(urlProva,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                console.log(resp);
                                if(resp.responseText == 1){
                                    CaricaProve($('#id_cliente').val());
                                }else{
                                    alert('problemi');
                                }
                            }
                        })
                });


                // --------------------- FINE pulsante RESO -----------------------------------//

                // --------------------- pulsante ASSEGNA PAGAMENTI -----------------------------------//
                $('#assegnaPagamento').on('click', function (ele) {
                    ele.preventDefault();

                    var urlAssegna = ($('#pagamentiForm').attr('action'));
                    //alert($('#_token').val());

                    $.ajax(urlAssegna,
                        {
                            method: 'POST',
                            data : {
                                '_token' : $('#_token').val(),
                                'id_prova' : $('#prova').val(),
                                'acconto' : $('#acconto').val(),
                                'nr_rate' : $('#rate').val()
                            },
                            complete : function (resp) {
                                console.log(resp.responseText);
                                if(resp.responseText == 1){
                                    $('#infoPagamento').modal('toggle');;
                                }else{
                                    alert('problemi');
                                }
                            }
                        })
                });


                // --------------------- FINE pulsante ASSEGNA PAGAMENTI -----------------------------------//

                // --------------------- pulsante INFO -----------------------------------//
                $('#prove_table').on('click', 'a.info', function (ele) {
                    var idsel = $(this).attr('id');
                    $('#prove_table_info tbody').html('');
                    var prelinkinfo = '{{ route('prove.info', ":idprova") }}';
                    var linkinfo = prelinkinfo.replace(':idprova', idsel);
                    $.ajax(linkinfo,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                for (var d = 0; d < (resp.responseJSON.prodottiprova).length; d++){
                                    var categoria = resp.responseJSON.provaprodotti[d].categoria;
                                    var descrizione = resp.responseJSON.provaprodotti[d].descrizione;
                                    var iva = resp.responseJSON.provaprodotti[d].iva;
                                    var prezzo = resp.responseJSON.prodottiprova[d].prezzo;
                                    if (resp.responseJSON.products.length > 0) {
                                        var matricola = resp.responseJSON.products[d].matricola;
                                    }else {
                                        var matricola = '';
                                    }

                                    $('#prove_table_info tbody').append('<tr><td style="text-align:center">'+categoria+'</td><td >'+descrizione+'</td><td>'+prezzo+'</td><td>'+iva+'</td><td>'+matricola+'</td></tr>');
                                }
                            }
                        })
                });


                // --------------------- FINE pulsante INFO -----------------------------------//

                // --------------------- pulsante ELIMINA PRODOTTO -----------------------------------//
                $('#listaprodotti').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();
                    var riga = this.parentNode.parentNode;
                    riga.parentNode.removeChild(riga);
                    var numero = parseInt($('#inserisciprodottoBtn').attr('data-number')) - 1;
                    $('#inserisciprodottoBtn').attr('data-number', numero);
                    //alert($('#inserisciprodottoBtn').attr('data-number'));
                });


                // --------------------- FINE ELIMINA PRODOTTO -----------------------------------//


                // --------------------- PULSANTE INSERISCI PRODOTTO---------------------------//

                $('#inserisciprodottoBtn').on('click', function (ele) {
                    ele.preventDefault();
                    $('#save').attr('disabled', false);
                    var numero = parseInt($('#inserisciprodottoBtn').attr('data-number')) + 1;
                    $('#inserisciprodottoBtn').attr('data-number', numero);

                    if($('#matricoleCheck').is(':checked')){
                        var matricolaselect = '<div style="width: 25%"> <label id="label'+numero+'" for="mat'+numero+'">Matricola</label><span id="cella'+numero+'"><select id="mat'+numero+'" name="mat[]" class="form-control matricola" ><option></option></select></span></div>'
                    }else{
                        var matricolaselect = '<div style="width: 25%"> <label id="label'+numero+'" for="mat'+numero+'">Matricola</label><span id="cella'+numero+'"><select disabled id="mat'+numero+'" name="mat[]" class="form-control matricola" ><option></option></select></span></div>'
                    }

                    $('#listaprodotti').append('<div class="rigasel" id="ele'+numero+'" data-title="" style="display: flex; justify-content: space-between"><div style="width: 5%" class="row align-items-end rigadel"><a href="#" class="btn-sm btn-danger "><i class="fas fa-times-circle"></i></a></div><div style="width: 10%"><label for="cat'+numero+'">Cat.</label><select id="cat'+numero+'" name="cat[]" class="form-control categoria"><option></option><option>APA</option><option>ACC</option><option>CON</option></select></div><div style="width: 35%"><label for="des'+numero+'">Prodotto</label><select id="des'+numero+'" name="des[]" class="form-control descrizione" ><option></option></select></div>'+ matricolaselect +'<div style="width: 15%"><label for="pre'+numero+'">PREZZO</label><input type="text" name="pre[]" id="pre'+numero+'" class="form-control" placeholder="" aria-describedby="helpId"></div></div>');

                    $('.categoria').change( function() {
                        $(this).find(":selected").each(function () {
                            var idcat = $(this).parent().attr('id');
                            var idpro = 'des'+idcat.substring(3);

                           $('#'+idcat).attr('disabled', 'true');
                            var valorecat = $(this).val();
                            var label = 'label'+idcat.substring(3);
                            var cellaselect = 'cella'+idcat.substring(3);
                            var num = idcat.substring(3);
                            //alert(num);
                            //$('#'+cellaselect).html('');

                            if (valorecat == 'CON'){
                                $('#'+label).html('Quantità');
                                $('#'+cellaselect).html('<input type="text" name="qta[]" id="qta'+num+'" class="form-control" placeholder="" aria-describedby="helpId">');
                            } else {
                                $('#'+label).html('Matricola');
                                if($('#matricoleCheck').is(':checked')) {
                                    $('#' + cellaselect).html('<select id="mat' + num + '" name="mat[]" class="form-control matricola" ><option></option></select>');
                                }else{
                                    $('#' + cellaselect).html('<select disabled id="mat' + num + '" name="mat[]" class="form-control matricola" ><option></option></select>');
                                }
                            }

                            var prelinkprodotti = '{{ route('listino.getProdotti', ":categoria") }}';
                            var linkprodotti = prelinkprodotti.replace(':categoria', valorecat);

                            $('#'+idpro).html('');
                            $('#'+idpro).append('<option></option>');

                            $.ajax(linkprodotti,
                                {
                                    method: 'GET',
                                    complete : function (resp) {
                                        //console.log(resp.responseJSON);
                                        for (var k = 0; k < resp.responseJSON.length; k++){
                                            var valores = resp.responseJSON[k].descrizione;
                                            $('#'+idpro).append('<option>'+valores+'</option>');
                                        }

                                        $('.descrizione').change( function() {
                                            $(this).find(":selected").each(function () {
                                                var idmatr = 'mat'+idpro.substring(3);
                                                var idpre = 'pre'+idpro.substring(3);
                                                var idriga = 'ele'+idpro.substring(3);
                                                var iddes = 'des'+idpro.substring(3);
                                                var valoredesc = $(this).val();
                                                $('#'+iddes).attr('disabled', 'true');
                                                //alert(idriga);
                                                var precaricaprezzo = '{{ route('listino.getPrezzo', ":descrizione") }}';
                                                var caricaprezzo = precaricaprezzo.replace(':descrizione', valoredesc);

                                                $('#'+idpre).html('');

                                                $.ajax(caricaprezzo,
                                                    {
                                                        method: 'GET',
                                                        complete: function (resp) {
                                                            $('#'+idpre).val(resp.responseJSON.prezzolistino);

                                                            var idlistino = resp.responseJSON.id;
                                                            $('#'+idriga).attr('data-title', idlistino);

                                                            if($('#matricoleCheck').is(':checked')){
                                                                var precaricamatricole = '{{ route('prove.getMatricolePresenti', ":id") }}';
                                                                var caricamatricole = precaricamatricole.replace(':id', idlistino);

                                                                $('#'+idmatr).html('');
                                                                $('#'+idmatr).append('<option></option>');


                                                                $.ajax(caricamatricole,
                                                                    {
                                                                        method: 'GET',
                                                                        complete: function (resp) {
                                                                           // console.log(resp.responseJSON)
                                                                            for (var i = 0; i < resp.responseJSON.matricole.length; i++){
                                                                                var valores = resp.responseJSON.matricole[i].matricola;
                                                                                $('#'+idmatr).append('<option>'+valores+'</option>');
                                                                            }
                                                                        }
                                                                    })
                                                            }
                                                        }
                                                    })
                                            })
                                        })
                                    }
                                })

                        });
                    });
                });
                // --------------------- FINE PULSANTE INSERISCI PRODOTTO---------------------------//

            }

        )

    </script>

@endsection

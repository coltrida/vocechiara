@extends('layouts.template-admin')

@section('container')
<h1>Home</h1>
<div class="container">
    <div class="row">
        <div class="col-sm table-responsive" style="border: solid 1px gray; height: 400px">

            @if(session()->has('messagefineprodotto'))
                <div id="messaggiofine" class="alert alert-info" role="alert">
                    <strong>{{session('messagefineprodotto')}}</strong>
                </div>
            @endif

            <h4>Da Spedire</h4>
            <table class="table table-striped text-nowrap" id="spedizioni_table">
                <thead>
                <tr>
                    <th style="width: 15%; text-align: center">Azioni</th>
                    <th>Audioprotesista</th>
                    <th>Cliente</th>
                    <th>Nr. Ordine</th>
                    <th>Totale</th>
                    <th>Data Prova</th>

                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-sm table-responsive" style="border: solid 1px gray; height: 400px">
            <h4>Da Fatturare</h4>
            <table class="table table-striped text-nowrap" id="fatture_table">
                <thead>
                <tr>
                    <th style="width: 15%; text-align: center">Azioni</th>
                    <th>Audioprotesista</th>
                    <th>Cliente</th>
                    <th>Nr. Ordine</th>
                    <th>Totale</th>
                    <th>Data Vendita</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

@include('home.partials.infoProvaModal')
@include('home.partials.infofatturaModal')

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                var tableSpedizioni = $('#spedizioni_table').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('home.getSpedizioni')}}',
                    columns: [
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false},
                        {data: 'id_audio', name: 'id_audio'},
                        {data: 'id_cliente', name: 'id_cliente'},
                        {data: 'nr_ordine', name: 'nr_ordine'},
                        {data: 'tot', name: 'tot'},
                        {data: 'created_at', name: 'created_at'}
                    ]
                });          // -------------------------------fine lettura dati datatable ---------------------

                setInterval( function () {
                    tableSpedizioni.ajax.reload( null, false ); // user paging is not reset on reload
                }, 300000 );

                var tablefatture = $('#fatture_table').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('home.getFatture')}}',
                    columns: [
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false},
                        {data: 'id_audio', name: 'id_audio'},
                        {data: 'id_cliente', name: 'id_cliente'},
                        {data: 'nr_ordine', name: 'nr_ordine'},
                        {data: 'tot', name: 'tot'},
                        {data: 'created_at', name: 'created_at'}
                    ]
                });          // -------------------------------fine lettura dati datatable ---------------------

                setInterval( function () {
                    tablefatture.ajax.reload( null, false ); // user paging is not reset on reload
                }, 300000 );

                // --------------------- pulsante INFO -----------------------------------//
                $('#spedizioni_table').on('click', 'a.info', function (ele) {
                    ele.preventDefault();
                    $('#prove_table_info tbody').html('');
                    $('#magazzino').val($(this).attr('data-magazzino'));
                    var urlProva = ($(this).attr('href'));
                    //alert(urlProva);
                    $.ajax(urlProva,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                //console.log(resp.responseJSON);
                                $('#nr_prodotti').val((resp.responseJSON.prodottiprova).length);
                                $('#id_prova').val(resp.responseJSON.id);
                                for (var d = 0; d < (resp.responseJSON.prodottiprova).length; d++){
                                    var idlistino = resp.responseJSON.provaprodotti[d].id;
                                    var categoria = resp.responseJSON.provaprodotti[d].categoria;
                                    var descrizione = resp.responseJSON.provaprodotti[d].descrizione;
                                    var iva = resp.responseJSON.provaprodotti[d].iva;
                                    var quantita = resp.responseJSON.prodottiprova[d].quantita;
                                    var prezzo = resp.responseJSON.prodottiprova[d].prezzo;
                                    if(categoria == 'CON'){
                                        var matricole = '<input type="hidden" value="'+descrizione+'" name="mat[]" id="mat'+d+'"> ';
                                    }else{
                                        var matricole = '<select id="mat'+d+'" name="mat[]" class="form-control matricole" ><option></option></select>';
                                    }
                                    $('#prove_table_info tbody').append('<tr id="ele'+d+'"><td style="text-align:center" id="cat'+d+'">'+categoria+'</td><td >'+descrizione+'</td><td>'+prezzo+'</td><td>'+iva+'</td><td id="qta'+d+'">'+quantita+'</td><td>'+matricole+'</td></tr>');

                                    var prelinkmatricole = '{{ route('prove.getMatricole', ":id") }}';
                                    var linkmatricole = prelinkmatricole.replace(':id', idlistino);
                                    $.ajax(linkmatricole,
                                        {
                                            method: 'GET',
                                            async: false,
                                            complete: function (resp) {
                                                //console.log(e.target);
                                                for (var k = 0; k < resp.responseJSON.matricole.length; k++) {
                                                    var valores = resp.responseJSON.matricole[k].matricola;
                                                    $('#mat'+d).append('<option>' + valores + '</option>');
                                                }
                                            }
                                        });
                                }
                            }
                        });


                });
                // --------------------- FINE pulsante INFO -----------------------------------//


                // --------------------------------  VERIFICA MATRICOLE -----------------------------------//
                $('#prove_table_info').on('change', '.matricole', function (e) {
                    $(this).find(":selected").each(function () {
                        var cerca = $(this).val();
                        var conta = 0;
                        //alert($('select').val());
                        $(".matricole").each(function() {
                            if (($(this).val() == cerca) && ($(this).val() !== null)){
                                conta = conta + 1;
                            }
                        });
                        if(conta>1){
                            alert('Le matricole devono essere univoche ');
                            $('#assegna').attr('disabled', true);
                        }else {
                            $('#assegna').attr('disabled', false);
                        }
                    });
                });
                // --------------------------------  FINE VERIFICA MATRICOLE -----------------------------------//

                // --------------------- pulsante ASSEGNA MATRICOLA -----------------------------------//
                $('#assegna').on('click', function (ele) {
                    ele.preventDefault();
                    var matricole = [];
                    var quantita = [];
                    var numeroprodotti = $('#nr_prodotti').val();
                    for(var j = 0; j < numeroprodotti; j++){
                            matricole[j] = $('#mat'+j).val();
                            quantita[j] = $('#qta'+j).html();
                    }

                    var id_prova = $('#id_prova').val();
                    var destinazione = $('#magazzino').val();
                    var linkassegna = '{{ route('products.assegnaProva') }}';
                    $.ajax(linkassegna,
                        {
                            method: 'POST',
                            data : {
                                '_token' : $('#_token').val(),
                                'matricole[]' : matricole,
                                'quantita[]' : quantita,
                                'destinazione' : destinazione,
                                'id_prova' : id_prova
                            },
                            complete : function (resp) {
                                console.log(resp);
                                var prelink = '{{ route('index') }}';
                                var link = prelink+resp.responseText;
                                window.open(link);
                                window.location.reload();
                            }
                        })

                });
                // --------------------- FINE pulsante ASSEGNA MATRICOLA -----------------------------------//

                // --------------------- pulsante INFOFATTURA -----------------------------------//
                $('#fatture_table').on('click', 'a.infofattura', function (ele) {
                    ele.preventDefault();
                    $('#prove_table_fattura tbody').html('');
                    var urlProva = ($(this).attr('href'));
                    //alert(urlProva);
                    $.ajax(urlProva,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                //console.log(resp.responseJSON);
                                for (var d = 0; d < (resp.responseJSON.prodottiprova).length; d++){
                                    var idlistino = resp.responseJSON.provaprodotti[d].id;
                                    var categoria = resp.responseJSON.provaprodotti[d].categoria;
                                    var descrizione = resp.responseJSON.provaprodotti[d].descrizione;
                                    var iva = resp.responseJSON.provaprodotti[d].iva;
                                    var prezzo = resp.responseJSON.prodottiprova[d].prezzo;
                                    var matricole = resp.responseJSON.products[d].matricola;

                                    $('#prove_table_fattura tbody').append('<tr id="ele'+d+'"><td style="text-align:center">'+categoria+'</td><td >'+descrizione+'</td><td>'+prezzo+'</td><td>'+iva+'</td><td>'+matricole+'</td></tr>');
                                }
                            }
                        });


                });
                // --------------------- FINE pulsante INFOFATTURA -----------------------------------//

                // --------------------- pulsante INFOFATTURA -----------------------------------//
                $('#fatture_table').on('click', 'a.fattura', function (ele) {
                    window.location.reload();
                });
                // --------------------- fine pulsante INFOFATTURA -----------------------------------//
            }
        )
    </script>
@endsection

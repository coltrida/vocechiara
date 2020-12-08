@extends('layouts.template-admin')

@section('container')
<h1>Home</h1>
<div class="container">
    <div class="row">
        <div class="col-sm table-responsive" style="border: solid 1px gray; height: 400px">
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
                @foreach($spedizioni as $item)
                    <tr>
                        <form action="{{route('prove.ddt')}}">
                            <td>
                                <div style="display: flex; justify-content: center">
                                    <a title="DDT" href="{{--{{route('clients.destroy.call', $item->id)}}--}}" class="btn btn-primary"><i class="fas fa-truck-moving"></i></a>&nbsp;<a title="info" href="{{route('prove.info', $item->id)}}" data-magazzino="{{$item->audio->magazzino}}" class="btn btn-primary info" data-toggle="modal" data-target="#infoProva"><i class="fas fa-search"></i></a>
                                </div>
                            </td>
                            <td>{{$item->audio->name}}</td>
                            <td>{{$item->client->nome.' '.$item->client->cognome}}</td>
                            <td>{{$item->nr_ordine}}</td>
                            <td>{{$item->tot}}</td>
                            <td>{{$item->created_at->format('d/m/Y')}}</td>

                        </form>
                    </tr>
                @endforeach
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
                @foreach($dafatturare as $item)
                    <tr>
                        <td>
                            <div style="display: flex; justify-content: center">
                                <a title="Fattura" href="{{--{{route('clients.destroy.call', $item->id)}}--}}" class="btn btn-success"><i class="fas fa-shopping-bag"></i></a>&nbsp;<a title="info" href="{{route('prove.info', $item->id)}}" class="btn btn-primary infofattura" data-toggle="modal" data-target="#infoFattura"><i class="fas fa-search"></i></a>

                            </div>
                        </td>
                        <td>{{$item->audio->name}}</td>
                        <td>{{$item->client->nome.' '.$item->client->cognome}}</td>
                        <td>{{$item->nr_ordine}}</td>
                        <td>{{$item->tot}}</td>
                        <td>{{$item->updated_at->format('d/m/Y')}}</td>

                    </tr>
                @endforeach
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

                // --------------------------------  AGGIORNAMENTO PAGINA -----------------------------------//
                setInterval(function() {
                    window.location.reload();
                }, 300000);
                // --------------------------------  FINE AGGIORNAMENTO PAGINA -----------------------------------//

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
                                    var prezzo = resp.responseJSON.prodottiprova[d].prezzo;
                                    var matricole = '<select id="mat'+d+'" name="mat[]" class="form-control matricole" ><option></option></select>';
                                    $('#prove_table_info tbody').append('<tr id="ele'+d+'"><td style="text-align:center">'+categoria+'</td><td >'+descrizione+'</td><td>'+prezzo+'</td><td>'+iva+'</td><td>'+matricole+'</td></tr>');

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
                    var numeroprodotti = $('#nr_prodotti').val();
                    for(var j = 0; j < numeroprodotti; j++){
                        if ($('#mat'+j).val() != ''){
                            matricole[j] = $('#mat'+j).val();
                        }
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
                                'destinazione' : destinazione,
                                'id_prova' : id_prova
                            },
                            complete : function (resp) {
                                console.log(resp);
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

            }
        )
    </script>
@endsection

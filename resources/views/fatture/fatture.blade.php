@extends('layouts.template-admin')

@section('container')
<h2>{{$filiale->nome}}</h2>
    <table class="table table-striped" id="fattureTable">
        <thead>
            <tr>
                <th>Audioprotesista</th>
                <th>Cliente</th>
                <th>Nr. Ordine</th>
                <th>Tot. Fattura</th>
                <th>Data Fattura</th>
                <th style="width: 15%; text-align: center">Azioni</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    @include('fatture.partials.infoFatturaModal')
@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#fattureTable').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('fatture.getFatture', $filiale->id)}}',
                    columns: [
                        {data: 'id_audio', name: 'id_audio'},
                        {data: 'id_cliente', name: 'id_cliente'},
                        {data: 'nr_ordine', name: 'nr_ordine'},
                        {data: 'tot', name: 'tot'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false}
                    ]
                });          // -------------------------------fine lettura dati datatable ---------------------


                // --------------------- pulsante INFO -----------------------------------//
                $('#fattureTable').on('click', 'a.info', function (ele) {
                    $('#fatture_info tbody').html('');
                    
                    ele.preventDefault();

                    var urlinfoClient = ($(this).attr('href'));
                    //alert(urlinfoClient);
                    $.ajax(urlinfoClient,
                        {
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                for (var d = 0; d < (resp.responseJSON.prodottiprova).length; d++){
                                    var categoria = resp.responseJSON.provaprodotti[d].categoria;
                                    var descrizione = resp.responseJSON.provaprodotti[d].descrizione;
                                    var iva = resp.responseJSON.provaprodotti[d].iva;
                                    var prezzo = resp.responseJSON.prodottiprova[d].prezzo;
                                    var quantita = resp.responseJSON.prodottiprova[d].quantita;
                                    if (resp.responseJSON.products.length > 0) {
                                        var matricola = resp.responseJSON.products[d].matricola;
                                    }else {
                                        var matricola = '';
                                    }

                                    $('#fatture_info tbody').append('<tr><td style="text-align:center">'+categoria+'</td><td >'+descrizione+'</td><td>'+prezzo+'</td><td>'+quantita+'</td><td>'+iva+'</td><td>'+matricola+'</td></tr>');
                                }

                            }
                        });



                });
                // --------------------- FINE pulsante INFO -----------------------------------//
            }

        )

    </script>

@endsection
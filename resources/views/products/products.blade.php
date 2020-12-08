@extends('layouts.template-admin')

@section('container')
    <form id="assegnaForm" action="#{{--{{route('products.assegnaDestinazione')}}--}}" method="POST">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                &nbsp;
            </div>
            {{--<div class="col-sm">
                @if(auth()->user()->isCarla())
                    <select class="custom-select destinazione" name="destinazione" id="destinazione">
                        <option selected></option>
                        @foreach($filiali as $item)
                            <option>{{$item->nome}}</option>
                        @endforeach
                    </select>
                @endif
            </div>--}}
            <div class="col-sm">
                &nbsp;
            </div>
        </div>
    </div>
        <table class="table table-striped display nowrap" id="products_table" style="width:100%;">
            <thead>
                <tr>
                    <th style="text-align: center">Azioni</th>
                    <th>Stato</th>
                    <th>Categoria</th>
                    <th>Matricola</th>
                    <th>Descrizione</th>
                    <th>Magazzino</th>
                    <th>Quantità</th>
                    <th>Costo</th>
                    <th>Prezzo</th>
                    <th>IVA</th>
                    {{--<th>Cliente</th>--}}


                </tr>
            </thead>
            <tbody style="font-size: 14px">
            </tbody>
            <tfoot>
            <tr>
                <th style="text-align: center">&nbsp;</th>
                <th>Stato</th>
                <th>Categoria</th>
                <th>Matricola</th>
                <th>Descrizione</th>
                <th>Magazzino</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
    {{--            <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Cliente</th>--}}

            </tr>
            </tfoot>
        </table>
    </form>
 {{--   </form>--}}

    @include('products.partials.infoProductProvaModal')

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#products_table').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    /*"lengthChange": false,*/
                    "scrollX": true,
                    ajax: '{{route('products.getProducts')}}',
                    columns: [
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false},
                        {data: 'stato', name: 'stato'},
                        {data: 'categoria', name: 'categoria'},
                        {data: 'matricola', name: 'matricola'},
                        {data: 'id_listino', name: 'id_listino'},
                        {data: 'destinazione', name: 'destinazione'},
                        {data: 'quantita', name: 'quantita'},
                        {data: 'costo', name: 'costo'},
                        {data: 'prezzo', name: 'prezzo'},
                        {data: 'iva', name: 'iva'}
                    ],
                    initComplete: function () {
                        this.api().columns([1,2,3,4,5]).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            $(input).css("width", "40px");

                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                    }
                });
                // -------------------------------fine lettura dati datatable ---------------------

                // --------------------------------  INSERISCI DESTINAZIONE -----------------------------------//
/*                $('.destinazione').on('change', function (e) {
                    $(this).find(":selected").each(function () {
                        var destinazione = $(this).val();
                        var selected = [];
                        var quantita = [];
                        //alert(destinazione);
                        //alert($('input[type=checkbox]').attr('checked'));
                        $('input:checked').each(function() {
                            selected.push($(this).attr('id'));
                            quantita.push($(this).parent().html());
                        });
                        alert(quantita);
                        var urlDest = ($('#assegnaForm').attr('action'));
                        //alert(urlDest);
                        $.ajax(urlDest2,
                            {
                                method: 'POST',
                                data : {
                                    '_token' : $('#_token').val(),
                                    'destinazione' : destinazione,
                                    'prodotti': selected,
                                    'quantita': quantita

                                },
                                complete : function (resp) {
                                    //console.log(resp.responseJSON);
                                    var prelink = route('index') }}';
                                    var link = prelink+resp.responseText;
                                    window.open(link);
                                    window.location.reload();
                                    $("#destinazione").val("");
                                }
                            })

                    });
                });*/
                // --------------------------------  FINE INSERISCI DESTINAZIONE -----------------------------------//

                // --------------------- pulsante INFO PROVA PRODOTTO-----------------------------------//
                $('#products_table').on('click', 'a.btn-success', function (ele) {
                    $('#productprova_table_info tbody').html('');

                    ele.preventDefault();

                    var urlprodotto = ($(this).attr('href'));
                    $.ajax(urlprodotto,
                        {
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                var nome = resp.responseJSON.nome;
                                var cognome = resp.responseJSON.cognome;
                                var indirizzo = resp.responseJSON.indirizzo;
                                var citta = resp.responseJSON.citta;
                                var telefono = resp.responseJSON.telefono;
                                $('#productprova_table_info tbody').html('<td>'+nome+'</td><td>'+cognome+'</td><td>'+indirizzo+'</td><td>'+citta+'</td><td>'+telefono+'</td>');
                            }
                        });
                });
                // --------------------- FINE pulsante INFO PROVA PRODOTTO---------------------------//

            }

        )

    </script>

@endsection
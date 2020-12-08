@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped display nowrap" id="listino_table" style="width:100%;">
        <thead>
            <tr>
                <th style="text-align: center">Azioni</th>
                <th>Categoria</th>
                <th>Descrizione</th>
                <th>Costo</th>
                <th>Prezzo</th>
                <th>Iva</th>
            </tr>
        </thead>
        <tbody style="font-size: 14px">
        </tbody>
    </table>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#listino_table').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    "scrollX": true,
                    ajax: '{{route('listino.getListino')}}',
                    columns: [
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false},
                        {data: 'categoria', name: 'categoria'},
                        {data: 'descrizione', name: 'descrizione'},
                        {data: 'costo', name: 'costo'},
                        {data: 'prezzolistino', name: 'prezzolistino'},
                        {data: 'iva', name: 'iva'}
                    ]
                });
                // -------------------------------fine lettura dati datatable ---------------------

                // --------------------- pulsante DELETE -----------------------------------//
                $('#listino_table').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();

                    var urlListino = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode.parentNode;
                    //alert(urlClient);
                    $.ajax(urlListino,
                        {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            complete : function (resp) {
                                console.log(resp);        //COSì POSSIAMO VEDERE IL VALORE ( = 1) NELLA CONSOLE DEL BROWSER
                                if(resp.responseText == 1){
                                    //alert(resp.responseText);
                                    tr.parentNode.removeChild(tr);        //QUESTO è CON JAVASCRIPT
                                    // $(li).remove();                     //QUESTO è CON JQUERY
                                }else{
                                    alert('problemi');
                                }
                            }
                        })
                });
                // --------------------- FINE pulsante DELETE -----------------------------------//
            }

        )

    </script>

@endsection
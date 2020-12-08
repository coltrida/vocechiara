@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped" id="fontsTable">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cod Fisc / P.IVA</th>
                <th>Città</th>
                <th>PEC</th>
                <th>Cod. Univoco</th>
                <th style="width: 15%; text-align: center">Azioni</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#fontsTable').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('fonts.getFonts')}}',
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'codfisc', name: 'codfisc'},
                        {data: 'citta', name: 'citta'},
                        {data: 'pec', name: 'pec'},
                        {data: 'univoco', name: 'univoco'},
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false}
                    ]
                });          // -------------------------------fine lettura dati datatable ---------------------

                // --------------------- pulsante DELETE -----------------------------------//
                $('#fontsTable').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();

                    var urlClient = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode.parentNode;
                    //alert(urlClient);
                    $.ajax(urlClient,
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
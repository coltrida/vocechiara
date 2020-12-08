@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped display nowrap" id="clients_table" style="width:100%;">
        <thead>
            <tr>
                <th style="text-align: center">Azioni</th>
                <th>Cognome</th>
                <th>Nome</th>
                <th>Indirizzo</th>
                <th>CAP</th>
                <th>Città</th>
                <th>PR</th>
                <th>Tel</th>
                <th>Tipo</th>
                <th>Fonte</th>
                <th>Audio</th>
                <th>Vendita</th>

            </tr>
        </thead>
        <tbody style="font-size: 14px">
        </tbody>
        <tfoot>
        <tr>
            <th style="text-align: center">&nbsp;</th>
            <th>Cognome</th>
            <th>Nome</th>
            <th>&nbsp;</th>
            <th>CAP</th>
            <th>Città</th>
            <th>PR</th>
            <th>&nbsp;</th>
            <th>Tipo</th>
            <th>Fonte</th>
            <th>Audio</th>
            <th>Vendita</th>
        </tr>
        </tfoot>
    </table>

    @include('clienti.partials.infoClientModal')
    @include('clienti.partials.phoneClientModal')
    @include('clienti.partials.noteClientModal')

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#clients_table').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    "scrollX": true,
                    ajax: '{{route('prove.getClients')}}',
                    columns: [
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false},
                        {data: 'cognome', name: 'cognome'},
                        {data: 'nome', name: 'nome'},
                        {data: 'indirizzo', name: 'indirizzo'},
                        {data: 'cap', name: 'cap'},
                        {data: 'citta', name: 'citta'},
                        {data: 'provincia', name: 'provincia'},
                        {data: 'telefono', name: 'telefono'},
                        {data: 'tipo', name: 'tipo'},
                        {data: 'fonte', name: 'fonte'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'ultimav', name: 'ultimav'}

                    ],
                    initComplete: function () {
                        this.api().columns([1,2,4,5,6,7,8,9,10,11]).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            $(input).css("width", "40px");
                            //nome = this.html();
                            //$(input).attr("placeholder", 'pp');
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                    }
                });
                // -------------------------------fine lettura dati datatable ---------------------

                // --------------------- pulsante DELETE -----------------------------------//
                $('#clients_table').on('click', 'a.btn-danger', function (ele) {
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

                // --------------------- pulsante INFO -----------------------------------//
                $('#clients_table').on('click', 'a.btn-success', function (ele) {
                    $('#modalClient').html('');
                    $('#ultima').html('');
                    $('#chartContainer').html('');

                    ele.preventDefault();

                    var urlinfoClient = ($(this).attr('href'));
                    //alert(urlClient);
                    $.ajax(urlinfoClient,
                        {
                            @include('clienti.partials.infoClient')
                        });
                });
                // --------------------- FINE pulsante INFO -----------------------------------//

                // --------------------- pulsante PHONE -----------------------------------//
                $('#clients_table').on('click', 'a.phone', function (ele) {
                   // ele.preventDefault();
                    var idClient = ($(this).attr('id'));
                    $('#id_cliente').val(idClient);

                });
                // --------------------- FINE pulsante PHONE -----------------------------------//

                // --------------------- pulsante NOTA -----------------------------------//
                $('#clients_table').on('click', 'a.note', function (ele) {
                    // ele.preventDefault();
                    var idClient = ($(this).attr('id'));
                    var id = idClient.substring(4);
                    $('#id_cliente_nota').val(id);
                    caricaNote(id);
                    $('#nota').val('');
                });
                // --------------------- FINE pulsante NOTA -----------------------------------//

                // --------------------- pulsante NUOVA NOTA -----------------------------------//
                $('#nuovaNotabtn').on('click', function (ele) {
                    ele.preventDefault();
                    $('#nota').text('');
                    var urlNota = ($('#formNota').attr('action'));
                    $.ajax(urlNota,
                        {
                            method: 'POST',
                            data : {
                                '_token' : $('#_token').val(),
                                'nota' : $('#nota').val(),
                                'id_cliente' : $('#id_cliente_nota').val()
                            },
                            complete : function (resp) {
                                console.log(resp);
                                if(resp.responseText == 1){
                                    //$('#noteClient').modal('toggle');
                                    caricaNote($('#id_cliente_nota').val());
                                    //ricarina note
                                    $('#nota').val('');

                                }else{
                                    alert('problemi');
                                }
                            }
                        })
                });


                // --------------------- FINE pulsante NUOVA NOTA -----------------------------------//

                // ---------------------- CARICA NOTE ---------------------------------------//
                function caricaNote(idCliente) {
                    $('#tableNote tbody').html('');
                    var prelinknote = '{{ route('nota.getNotes', ":id") }}';
                    var linknote = prelinknote.replace(':id', idCliente);
                    //alert(linknote);
                    $.ajax(linknote,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                if((resp.responseJSON.note).length > 0){
                                    for (var k = 0; k < (resp.responseJSON.note).length; k++){
                                        var idnota = resp.responseJSON.note[k].id;
                                        var testo = resp.responseJSON.note[k].testo;
                                        var giorno = resp.responseJSON.note[k].created_at;
                                        var prelinkelimina = '{{ route('nota.elimina', ":idnota") }}';
                                        var linkelimina = prelinkelimina.replace(':idnota', idnota);
                                        $('#tableNote tbody').append('<tr><td>'+testo+'</td><td>'+giorno+'</td><td><a title="elimina" href="'+linkelimina+'" class="btn-sm btn-danger elenote"><i class="fa fa-trash" ></i></a></td></tr>');
                                    }
                                }
                            }
                        })
                }
                // ---------------------- FINE CARICA NOTE ---------------------------------------//

                // --------------------- pulsante DELETE NOTA-----------------------------------//
                $('#tableNote').on('click', 'a.elenote', function (ele) {
                    ele.preventDefault();

                    var urlNota = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode;
                    //alert(urlNota);
                    $.ajax(urlNota,
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
                // --------------------- FINE pulsante DELETE NOTA-----------------------------------//

            }

        )

    </script>

@endsection
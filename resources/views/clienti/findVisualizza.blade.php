@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped" id="clients_table_find">
        <thead>
            <tr>
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
                <th style="width: 15%; text-align: center">Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientFind as $item)
                <tr>
                    <td>{{$item->cognome}}</td>
                    <td>{{$item->nome}}</td>
                    <td>{{$item->indirizzo}}</td>
                    <td>{{$item->cap}}</td>
                    <td>{{$item->citta}}</td>
                    <td>{{$item->provincia}}</td>
                    <td>{{$item->telefono}}</td>
                    <td>{{$item->tipo}}</td>
                    <td>{{$item->fonte}}</td>
                    <td>{{$item->user->name}}</td>
                    <td>12/04/2018</td>
                    <td>
                        <div style="display: flex; justify-content: flex-start">
                            <a title="elimina" href="{{route('clients.destroy', $item->id)}}" class="btn-sm btn-danger"><i class="fa fa-trash" ></i></a>
                            &nbsp;
                            <a title="modifica" href="{{route('clients.edit', $item->id)}}" class="btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            &nbsp;
                            <a title="info" href="{{route('clients.info', $item->id)}}" class="btn-sm btn-success" data-toggle="modal" data-target="#infoClient"><i class="fas fa-info-circle"></i></a>
                        </div>

                        <div style="display: flex; justify-content: flex-start">
                            @if($item->recall)
                                <a title="{{date('d/m/Y', strtotime($item->datarecall))}}" id="{{$item->id}}" href="{{route('clients.phone', $item->id)}}" class="btn-sm phone" data-toggle="modal" style="background-color: yellow" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                            @else
                                <a title="phone" id="{{$item->id}}" href="{{route('clients.phone', $item->id)}}" class="btn-sm phone" data-toggle="modal" data-target="#phoneClient"><i class="fas fa-phone"></i></a>
                            @endif
                            &nbsp;
                            <a title="note" id="{{'note'.$item->id}}" href="{{route('clients.note', $item->id)}}" class="btn-sm  note" data-toggle="modal" style="background-color: yellow" data-target="#noteClient"><i class="far fa-sticky-note"></i></a>
                            &nbsp;
                            <a title="apa" id="{{'apa'.$item->id}}" href="{{route('clients.note', $item->id)}}" class="btn-sm  apa" data-toggle="modal" style="background-color: #4c110f" data-target="#apaClient"><i class="fas fa-assistive-listening-systems"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="infoClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClient"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="display: flex; justify-content: space-between">
                        <h4>Ultima modifica</h4>
                        <h4 id="ultima"> </h4>
                    </div>

                    <div id="chartContainer" style="height: 400px; width: 520px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-primary">Modifica</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="phoneClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recall</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('clients.phone.store')}}" method="POST">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        <input type="date" name="recall" id="recall">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                        <button type="submit" class="btn btn-primary">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                // --------------------- pulsante DELETE -----------------------------------//
                $('#clients_table_find').on('click', 'a.btn-danger', function (ele) {
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
                $('#clients_table_find').on('click', 'a.btn-success', function (ele) {
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
                $('#clients_table_find').on('click', 'a.phone', function (ele) {
                    // ele.preventDefault();
                    var idClient = ($(this).attr('id'));
                    //alert(idClient);
                    $('#id_cliente').val(idClient);

                });
                // --------------------- FINE pulsante PHONE -----------------------------------//
            }

        )

    </script>

@endsection
@extends('layouts.template-admin')

@section('container')
<h1>Home</h1>
<div class="container">
    <div class="row">
        <div class="col-sm" style="border: solid 1px gray; height: 400px">
            <h4>Da Richiamare</h4>
            <table class="table table-striped" id="clients_table_recall" style="font-size: 14px">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Città</th>
                    <th>Tel</th>
                    <th>Data Recall</th>
                    <th style="width: 15%; text-align: center">Azioni</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($clientRecall as $item)
                        <tr>
                            <td>{{$item->nome}}</td>
                            <td>{{$item->cognome}}</td>
                            <td>{{$item->citta}}</td>
                            <td>{{$item->telefono}}</td>
                            <td>{{$item->datarecall}}</td>
                            <td>
                                <div style="display: flex; justify-content: center">
                                    <a title="Chiamato" href="{{route('clients.destroy.call', $item->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-phone-slash"></i></a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-sm" style="border: solid 1px gray; height: 400px">
            <h4>Recall Automatici</h4>
            <table class="table table-striped" id="clients_table_auto" style="font-size: 14px">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Città</th>
                    <th>Tel</th>
                    <th>Data Recall</th>
                    <th style="width: 15%; text-align: center">Azioni</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recallAutomatici as $item)
                    <tr>
                        <td>{{$item->nome}}</td>
                        <td>{{$item->cognome}}</td>
                        <td>{{$item->citta}}</td>
                        <td>{{$item->telefono}}</td>
                        <td>{{$item->updated_at->format('d/m/Y')}}</td>
                        <td>
                            <div style="display: flex; justify-content: center">
                                <a title="Chiamato" href="{{route('clients.destroy.auto', $item->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-phone-slash"></i></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{--<div class="col-sm" style="border: solid 1px gray; height: 400px">
            One of three columns
        </div>--}}
    </div>
</div>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                // --------------------- pulsante DELETE CALL-----------------------------------//
                $('#clients_table_recall').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();

                    var urlClient = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode.parentNode;
                    //alert(urlClient);
                    $.ajax(urlClient,
                        {
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
                // --------------------- FINE pulsante DELETE CALL-----------------------------------//

                // --------------------- pulsante DELETE CALL AUTO-----------------------------------//
                $('#clients_table_auto').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();

                    var urlClient = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode.parentNode;
                    //alert(urlClient);
                    $.ajax(urlClient,
                        {
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
                // --------------------- FINE pulsante DELETE CALL AUTO-----------------------------------//

            }
        )
    </script>
@endsection

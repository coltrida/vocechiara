@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped" id="ddtTable">
        <thead>
            <tr>
                <th>Azioni</th>
                <th>Data DDT</th>
                <th>Filiale</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paginatedItems as $file)
                <tr>
                    <td><a target="_blank" class="btn-sm btn-primary" href="{{'/'.$file}}"><i class="fas fa-print"></i></a></td>
                    <td>{{str_replace("storage/ddt/".$filiale->nome."/", '', $file)}}</td>

                    <td>{{$filiale->nome}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $paginatedItems->links() }}
    </div>
@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                // --------------------- pulsante INFO -----------------------------------//
                $('#ddtTable').on('click', 'a.info', function (ele) {
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
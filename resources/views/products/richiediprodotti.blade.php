@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-10 offset-md-1">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

                <h1>Richiedi Prodotti</h1>
                <form>

                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

                    <input type="hidden" value="richiesta" name="stato" id="stato">
                    <input type="hidden" value="{{auth()->user()->id}}" name="audio" id="audio">

                    <div style="display: flex; justify-content: space-between">
                        <div>
                            <a id="inserisciprodottoBtn" data-number="0" class="btn btn-primary">Inserisci Prodotto</a>
                        </div>
                    </div>

                    <div class="form-group" id="listaprodotti">
                    </div>

                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('clients.index')}}" class="btn btn-info">INDIETRO</a>
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">RICHIEDI</button>
                    </div>

                </form>

        </div>

    </div>

    <div class="row" style="margin-top: 30px">
        <div class="col-md-10 offset-md-1">
            <form>
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <div id="listaprove" class="table-responsive">
                    <h4>Prodotti Richiesti</h4>
                    <table class="table table-striped text-nowrap" id="prodotti_table" style="width:100%;">
                        <thead>
                        <tr>
                            <th style="text-align: center">Azioni</th>
                            <th>DATA</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px">
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    @include('products.partials.infoProductModal')
@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto
                CaricaProdotti($('#audio').val());

                // ---------------------- CARICA PRODOTTI RICHIESTI ---------------------------------------//
                function CaricaProdotti(id) {
                    $('#prodotti_table tbody').html('');
                    var prelinkprove = '{{ route('products.prodottiRichiesti', ":id") }}';
                    var linkprodotti = prelinkprove.replace(':id', id);

                    //alert(linknote);
                    $.ajax(linkprodotti,
                        {
                            method: 'GET',
                            complete : function (resp) {
                               console.log(resp.responseJSON);

                                if((resp.responseJSON.prove).length > 0){
                                    //alert(resp.responseJSON.prove.length);
                                    for (var k = 0; k < (resp.responseJSON.prove.length); k++){
                                        var idprova = resp.responseJSON.prove[k].id;

                                        var selezionato = resp.responseJSON.prove[k].created_at;
                                        var giorno = $.format.date(selezionato, "dd-MM-yyyy");


                                        var PrimaColonna = '<a title="info" href="#" id="'+idprova+'" class="btn-sm  btn-success info" data-toggle="modal" data-target="#infoProva"><i class="fas fa-info-circle"></i></a>';

                                        $('#prodotti_table tbody').append('<tr><td style="text-align:center">'+PrimaColonna+'</td><td>'+giorno+'</td></tr>');

                                    }
                                }
                            }
                        })
                }
                // ---------------------- FINE CARICA PROVE ---------------------------------------//


        // --------------------- pulsante NUOVA RICHIESTA -----------------------------------//
        $('#save').on('click', function (ele) {
            ele.preventDefault();
            $('#save').attr('disabled', true);
            var numeroprodotti = $('#inserisciprodottoBtn').attr('data-number');

            var ids = [];
            var quantita = [];

            for(var j = 1; j <= numeroprodotti; j++){
                ids[j-1] = $('#ele'+j).attr('data-title');
                quantita[j-1] = $('#qta'+j).val();
            }

            var urlProva = '{{route('products.storeRichiesti')}}';
            $.ajax(urlProva,
                {
                    method: 'POST',
                    data : {
                        '_token' : $('#_token').val(),
                        'ids[]' : ids,
                        'quantita[]' : quantita,
                        'stato' : $('#stato').val()
                    },
                    complete : function (resp) {
                        console.log(resp);
                        if(resp.responseText == 1){
                            for (var i = 1; i <= numeroprodotti; i++){
                                $('#ele'+i).html('');
                                $('#qta'+i).html('');
                            }
                            $('#inserisciprodottoBtn').attr('data-number',0);
                            CaricaProdotti($('#audio').val());
                        }else{
                            alert('problemi');
                        }
                    }
                })
        });
        // --------------------- FINE pulsante NUOVA RICHIESTA -----------------------------------//

                // --------------------- pulsante INFO -----------------------------------//
                $('#prodotti_table').on('click', 'a.info', function (ele) {
                    var idsel = $(this).attr('id');
                    $('#product_table_info tbody').html('');
                    var prelinkinfo = '{{ route('prove.info', ":idprova") }}';
                    var linkinfo = prelinkinfo.replace(':idprova', idsel);
                    $.ajax(linkinfo,
                        {
                            method: 'GET',
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                for (var d = 0; d < (resp.responseJSON.prodottiprova).length; d++){
                                    var categoria = resp.responseJSON.provaprodotti[d].categoria;
                                    var descrizione = resp.responseJSON.provaprodotti[d].descrizione;
                                    var quantita = resp.responseJSON.prodottiprova[d].quantita;

                                    $('#product_table_info tbody').append('<tr><td style="text-align:center">'+categoria+'</td><td >'+descrizione+'</td><td>'+quantita+'</td></tr>');
                                }
                            }
                        })
                });
                // --------------------- FINE pulsante INFO -----------------------------------//

                // --------------------- pulsante ELIMINA PRODOTTO -----------------------------------//
                $('#listaprodotti').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();
                    var riga = this.parentNode.parentNode;
                    riga.parentNode.removeChild(riga);
                    var numero = parseInt($('#inserisciprodottoBtn').attr('data-number')) - 1;
                    $('#inserisciprodottoBtn').attr('data-number', numero);
                    //alert($('#inserisciprodottoBtn').attr('data-number'));
                });
                // --------------------- FINE ELIMINA PRODOTTO -----------------------------------//


                // --------------------- PULSANTE INSERISCI PRODOTTO---------------------------//
                $('#inserisciprodottoBtn').on('click', function (ele) {
                    ele.preventDefault();
                    $('#save').attr('disabled', false);
                    var numero = parseInt($('#inserisciprodottoBtn').attr('data-number')) + 1;
                    $('#inserisciprodottoBtn').attr('data-number', numero);


                    $('#listaprodotti').append('<div class="rigasel" id="ele'+numero+'" data-title="" style="display: flex; justify-content: space-between"><div style="width: 5%" class="row align-items-end rigadel"><a href="#" class="btn-sm btn-danger "><i class="fas fa-times-circle"></i></a></div><div style="width: 10%"><label for="cat'+numero+'">Cat.</label><select id="cat'+numero+'" name="cat[]" class="form-control categoria"><option></option><option>APA</option><option>ACC</option><option>CON</option></select></div><div style="width: 35%"><label for="des'+numero+'">Prodotto</label><select id="des'+numero+'" name="des[]" class="form-control descrizione" ><option></option></select></div><div style="width: 35%"><label for="qta'+numero+'">Quantità</label><input type="text" name="qta[]" id="qta'+numero+'" class="form-control"></div></div>');

                    $('.categoria').change( function() {
                        $(this).find(":selected").each(function () {
                            var idcat = $(this).parent().attr('id');
                            var idpro = 'des'+idcat.substring(3);

                           $('#'+idcat).attr('disabled', 'true');
                            var valorecat = $(this).val();
                            var label = 'label'+idcat.substring(3);
                            var cellaselect = 'cella'+idcat.substring(3);
                            var num = idcat.substring(3);
                            //alert(num);
                            //$('#'+cellaselect).html('');

                            var prelinkprodotti = '{{ route('listino.getProdotti', ":categoria") }}';
                            var linkprodotti = prelinkprodotti.replace(':categoria', valorecat);

                            $('#'+idpro).html('');
                            $('#'+idpro).append('<option></option>');

                            $.ajax(linkprodotti,
                                {
                                    method: 'GET',
                                    complete : function (resp) {
                                        //console.log(resp.responseJSON);
                                        for (var k = 0; k < resp.responseJSON.length; k++){
                                            var valores = resp.responseJSON[k].descrizione;
                                            var id = resp.responseJSON[k].id;
                                            $('#'+idpro).append('<option data-content="'+id+'">'+valores+'</option>');
                                        }

                                        $('.descrizione').change( function() {
                                            $(this).find(":selected").each(function () {
                                                var idriga = 'ele' + idpro.substring(3);
                                                var idlistino = $(this).attr('data-content');
                                                //alert(idlistino);
                                                $('#'+idriga).attr('data-title', idlistino);
                                            })
                                        })

                                    }
                                })

                        });
                    });
                });
                // --------------------- FINE PULSANTE INSERISCI PRODOTTO---------------------------//

            }

        )

    </script>

@endsection

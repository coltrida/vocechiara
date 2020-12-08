@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

            @if($product->id)
                <h1>Modifica Prodotto</h1>
                <form action="{{route('products.update', $product->id)}}" method="POST">
                    {{method_field('PATCH')}}
            @else
                <h1>Inserisci Prodotto</h1>
                <form action="{{route('products.store')}}" method="POST">
            @endif

                    {{csrf_field()}}

                    @if($product->id)
                        <div class="form-group">
                            <label for="id_listino">Descrizione</label>
                            <select id="id_listino" name="id_listino" class="form-control" required>
                                <option></option>
                                @foreach($listino as $item)
                                    <option data-content="{{$item->categoria}}" {{$item->id==$product->id_listino ? 'selected':''}} value="{{$item->id}}">{{$item->descrizione}}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="id_listino">Descrizione</label>
                            <select id="id_listino" name="id_listino" class="form-control" required>
                                <option></option>
                                @foreach($listino as $item)
                                    <option data-content="{{$item->categoria}}" value="{{$item->id}}">{{$item->descrizione}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="nome">Matricola</label>
                        <input type="text" required value="{{$product->matricola ? $product->matricola : old('matricola')}}"
                               name="matricola" id="matricola" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('matricola'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('matricola') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="quantita">Quantità</label>
                        <input type="text" value="{{$product->quantita ? $product->quantita : old('quantita')}}"
                               name="quantita" id="quantita" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('quantita'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('quantita') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    @if($product->id)
                        <div class="form-group">
                            <label for="destinazione">Magazzino di</label>
                            <select id="destinazione" name="destinazione" class="form-control">
                                <option></option>
                                @foreach($filiali as $filiale)
                                    <option {{$product->destinazione==$filiale->nome ? 'selected':''}}>{{$filiale->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="destinazione">Magazzino di</label>
                            <select id="destinazione" name="destinazione" class="form-control">
                                <option></option>
                                @foreach($filiali as $filiale)
                                    <option>{{$filiale->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif


                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('products.index')}}" class="btn btn-info">INDIETRO</a>
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">SAVE</button>
                    </div>

                </form>

        </div>
    </div>
@endsection
@section('footer')
    @parent
    <script>
        $(
            function () {
                // --------------------------------  VERIFICA CATEGORIA -----------------------------------//
                $('#id_listino').on('change', function (e) {
                    $(this).find(":selected").each(function () {
                        var categoria = $(this).attr('data-content');
                        if(categoria == 'CON'){
                            $('#matricola').val($(this).html());
                            $('#matricola').attr('readonly', true);
                            $('#quantita').val('');
                            $('#quantita').attr('disabled', false);
                        }else {
                            $('#matricola').val('');
                            $('#matricola').attr('disabled', false);
                            $('#quantita').val('1');
                            $('#quantita').attr('readonly', true);
                        }

                    });
                });
                // --------------------------------  FINE VERIFICA CATEGORIA -----------------------------------//
            }
        )

    </script>
@endsection

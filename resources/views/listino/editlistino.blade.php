@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

            @if($listino->id)
                <h1>Modifica Elemento del Listino</h1>
                <form action="{{route('listino.update', $listino->id)}}" method="POST">
                    {{method_field('PATCH')}}
            @else
                <h1>Inserisci Elemento nel Listino</h1>
                <form action="{{route('listino.store')}}" method="POST">
            @endif

                    {{csrf_field()}}

                    @if($listino->id)
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <select id="categoria" name="categoria" class="form-control" required>
                                <option></option>
                                <option {{$listino->categoria=='APA' ? 'selected':''}}>APA</option>
                                <option {{$listino->categoria=='ACC' ? 'selected':''}}>ACC</option>
                                <option {{$listino->categoria=='CON' ? 'selected':''}}>ACC</option>
                            </select>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <select id="categoria" name="categoria" class="form-control" required>
                                <option></option>
                                <option>APA</option>
                                <option>ACC</option>
                                <option>CON</option>
                            </select>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="descrizione">Descrizione</label>
                        <input type="text" required value="{{$listino->descrizione ? $listino->descrizione : old('descrizione')}}"
                               name="descrizione" id="descrizione" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('descrizione'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('descrizione') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="costo">Costo</label>
                        <input type="text" required value="{{$listino->costo ? $listino->costo : old('costo')}}"
                               name="costo" id="costo" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('costo'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('costo') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="prezzolistino">Prezzo</label>
                        <input type="text" required value="{{$listino->prezzolistino ? $listino->prezzolistino : old('prezzolistino')}}"
                               name="prezzolistino" id="prezzolistino" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('prezzolistino'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('prezzolistino') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="iva">Iva</label>
                        <input type="text" required value="{{$listino->iva ? $listino->iva : old('iva')}}"
                               name="iva" id="iva" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('iva'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('iva') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('listino.index')}}" class="btn btn-info">INDIETRO</a>
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">SAVE</button>
                    </div>

                </form>

        </div>
    </div>
@endsection

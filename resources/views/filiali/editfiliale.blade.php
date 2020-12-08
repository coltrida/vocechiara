@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

                @if($filiale->id)
                    <h1>Modifica Filiale</h1>
                    <form action="{{route('filiale.update', $filiale->id)}}" method="POST">
                        {{method_field('PATCH')}}
                @else
                    <h1>Inserisci Filiale</h1>
                    <form action="{{route('filiale.store')}}" method="POST">
                @endif

                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" required value="{{$filiale->nome ? $filiale->nome  : old('nome')}}"
                               name="nome" id="nome" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('nome'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('nome') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>

                <div class="form-group">
                    <label for="indirizzo">Indirizzo</label>
                    <input type="text" required value="{{$filiale->indirizzo ? $filiale->indirizzo  : old('indirizzo')}}"
                           name="indirizzo" id="indirizzo" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('indirizzo'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('indirizzo') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="citta">Città</label>
                    <input type="text" required value="{{$filiale->citta ? $filiale->citta  : old('citta')}}"
                           name="citta" id="citta" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('citta'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('citta') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="cap">CAP</label>
                    <input type="text" required value="{{$filiale->cap ? $filiale->cap  : old('cap')}}"
                           name="cap" id="cap" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('cap'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('cap') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="provincia">Provincia</label>
                    <input type="text" required value="{{$filiale->provincia ? $filiale->provincia  : old('provincia')}}"
                           name="provincia" id="provincia" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('provincia'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('provincia') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>

                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('filiale.index')}}" class="btn btn-info">INDIETRO</a>
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">SAVE</button>
                    </div>

                </form>

        </div>
    </div>
@endsection
{{--
@section('footer')
    @parent
    <script>
        $(
            function () {

            }
        )

    </script>
@endsection--}}

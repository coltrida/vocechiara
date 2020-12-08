@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

            @if($font->id)
                <h1>Modifica Fonte</h1>
                <form action="{{route('fonts.update', $font->id)}}" method="POST">
                    {{method_field('PATCH')}}
            @else
                <h1>Inserisci Fonte</h1>
                <form action="{{route('fonts.store')}}" method="POST">
            @endif

                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" required value="{{$font->name ? $font->name : old('name')}}"
                               name="name" id="name" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('name'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('name') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="codfisc">Codice Fiscale / P.IVA</label>
                        <input type="text" value="{{$font->codfisc ? $font->codfisc : old('codfisc')}}"
                               name="codfisc" id="codfisc" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('codfisc'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('codfisc') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="citta">Città</label>
                        <input type="text" value="{{$font->citta ? $font->citta : old('citta')}}"
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
                        <label for="pec">PEC</label>
                        <input type="text" value="{{$font->pec ? $font->pec : old('pec')}}"
                               name="pec" id="pec" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('provincia'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('provincia') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="univoco">Codice Univoco</label>
                        <input type="text" value="{{$font->univoco ? $font->univoco : old('univoco')}}"
                               name="univoco" id="univoco" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('univoco'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('univoco') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>


                    <div class="form-group">
                        {{--<a href="{{route('clients.index')}}" class="btn btn-info">INDIETRO</a>--}}
                        <button id="reset" type="reset" class="btn btn-primary">CANCELLA</button>
                        <button id="save" class="btn btn-success">SAVE</button>
                    </div>

                </form>

        </div>
    </div>
@endsection
@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif


                    <h1>Modifica Utente</h1>
                    <form action="{{route('audio.update', $user->id)}}" method="POST">
                        {{method_field('PATCH')}}

                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" required value="{{$user->name ? $user->name  : old('name')}}"
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
                    <label for="email">Username</label>
                    <input type="text" required value="{{$user->email ? $user->email  : old('email')}}"
                           name="email" id="email" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('email'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('email') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="ruolo">Ruolo</label>
                    <input type="text" required value="{{$user->ruolo ? $user->ruolo  : old('ruolo')}}"
                           name="ruolo" id="ruolo" class="form-control" placeholder="" aria-describedby="helpId">

                    @if($errors->get('ruolo'))
                        <div class="badge badge-danger">
                            @foreach($errors->get('ruolo') as $error)
                                {{$error}}<br>
                            @endforeach
                        </div>
                    @endif
                </div>


                <div class="form-group">
                    <label for="magazzino">Filiale</label>
                        <select id="magazzino" name="magazzino" class="form-control">
                            <option></option>
                            @foreach($filiali as $filiale)
                                <option {{$user->magazzino==$filiale->nome ? 'selected':''}}>{{$filiale->nome}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('magazzino'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('magazzino') }}</strong>
                            </span>
                        @endif

                </div>



                    <div class="form-group">
                        <a href="{{route('audio.index')}}" class="btn btn-info">INDIETRO</a>
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

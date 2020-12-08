@extends('layouts.template-admin')

@section('container')

    <div class="row">
        <div class="col-md-6 offset-md-3">
            @if(session()->has('message-find'))
                <div class="alert alert-info" role="alert">
                    <strong>{{session('message-find')}}</strong>
                </div>
            @endif

            <h1>Ricerca cliente</h1>
                <form action="{{route('clients.find.esegui')}}" method="POST">

                    @csrf

                    <div class="form-group">
                        <label for="cap">CAP</label>
                        <input type="text" name="cap" class="form-control" id="cap" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="citta">Città</label>
                        <input type="text" name="citta" class="form-control" id="citta" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="pr">PR</label>
                        <input type="text" name="pr" class="form-control" id="pr" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control">
                            <option></option>
                            <option>PC</option>
                            <option>CL</option>
                            <option>CLC</option>
                            <option>PRE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fonte">Fonte</label>
                        <select id="fonte" name="fonte" class="form-control">
                            <option></option>
                            @foreach($fonts as $font)
                                <option value="{{$font->name}}">{{$font->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="audio">Audioprotesista</label>
                        <select id="audio" name="audio" class="form-control">
                            <option></option>
                            @foreach($audios as $audio)
                                <option value="{{$audio->id}}">{{$audio->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
        </div>
    </div>
@endsection


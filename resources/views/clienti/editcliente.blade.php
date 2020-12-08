@extends('layouts.template-admin')

@section('container')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if(session()->has('message'))
                <div class="alert alert-info" role="alert">
                        <strong>{{session('message')}}</strong>
                </div>
            @endif

            @if($client->id)
                <h1>Modifica cliente</h1>
                <form action="{{route('clients.update', $client->id)}}" method="POST">
                    {{method_field('PATCH')}}
            @else
                <h1>Inserisci cliente</h1>
                <form action="{{route('clients.store')}}" method="POST">
            @endif

                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" required value="{{$client->nome ? $client->nome : old('nome')}}"
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
                        <label for="cognome">Cognome</label>
                        <input type="text" required value="{{$client->cognome ? $client->cognome : old('cognome')}}"
                               name="cognome" id="cognome" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('cognome'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('cognome') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="cod_fisc">Codice Fiscale</label>
                        <input type="text" required value="{{$client->cod_fisc ? $client->cod_fisc : old('cod_fisc')}}"
                               name="cod_fisc" id="cod_fisc" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('cod_fisc'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('cod_fisc') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="indirizzo">Indirizzo</label>
                        <input type="text" required value="{{$client->indirizzo ? $client->indirizzo : old('indirizzo')}}"
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
                        <label for="cap">CAP</label>
                        <input type="text" required value="{{$client->cap ? $client->cap : old('cap')}}"
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
                        <label for="citta">Città</label>
                        <input type="text" required value="{{$client->citta ? $client->citta : old('citta')}}"
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
                        <label for="provincia">Provincia</label>
                        <input type="text" required value="{{$client->provincia ? $client->provincia : old('provincia')}}"
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
                        <label for="telefono">Telefono</label>
                        <input type="text" required value="{{$client->telefono ? $client->telefono : old('telefono')}}"
                               name="telefono" id="telefono" class="form-control" placeholder="" aria-describedby="helpId">

                        @if($errors->get('telefono'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('telefono') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    @if($client->id)
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option></option>
                            <option {{$client->tipo=='PC' ? 'selected':''}}>PC</option>
                            <option {{$client->tipo=='CL' ? 'selected':''}}>CL</option>
                            <option {{$client->tipo=='CLC' ? 'selected':''}}>CLC</option>
                            <option {{$client->tipo=='PRE' ? 'selected':''}}>PRE</option>
                        </select>

                        @if($errors->get('tipo'))
                            <div class="badge badge-danger">
                                @foreach($errors->get('tipo') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif

                    </div>
                    @else
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option></option>
                                <option>PC</option>
                                <option>CL</option>
                                <option>CLC</option>
                                <option>PRE</option>
                            </select>

                            @if($errors->get('tipo'))
                                <div class="badge badge-danger">
                                    @foreach($errors->get('tipo') as $error)
                                        {{$error}}<br>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    @endif

                    @if($client->id)
                        <div class="form-group">
                            <label for="fonte">Fonte</label>
                            <select id="fonte" name="fonte" class="form-control" required>
                                <option></option>
                                @foreach($fonts as $font)
                                    <option {{$client->fonte==$font->name ? 'selected':''}} value="{{$font->name}}">{{$font->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="fonte">Fonte</label>
                            <select id="fonte" name="fonte" class="form-control" required>
                                <option></option>
                                @foreach($fonts as $font)
                                    <option value="{{$font->name}}">{{$font->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(!auth()->user()->isAudio())
                        @if($client->id)
                            <div class="form-group">
                                <label for="user_id">Audioprotesista</label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <option></option>
                                    @foreach($audios as $audio)
                                        <option {{$client->user_id==$audio->id ? 'selected':''}} value="{{$audio->id}}">{{$audio->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="user_id">Audioprotesista</label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <option></option>
                                    @foreach($audios as $audio)
                                        <option value="{{$audio->id}}">{{$audio->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @else
                        <input type="hidden" id="user_id" name="user_id" value="{{auth()->user()->id}}">
                    @endif

                    <label>Destro</label>
                    @if(($client->id) and ($audiometria))
                        <div style=" display: flex; justify-content: space-between; background-color: lightgrey; padding: 20px; margin-top: 30px">

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        {{$i}}
                                    </div>

                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_250d" value="{{$i}}" {{$audiometria->_250d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_500d" value="{{$i}}" {{$audiometria->_500d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_1000d" value="{{$i}}" {{$audiometria->_1000d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_2000d" value="{{$i}}" {{$audiometria->_2000d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_4000d" value="{{$i}}" {{$audiometria->_4000d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_8000d" value="{{$i}}" {{$audiometria->_8000d==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                        </div>


                        <div style=" display: flex; justify-content: space-between; margin-bottom: 40px">
                            <div>
                                &nbsp;
                            </div>
                            <div>
                                &nbsp;
                            </div>
                            @for($i = 250; $i < 12000; $i = $i + $i)
                                <div>
                                    {{$i}}
                                </div>
                            @endfor
                        </div>

                    @else

                        <div style=" display: flex; justify-content: space-between; background-color: lightgrey; padding: 20px; margin-top: 30px">

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        {{$i}}
                                    </div>

                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_250d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_500d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_1000d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_2000d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_4000d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_8000d" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                        </div>


                        <div style=" display: flex; justify-content: space-between; margin-bottom: 40px">
                            <div>
                                &nbsp;
                            </div>
                            <div>
                                &nbsp;
                            </div>
                            @for($i = 250; $i < 12000; $i = $i + $i)
                                <div>
                                    {{$i}}
                                </div>
                            @endfor
                        </div>
                    @endif

                    <label>Sinistro</label>
                    @if(($client->id) and ($audiometria))
                        <div style=" display: flex; justify-content: space-between; background-color: lightgrey; padding: 20px; margin-top: 30px">

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        {{$i}}
                                    </div>

                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_250s" value="{{$i}}" {{$audiometria->_250s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_500s" value="{{$i}}" {{$audiometria->_500s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_1000s" value="{{$i}}" {{$audiometria->_1000s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_2000s" value="{{$i}}" {{$audiometria->_2000s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_4000s" value="{{$i}}" {{$audiometria->_4000s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_8000s" value="{{$i}}" {{$audiometria->_8000s==$i ? 'checked':''}}>
                                    </div>
                                @endfor
                            </div>

                        </div>


                        <div style=" display: flex; justify-content: space-between; margin-bottom: 40px">
                            <div>
                                &nbsp;
                            </div>
                            <div>
                                &nbsp;
                            </div>
                            @for($i = 250; $i < 12000; $i = $i + $i)
                                <div>
                                    {{$i}}
                                </div>
                            @endfor
                        </div>
                    @else
                        <div style=" display: flex; justify-content: space-between; background-color: lightgrey; padding: 20px; margin-top: 30px">

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        {{$i}}
                                    </div>

                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_250s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_500s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_1000s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_2000s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_4000s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                            <div style=" display: flex; flex-direction: column; justify-content: space-between">
                                @for($i = 0; $i < 120; $i = $i + 10)
                                    <div>
                                        <input type="radio" name="_8000s" value="{{$i}}">
                                    </div>
                                @endfor
                            </div>

                        </div>


                        <div style=" display: flex; justify-content: space-between; margin-bottom: 40px">
                            <div>
                                &nbsp;
                            </div>
                            <div>
                                &nbsp;
                            </div>
                            @for($i = 250; $i < 12000; $i = $i + $i)
                                <div>
                                    {{$i}}
                                </div>
                            @endfor
                        </div>
                    @endif


                    <div class="form-group">
                        <input type="hidden" id="audiogrammachange" name="audiogrammachange">
                        <a href="{{route('clients.index')}}" class="btn btn-info">INDIETRO</a>
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
        $('#audiogrammachange').val(0);
        //alert($('#audiogrammachange').val());
        $(
            function () {
                $('input:radio').change(function(){
                    $('#audiogrammachange').val(1);
                });
            }
        )

    </script>
@endsection
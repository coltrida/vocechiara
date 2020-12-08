@extends('layouts.template-admin')

@section('container')

    <div style="position: relative; width: 100%;">

        @for($i=0; $i<count($fatturati); $i++)
            <input type="hidden" id="{{'nome'.$i}}" value="{{ $fatturati[$i][0]}}">
            @for($j=1; $j<=$mese; $j++)
                <input type="hidden" id="{{'fatt'.$i.$j}}" value="{{ $fatturati[$i][$j]}}">
            @endfor
            <div id="{{'fatturato'.$i}}" style="position: absolute;
                top: {{$i*500}}px;
                left: 0; width: 100%">
            </div>
        @endfor

        {{--<div id="incorso" style="position: absolute;
    top: 500px;
    left: 0; width: 100%">
        </div>--}}
<div style="position: absolute;
    top: {{count($fatturati)*500}}px;
    left: 0; width: 100%">&nbsp;</div>
    </div>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                //------------------- FATTURATO ---------------------------//
                var nraudio = '{{count($fatturati)}}';
                {{$z=0}}

                for (var k = 0; k < nraudio; k++){
                    /*alert($("#fatturato"+k).css('top'));*/
                    $('#fatturato'+k).html('');

                    var nome = $('#nome'+k).val();

                    var chart = new CanvasJS.Chart("fatturato"+k, {
                        animationEnabled: true,
                        exportEnabled: true,
                        theme: "light1", // "light1", "light2", "dark1", "dark2"
                        title:{
                            text: "Fatturato Anno: "+nome
                        },
                        data: [{
                            type: "column", //change type to bar, line, area, pie, etc
                            //indexLabel: "{y}", //Shows y value on all Data Points
                            indexLabelFontColor: "#5A5757",
                            indexLabelPlacement: "inside",
                            dataPoints: [
                                    @for($i=1; $i<=$mese; $i++)
                                    { label: "{{$i}}", y: parseInt($('#fatt'+k+"{{$i}}").val()) },
                                    @endfor
                            ]
                        }]
                    });
                    chart.render();
                    {{$z = $z + 1}}

                }


                //------------------- FINE FATTURATO ---------------------------//

            }

        )

    </script>

@endsection
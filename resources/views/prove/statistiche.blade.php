@extends('layouts.template-admin')

@section('container')

    <div style="position: relative; width: 100%;">

        <div id="fatturato" style="position: absolute;
    top: 0;
    left: 0; width: 100%">
        </div>

        <div id="incorso" style="position: absolute;
    top: 500px;
    left: 0; width: 100%">
        </div>
<div style="position: absolute;
    top: 1000px;
    left: 0; width: 100%">&nbsp;</div>
    </div>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                //------------------- FATTURATO ---------------------------//
                    $('#fatturato').html('');
                    var mese = '{{$mese}}';

                    var chart = new CanvasJS.Chart("fatturato", {
                        animationEnabled: true,
                        exportEnabled: true,
                        theme: "light1", // "light1", "light2", "dark1", "dark2"
                        title:{
                            text: "Fatturato mese: "+mese
                        },
                        data: [{
                            type: "column", //change type to bar, line, area, pie, etc
                            //indexLabel: "{y}", //Shows y value on all Data Points
                            indexLabelFontColor: "#5A5757",
                            indexLabelPlacement: "inside",
                            dataPoints: [
                                @for($i=0; $i<count($fatturati); $i++)
                                    { label: "{{$audio[$i]->name}}", y: parseInt({{$fatturati[$i]}}) },
                                @endfor
                            ]
                        }]
                    });
                    chart.render();
                //------------------- FINE FATTURATO ---------------------------//

                //------------------- IN CORSO ---------------------------//
                $('#incorso').html('');


                var chart2 = new CanvasJS.Chart("incorso", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light1", // "light1", "light2", "dark1", "dark2"
                    title:{
                        text: "Prove in corso mese: "+mese
                    },
                    data: [{
                        type: "column", //change type to bar, line, area, pie, etc
                        //indexLabel: "{y}", //Shows y value on all Data Points
                        indexLabelFontColor: "#5A5757",
                        indexLabelPlacement: "inside",
                        dataPoints: [
                                @for($k=0; $k<count($prove); $k++)
                            { label: "{{$audio[$k]->name}}", y: parseInt({{$prove[$k]}}) },
                            @endfor
                        ]
                    }]
                });
                chart2.render();
                //------------------- FINE IN CORSO ---------------------------//
            }

        )

    </script>

@endsection
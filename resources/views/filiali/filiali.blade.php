@extends('layouts.template-admin')

@section('container')

    <table class="table table-striped" id="filialiTable">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Indirizzo</th>
                <th>Città</th>
                <th>Cap</th>
                <th>Provincia</th>
                <th style="width: 15%; text-align: center">Azioni</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection

@section('footer')
    @parent
    <script>
        $(
            function () {       //tutto ciò che viene effettuato qui dentro lo farò a DOM pronto

                $('#filialiTable').DataTable({        //----------------- lettura dati datatable
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('filiali.getFiliali')}}',
                    columns: [
                        {data: 'nome', name: 'nome'},
                        {data: 'indirizzo', name: 'indirizzo'},
                        {data: 'citta', name: 'citta'},
                        {data: 'cap', name: 'cap'},
                        {data: 'provincia', name: 'provincia'},
                        {data: 'Azioni', name: 'Azioni', orderable:false, searchable:false}
                    ]
                });          // -------------------------------fine lettura dati datatable ---------------------

                // --------------------- pulsante DELETE -----------------------------------//
                $('#fontsTable').on('click', 'a.btn-danger', function (ele) {
                    ele.preventDefault();

                    var urlClient = ($(this).attr('href'));  //QUESTO è UN ALTRO MODO PER CATTURARE IL LINK (con jQuery)
                    var tr = this.parentNode.parentNode.parentNode;
                    //alert(urlClient);
                    $.ajax(urlClient,
                        {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            complete : function (resp) {
                                console.log(resp);        //COSì POSSIAMO VEDERE IL VALORE ( = 1) NELLA CONSOLE DEL BROWSER
                                if(resp.responseText == 1){
                                    //alert(resp.responseText);
                                    tr.parentNode.removeChild(tr);        //QUESTO è CON JAVASCRIPT
                                    // $(li).remove();                     //QUESTO è CON JQUERY
                                }else{
                                    alert('problemi');
                                }
                            }
                        })
                });
                // --------------------- FINE pulsante DELETE -----------------------------------//

                // --------------------- pulsante INFO -----------------------------------//
          /*      $('#clients_table').on('click', 'a.btn-success', function (ele) {
                    $('#modalClient').html('');
                    $('#ultima').html('');
                    $('#chartContainer').html('');
                    
                    ele.preventDefault();

                    var urlinfoClient = ($(this).attr('href'));
                    //alert(urlClient);
                    $.ajax(urlinfoClient,
                        {
                            complete : function (resp) {
                                console.log(resp.responseJSON);
                                $('#modalClient').html(resp.responseJSON.cognome+" "+resp.responseJSON.nome);
                                $('#ultima').html(resp.responseJSON.updated_at);

                                var primo = parseInt(resp.responseJSON._250);
                                var secondo = parseInt(resp.responseJSON._500);
                                var terzo = parseInt(resp.responseJSON._1000);
                                var quarto = parseInt(resp.responseJSON._2000);
                                var quinto = parseInt(resp.responseJSON._4000);
                                var sesto = parseInt(resp.responseJSON._8000);

                                var chart = new CanvasJS.Chart("chartContainer", {
                                    animationEnabled: true,
                                    theme: "light2",
                                    axisX:{
                                        gridThickness: 1

                                    },
                                    axisY:{
                                        includeZero: false,
                                        reversed: true
                                    },
                                    width:470,
                                    data: [{
                                        type: "line",
                                        dataPoints: [
                                            { y: primo, label: "250" },
                                            { y: secondo, label: "500"},
                                            { y: terzo, label: "1000" },
                                            { y: quarto, label: "2000" },
                                            { y: quinto, label: "4000" },
                                            { y: sesto, label: "8000" }
                                        ]
                                    }]
                                });
                                chart.render();

                            }
                        });



                });*/
                // --------------------- FINE pulsante INFO -----------------------------------//
            }

        )

    </script>

@endsection
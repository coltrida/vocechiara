<div class="modal fade" id="infoProva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClient"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: flex; justify-content: space-between">
                    <h4>Info Prova</h4>
                </div>
                <table class="table table-striped text-nowrap" id="prove_table_info" style="width:100%;">
                    <thead>
                    <tr>
                        <th>CATEGORIA</th>
                        <th>PRODOTTO</th>
                        <th>PREZZO</th>
                        <th>IVA</th>
                        <th>QTA</th>
                        <th>MATRICOLA</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 14px">
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <input type="hidden" name="nr_prodotti" id="nr_prodotti" value=0>
                <input type="hidden" name="id_prova" id="id_prova" value=''>
                <input type="hidden" name="magazzino" id="magazzino" value=''>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="assegna">Assegna e Stampa DDT</button>
            </div>
        </div>
    </div>
</div>
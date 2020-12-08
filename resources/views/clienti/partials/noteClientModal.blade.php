<div class="modal fade" id="noteClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="formNota" action="{{route('nota.store')}}" method="POST">
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input type="hidden" id="id_cliente_nota" name="id_cliente">
                    <input type="text" class="form-control" placeholder="Inserisci nota" name="nota" id="nota">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button id="nuovaNotabtn" type="submit" class="btn btn-primary">Nuova nota</button>
                </div>
            </form>

            <div id="listanote">
                <table id="tableNote" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nota</th>
                        <th>Data</th>
                        <th>Elimina</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 14px">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
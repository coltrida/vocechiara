<div class="modal fade" id="infoPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <h4>Modalità di Pagamento</h4>
                </div>
                <form id="pagamentiForm" action="{{route('fatture.assegnaPagamento')}}">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="prova" name="prova">
                    <div class="form-group">
                        <label for="acconto">Acconto</label>
                        <input type="text" class="form-control" id="acconto" name="acconto" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="rate">Nr. Rate</label>
                        <input type="text" class="form-control" id="rate" name="rate" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" id="assegnaPagamento" class="btn btn-primary">Assegna</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
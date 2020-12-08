<div class="modal fade" id="phoneClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nomeRecall"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('clients.phone.store')}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <input type="date" name="recall" id="recall" {{--value="{{$client->datarecall ? $client->datarecall : ''}}"--}} >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
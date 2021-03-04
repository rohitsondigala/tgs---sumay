<!-- Modal -->
<div class="modal fade" id="reject-{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reject Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => route($route.'.reject-post'),'method'=>'post']) !!}
                {!! Form::hidden('uuid',$query->uuid) !!}
                <h4 class="text-center">Are you sure?</h4>
                <br>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@if(!empty($subjects) && !$subjects->isEmpty())

    <table id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody>
    @forelse($subjects as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{!empty($list->stream) ? $list->stream->title : 'NA'}}</td>
            <td>{{$list->title}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($subjects,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}
            {!! Form::close() !!}
        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>
@else
    @include('common.no-record-found')
@endif


<table class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="4"><input type="text" class="form-control" placeholder="Search Words by stream"  wire:model="searchWords"></th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Is Standard')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody >
    @if(!empty($streams) && !$streams->isEmpty())

        @foreach($streams as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->title}}</td>
            <td>{{$list->is_standard}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
{{--                <span>|</span>--}}
{{--                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>--}}
{{--            {!! Form::model($streams,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}--}}
{{--            {!! Form::close() !!}--}}
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>

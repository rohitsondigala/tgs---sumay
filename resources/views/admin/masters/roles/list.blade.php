<table id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Code')}}</th>
    </tr>
    </thead>

    <tbody>
    @forelse($countries as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->title}}</td>
            <td>{{$list->role}}</td>
{{--            <td>--}}
{{--                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>--}}
{{--                <span>|</span>--}}
{{--                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>--}}
{{--            {!! Form::model($countries,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}--}}
{{--            {!! Form::close() !!}--}}
        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>

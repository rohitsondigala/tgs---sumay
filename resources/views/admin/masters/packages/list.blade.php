@if(!empty($packages) && !$packages->isEmpty())
<table  id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Description')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($packages as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->title}}</td>
            <td>{{$list->description}}</td>
            <td>{{$list->stream->title}}</td>
            <td>{{$list->subject->title}}</td>
            <td>
                <a href="{{route($route.'.show',$list->uuid)}}">View </a>
                <span>|</span>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
{{--                <span>|</span>--}}
{{--                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>--}}
{{--            {!! Form::model($packages,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}--}}
{{--            {!! Form::close() !!}--}}
        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>
@else
    @include('common.no-record-found')
@endif

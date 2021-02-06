@if(!empty($streams) && !$streams->isEmpty())
{{--    <div class="col-md-12 text-right">--}}
{{--       Search : <input type="text" name="search" class="search-data" data-search-url="{{route('admin.search','streams')}}" data-route-url="{{$route}}"/>--}}
{{--    </div>--}}
{{--    <br>--}}
<table  id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Is Standard')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($streams as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->title}}</td>
            <td>{{$list->is_standard}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($streams,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}
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

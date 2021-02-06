@if(!empty($users) && !$users->isEmpty())
{{--    <div class="col-md-12 text-right">--}}
{{--       Search : <input type="text" name="search" class="search-data" data-search-url="{{route('admin.search','streams')}}" data-route-url="{{$route}}"/>--}}
{{--    </div>--}}
{{--    <br>--}}
<table  id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Mobile')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($users as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{!empty($list->moderator) ? $list->moderator->stream->title : 'NA'}}</td>
            <td>{{!empty($list->moderator) ? $list->moderator->subject->title : 'NA'}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->email}}</td>
            <td>{{$list->mobile}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($users,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}
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

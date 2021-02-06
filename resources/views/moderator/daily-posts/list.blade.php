@if(!empty($dailyPosts) && !$dailyPosts->isEmpty())
{{--    <div class="col-md-12 text-right">--}}
{{--       Search : <input type="text" name="search" class="search-data" data-search-url="{{route('admin.search','streams')}}" data-route-url="{{$route}}"/>--}}
{{--    </div>--}}
{{--    <br>--}}
<table  id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Description')}}</th>
        <th>{{__('image')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($dailyPosts as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->title}}</td>
            <td>{{$list->description}}</td>
            <td>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#image-{{$list->id}}">View Image</button>
                <div class="modal fade" id="image-{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="image-{{$list->id}}Label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="{{$list->image}}" style="width: 100%">
                            </div>

                        </div>
                    </div>
                </div>

            </td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($dailyPosts,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}
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

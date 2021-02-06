<table id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('CountryName')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody>
    @forelse($states as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{!empty($list->country) ? $list->country->name : 'NA'}}</td>
            <td>{{$list->name}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->id)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->id}}' href="javascript:;">Delete</a>
            {!! Form::model($states,array('url'=>route($route.'.destroy',$list->id),'method'=>'DELETE','class'=>'delete-form-'.$list->id)) !!}
            {!! Form::close() !!}
        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>

<table id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('SortName')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('PhoneCode')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody>
    @forelse($countries as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->sortname}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->phonecode}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->id)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->id}}' href="javascript:;">Delete</a>
            {!! Form::model($countries,array('url'=>route($route.'.destroy',$list->id),'method'=>'DELETE','class'=>'delete-form-'.$list->id)) !!}
            {!! Form::close() !!}
        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>

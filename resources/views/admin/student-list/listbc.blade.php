
<table   class="table nowrap" style="width:100%">
    <thead>
    <tr>
        {!! Form::open(['url'=>route($route.".index"),'method'=>'get','id'=>'filter-form']) !!}
        <th>&nbsp;</th>
        <th>{!! Form::select('user_uuid',$userList,$user_uuid,['class'=>'form-control select2 filterChange','id'=>'studentUserList']) !!}</th>
        <th></th>
{{--        <th>{!! Form::select('email',$userEmailList,$email,['class'=>'form-control select2 filterChange','id'=>'studentEmailList']) !!}</th>--}}
        <th>{!! Form::select('stream_uuid',$streamList,$stream_uuid,['class'=>'form-control select2 filterChange','id'=>'studentStreamList']) !!}</th>
        <th>{!! Form::select('subject_uuid',$subjectList,$subject_uuid,['class'=>'form-control select2 filterChange','id'=>'studentSubjectList']) !!}</th>
{{--        <th>{!! Form::select('status',['all'=> 'All','0'=>'Pending','1'=>'Approved','2'=>'Rejected'],$status,['class'=>'form-control select2 filterChange']) !!}</th>--}}
        <th>

        <a href="{{route('admin.student.index')}}" class="btn btn-info">Clear</a> </th>
        {!! Form::close(); !!}
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subjects')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($students as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->email}}</td>
            <td>{{$list->stream->title}}</td>
            <td>
                @if(!empty($list->student_subjects))
                    @foreach($list->student_subjects as $subjectList)
                        {{$loop->iteration}} - {{$subjectList->subject->title}}<br>
                    @endforeach
                @else
                @endif
            </td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($students,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}
            {!! Form::close() !!}
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No record found !!</td>
        </tr>    @endforelse
    </tbody>
</table>


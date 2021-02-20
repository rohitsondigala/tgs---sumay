<table   class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="6"><input type="text" class="form-control" placeholder="Search Words by name,email,stream or subject"  wire:model="searchWords"></th>
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
    @foreach($students as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->email}}</td>
            <td>{{!empty($list->stream) ? $list->stream->title : 'NA'}}</td>
            <td>
                @if(!empty($list->student_subjects))
                    @foreach($list->student_subjects as $subjectList)
                        {{$loop->iteration}} - {{!empty($subjectList->subject) ? $subjectList->subject->title : ''}}<br>
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

    @endforeach
    </tbody>
</table>

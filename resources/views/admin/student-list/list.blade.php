<table   class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="3"><input type="text" class="form-control" placeholder="Search words by name,email,stream or subject"  wire:model="searchWords"></th>

        <th colspan="4" >@if(!empty($subjectList))

                                 <select class="form-control" wire:model="subjectUuid">
                                     @foreach($subjectList as $key =>  $subject)
                                         <option value="{{$key}}" >{{$subject}}</option>
                                     @endforeach
                                 </select>
        @endif</th>

    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subjects')}}</th>
        <th width="1%">{{__('Posts')}}</th>
        <th width="1%">{{__('Queries')}}</th>
        <th width="1%">{{__('Total')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @if(!empty($students) && !$students->isEmpty())

        @foreach($students as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><div class="media pb-3 align-items-center justify-content-between">
                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-primary text-white">
                        <div  style="background-image: url({{asset($list->image_placeholder)}}); width: 100%;
                            height: 100%;
                            background-size: cover;
                            background-position: center;
                            border-radius: 100%;"></div>
                    </div>
                    <div class="media-body pr-3 ">
                        <a class="mt-0 mb-1 font-size-15 text-dark" href="#">{{$list->name}}</a>
                        <p>{{$list->mobile}}</p>
                        <p>{{$list->email}}</p>
                    </div>
                </div>
            </td>

            <td>{{!empty($list->stream) ? $list->stream->title : 'NA'}}</td>
            <td>
                @if(!empty($list->student_subjects))
                    @foreach($list->student_subjects as $subjectList)
                        {{$loop->iteration}} - {{!empty($subjectList->subject) ? $subjectList->subject->title : ''}}
{{--                        - {{$subjectList->is_purchased}}--}}
                        <br>
                    @endforeach
                @else
                @endif
            </td>
            @php($notes_count = $list->notes()->where('approve',1)->count())
            @php($queries_count = $list->student_post_queries()->where('approve',1)->count())
            <td>{{$notes_count}}</td>
            <td>{{$queries_count}}</td>
            <td>{{$notes_count + $queries_count}}</td>
            <td>
                <a href="{{route($route.'.show',[$list->uuid, 'type'=>'ACTIVITIES'])}}">Activities </a>
                <span>|</span>
                <a href="{{route($route.'.show',$list->uuid)}}">View </a>
                <span>|</span>
                <a class="delete-item text-danger" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
                {!! Form::model($students,array('url'=>route($route.'.delete-student',$list->uuid),'method'=>'POST','class'=>'delete-form-'.$list->uuid)) !!}
                {!! Form::close() !!}
            </td>
        </tr>

    @endforeach
    @else
        <tr>
            <td colspan="6" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>


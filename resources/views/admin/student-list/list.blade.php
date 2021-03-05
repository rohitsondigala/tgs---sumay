<table   class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="6"><input type="text" class="form-control" placeholder="Search words by name,email,stream or subject"  wire:model="searchWords"></th>
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
                    </div>
                </div>
            </td>
            <td>{{$list->email}}</td>
            <td>{{!empty($list->stream) ? $list->stream->title : 'NA'}}</td>
            <td>
                @if(!empty($list->student_subjects))
                    @foreach($list->student_subjects as $subjectList)
                        {{$loop->iteration}} - {{!empty($subjectList->subject) ? $subjectList->subject->title : ''}}
                        - {{$subjectList->is_purchased}}
                        <br>
                    @endforeach
                @else
                @endif
            </td>
            <td>
                <a href="{{route($route.'.show',[$list->uuid, 'type'=>'ACTIVITIES'])}}">Activities </a>
                <span>|</span>
                <a href="{{route($route.'.show',$list->uuid)}}">View </a>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>

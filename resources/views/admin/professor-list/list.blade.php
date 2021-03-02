<table  class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="6"><input type="text" class="form-control" placeholder="Search Words by name,email,subject"  wire:model="searchWords"></th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @if(!empty($professors) && !$professors->isEmpty())

        @foreach($professors as $list)
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
                    @if(!empty($list->professor_subjects))
                        @foreach($list->professor_subjects as $subjectList)
                           <b>{{$subjectList->subject->title}}</b><br>
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
    @else
        <tr>
            <td colspan="6" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>


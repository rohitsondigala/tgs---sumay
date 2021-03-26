<table class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="3"><input type="text" class="form-control" placeholder="Search Words by name,email,subject"
                               wire:model="searchWords"></th>
        <th colspan="2" >@if(!empty($subjectList))

                    <select class="form-control" wire:model="subjectUuid" >
                        @foreach($subjectList as $key =>  $subject)
                            <option value="{{$key}}" >{{$subject}}</option>
                        @endforeach
                    </select>
            @endif</th>
        <th colspan="1" >
            <select class="form-control" wire:model="rating">
                <option value="0">Rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Rating')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @if(!empty($professors) && !$professors->isEmpty())

        @foreach($professors as $list)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    <div class="media pb-3 align-items-center justify-content-between">
                        <div
                            class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-primary text-white">
                            <div style="background-image: url({{asset($list->image_placeholder)}}); width: 100%;
                                height: 100%;
                                background-size: cover;
                                background-position: center;
                                border-radius: 100%;"></div>
                        </div>
                        <div class="media-body pr-3 ">
                            <a class="mt-0 mb-1 font-size-15 text-dark" href="#">{{$list->name}}   </a>
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
{{--                <td><span class="badge badge-warning"><i class="mdi mdi-star"></i> {{number_format($list->reviews()->avg('rating'),1)}}</span>--}}
                <td><span class="badge badge-warning"><i class="mdi mdi-star"></i> {{number_format($list->average_rating,1)}}</span>
                </td>

                <td>
                    <a href="{{route($route.'.show',[$list->uuid, 'type'=>'ACTIVITIES'])}}">Activities </a>
                    <span>|</span>
                    <a href="{{route($route.'.show',$list->uuid)}}">View </a>
                    <span>|</span>
                    <a class="delete-item text-danger" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
                    {!! Form::model($professors,array('url'=>route($route.'.delete-professor',$list->uuid),'method'=>'POST','class'=>'delete-form-'.$list->uuid)) !!}
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


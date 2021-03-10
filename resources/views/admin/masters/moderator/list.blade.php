
<table   class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="3"><input type="text" class="form-control" placeholder="Search words by name,email,stream or subject"  wire:model="searchWords"></th>

        <th colspan="5" >@if(!empty($subjectList))

                <select class="form-control" wire:model="subjectUuid">
                    @foreach($subjectList as $key =>  $subject)
                        <option value="{{$key}}" >{{$subject}}</option>
                    @endforeach
                </select>
            @endif</th>

    </tr>
    <tr>
        <th width="1%">{{__('No')}}</th>
        <th width="5%">{{__('Name')}}</th>
        <th width="5%">{{__('Stream')}}</th>
        <th width="15%">{{__('Subject')}}</th>
        <th width="1%">{{__('Posts')}}</th>
        <th width="1%">{{__('Queries')}}</th>
        <th width="1%">{{__('Total')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @if(!empty($users) && !$users->isEmpty())

        @foreach($users as $list)
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
            <td>{{!empty($list->moderator) ? $list->moderator->stream->title : 'NA'}}</td>
            <td>{{!empty($list->moderator) ? $list->moderator->subject->title : 'NA'}}</td>
            @php($total_notes = $list->moderator_approved_notes()->count())
            @php($total_queries = $list->moderator_approved_queries()->count())
            <td>{{$total_notes}}</td>
            <td>{{$total_queries}}</td>
            <td>{{$total_notes + $total_queries}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                <span>|</span>
                <a class="delete-item text-danger" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>
            {!! Form::model($users,array('url'=>route($route.'.delete-moderator',$list->uuid),'method'=>'POST','class'=>'delete-form-'.$list->uuid)) !!}
            {!! Form::close() !!}
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>


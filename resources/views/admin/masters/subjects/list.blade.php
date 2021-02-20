




<table class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="4"><input type="text" class="form-control" placeholder="Search Words by stream or subject"  wire:model="searchWords"></th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th width="20%">{{__('Stream')}}</th>
        <th width="50%">{{__('Subject')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody>

   @if(!empty($subjects) && !$subjects->isEmpty())
       @foreach($subjects as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{!empty($list->stream) ? $list->stream->title : 'NA'}}</td>
            <td>{{$list->title}}</td>
            <td>
                <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
{{--                <span>|</span>--}}
{{--                <a class="delete-item" data-delete='delete-form-{{$list->uuid}}' href="javascript:;">Delete</a>--}}
{{--            {!! Form::model($subjects,array('url'=>route($route.'.destroy',$list->uuid),'method'=>'DELETE','class'=>'delete-form-'.$list->uuid)) !!}--}}
{{--            {!! Form::close() !!}--}}
        </tr>
     @endforeach
   @else
       <tr>
           <td colspan="4" class="text-center">No Record Found</td>
       </tr>
    @endif

    </tbody>
</table>

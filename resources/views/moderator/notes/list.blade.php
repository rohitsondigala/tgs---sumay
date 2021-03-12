
{{--    <table id="basic-data-table" class="table nowrap" style="width:100%">--}}

        <table   class="table nowrap" style="width:100%">
        <thead>
        <tr>
            {!! Form::open(['url'=>route($route.".index"),'method'=>'get','id'=>'filter-form']) !!}
            <th>&nbsp;</th>
            <th>{!! Form::select('user_type',['all'=> 'All','PROFESSOR'=>'PROFESSOR','STUDENT'=>'STUDENT'],$user_type,['class'=>'form-control select2 filterChange','id'=>'userTypeList']) !!}</th>
            <th>{!! Form::select('user_uuid',$notesUserList,$user_uuid,['class'=>'form-control select2 filterChange','id'=>'userList']) !!}</th>
            <th>{!! Form::text('title',$title,['class'=>'form-control']) !!}</th>
            <th>{!! Form::select('status',['all'=> 'All','0'=>'Pending','1'=>'Approved','2'=>'Rejected'],$status,['class'=>'form-control select2 filterChange']) !!}</th>
            <th>{!! Form::submit('Filter',['class'=>'btn btn-primary']) !!}</th>
            {!! Form::close(); !!}
        </tr>
        <tr>
            <th>{{__('No')}}</th>
            <th>{{__('User Type')}}</th>
            <th>{{__('Uploaded By')}}</th>
            <th>{{__('Title')}}</th>
            <th>{{__('Action')}}</th>
            <th>{{__('Approve?')}}</th>
        </tr>
        </thead>

        <tbody class="search-results">
        @forelse($notes as $note)
            <tr class="{{($note->approve == 1 ||  $note->approve == 2) ? 'read_status' : 'not_read_status'}}">
                <td>{{$loop->iteration}}</td>
                <td><span
                        class="badge {{$note->user->role->title == 'STUDENT' ? 'badge-primary' : 'badge-success'}}"> {{$note->user->role->title}}</span>
                </td>
                <td>{{$note->user->name}}</td>
                <td><a class="text-primary" href="{{route($route.'.show',$note->uuid)}}">{{$note->title}}</a></td>
                <td>
                    <a class="text-info" href="{{route($route.'.show',$note->uuid)}}"> View Detail </a>
                </td>
                <td>
                    {!! getVerifyBadge($note->approve) !!}
                    @if($note->approve == 0 || $note->approve ==4)
                        <a class="btn btn-sm btn-success text-white" data-toggle="modal"
                           data-target="#approve-{{$note->id}}" title="APPROVE">
                            <i class="mdi mdi-check"></i>
                        </a>
                        @include($route.'.approve')
                        <a class="btn btn-sm btn-danger text-white" data-toggle="modal"
                           data-target="#reject-{{$note->id}}" title="REJECT">
                            <i class="mdi mdi-close"></i>
                        </a>

                        @include($route.'.reject')
                    @endif

                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No record found !!</td>
            </tr>
        @endforelse
        </tbody>
    </table>


<div class="text-center">
    {{$notes->links()}}
</div>

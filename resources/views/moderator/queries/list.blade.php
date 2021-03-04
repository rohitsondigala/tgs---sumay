
{{--    <table id="basic-data-table" class="table nowrap" style="width:100%">--}}

        <table   class="table nowrap" style="width:100%">
        <thead>
        <tr>
            {!! Form::open(['url'=>route($route.".index"),'method'=>'get','id'=>'filter-form']) !!}
            <th>&nbsp;</th>
            <th>{!! Form::select('from_user_uuid',$from_user_query_list,$from_user_uuid,['class'=>'form-control select2 filterChange','id'=>'fromUserList']) !!}</th>
            <th>{!! Form::select('to_user_uuid',$to_user_query_list,$to_user_uuid,['class'=>'form-control select2 filterChange','id'=>'toUserList']) !!}</th>
            <th>{!! Form::text('title',$title,['class'=>'form-control']) !!}</th>
            <th>{!! Form::select('status',['all'=> 'All','0'=>'Pending','1'=>'Approved','2'=>'Rejected'],$status,['class'=>'form-control select2 filterChange']) !!}</th>
            <th>{!! Form::submit('Filter',['class'=>'btn btn-primary']) !!}</th>
            {!! Form::close(); !!}
        </tr>
        <tr>
            <th>{{__('No')}}</th>
            <th>{{__('Student Name')}}</th>
            <th>{{__('Professor Name')}}</th>
            <th>{{__('Title')}}</th>
            <th>{{__('Approve?')}}</th>
            <th>{{__('Action')}}</th>
        </tr>
        </thead>

        <tbody class="search-results">
        @forelse($queries as $query)
            <tr class="{{($query->approve == 1 ||  $query->approve == 2) ? 'read_status' : 'not_read_status'}}">
                <td>{{$loop->iteration}}</td>

                <td>{{$query->from_user->name}}</td>
                <td>{{$query->to_user->name}}</td>
                <td><a class="text-primary" href="{{route($route.'.show',$query->uuid)}}">{{$query->title}}</a></td>
                <td>
                    {!! getVerifyBadge($query->approve) !!}
                    @if($query->approve == 0 || $query->approve ==4)
                        <a class="btn btn-sm btn-success text-white" data-toggle="modal"
                           data-target="#approve-{{$query->id}}" title="APPROVE">
                            <i class="mdi mdi-check"></i>
                        </a>
                        @include($route.'.approve')
                        <a class="btn btn-sm btn-danger text-white" data-toggle="modal"
                           data-target="#reject-{{$query->id}}" title="REJECT">
                            <i class="mdi mdi-close"></i>
                        </a>

                        @include($route.'.reject')
                    @endif

                </td>
                <td>
                    <a class="text-info" href="{{route($route.'.show',$query->uuid)}}"> View Detail </a>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No record found !!</td>
            </tr>
        @endforelse
        </tbody>
    </table>

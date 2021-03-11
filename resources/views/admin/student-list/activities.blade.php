@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">

        @include('common.globalAlerts')

        <div class="text-right">
            <a href="{{route($route.'.index')}}"><i class="mdi mdi-chevron-double-left"></i> Return To Students</a>
        </div>
        <br><div class="row">

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->notes()->count()}}</h2>
                            <p>{{__('Uploaded Notes')}}</p>

                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->student_post_queries()->count()}}</h2>
                            <p>{{__('Queries Posted')}}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->reviews()->count()}}</h2>
                            <p>{{__('Reviews')}}</p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}} - {{$detail->name}}</h2>
                <div class="text-right">
                    <a href="{{route($route.'.reviews',$detail->uuid)}}" class="btn btn-outline-info">View Reviews</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{__('Uploaded Notes')}}</h5>

                        <hr>
                        @if(!empty($notes) && $notes->IsNotEmpty())
                            <div id="content">
                                <ul class="timeline">
                                    @foreach($notes as $list)
                                        <li class="event"
                                            data-date="{{\Carbon\Carbon::parse($list->created_at)->format('d M Y')}}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12 text-right">

                                                            <a href="{{route('admin.moderator.edit',$list->approve_user->uuid)}}">
                                                                {{__('Approved By')}} : {{$list->approve_user->name}}
                                                            </a>
                                                            <br>
                                                            <small>
                                                                Date : {{getDateInFormat($list->updated_at)}}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <h3>{{$list->title}}</h3>
                                            <p>{{$list->subject->title}}</p>
                                            <p>{{\Illuminate\Support\Str::limit($list->description,50)}}</p>
                                            <a href="{{route("admin.notes.show",$list->uuid)}}">View Detail</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <div class="text-center">
                                    <a href="{{route('admin.notes.index',['user_uuid'=>$detail->uuid])}}" class="btn btn-outline-primary"> View All </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5>{{__('Posted Queries')}}</h5>
                        <hr>
                        @if(!empty($queries) && $queries->IsNotEmpty())
                            <div id="content">
                                <ul class="timeline">
                                    @foreach($queries as $list)
                                        <li class="event"
                                            data-date="{{\Carbon\Carbon::parse($list->created_at)->format('d M Y')}}">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="text-left text-uppercase text-primary"> {{$list->to_user->name}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        @if(!empty($list->post_reply))
                                                            <div class=" text-success">{{__('REPLIED')}}</div>
                                                        @else
                                                            <div class=" text-danger">{{__('PENDING REPLY')}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            <hr>  <h3>{{$list->title}}</h3>
                                            <p>{{\Illuminate\Support\Str::limit($list->description,50)}}</p>
                                            <a href="">View Detail</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <hr>
                                <div class="text-center">
                                    <a href="{{route('admin.queries.index',['user_uuid'=>$detail->uuid])}}" class="btn btn-outline-primary"> View All </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

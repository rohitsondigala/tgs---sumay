@section('PAGE_TITLE','Dashboard')
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-xl-3 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$getCounts['notes']}}</h2>
                            <p>{{__('Notes')}}</p>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini  mb-4">
                    <a href="{{route('admin.moderator.index')}}">
                        <div class="card-body">
                            <h2 class="mb-1">{{$getCounts['moderators']}}</h2>
                            <p>{{__('Moderators')}}</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <a href="{{route('admin.professor.index')}}">
                        <div class="card-body">
                            <h2 class="mb-1">{{$getCounts['professors']}}</h2>
                            <p>{{__('Professors')}}</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <a href="{{route('admin.student.index')}}">
                        <div class="card-body">
                            <h2 class="mb-1">{{$getCounts['students']}}</h2>
                            <p>{{__('Students')}}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

@if(!empty($latestNotes))
            <div class="row">
                <div class="col-12">
                    <!-- Recent Order Table -->
                    <div class="card card-table-border-none" id="recent-orders">
                        <div class="card-header justify-content-between">
                            <h2>Recent Notes</h2>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                                <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th class="d-none d-lg-table-cell">Date</th>
{{--                                    <th class="d-none d-lg-table-cell">Image Files</th>--}}
{{--                                    <th class="d-none d-lg-table-cell">Audio Files</th>--}}
{{--                                    <th class="d-none d-lg-table-cell">PDF Files</th>--}}
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($latestNotes as $note)
                                <tr>
                                    <td>{{$note->user->role->title}}</td>
                                    <td>
                                        <a class="text-dark" href="">{{$note->user->name}}</a>
                                    </td>
                                    <td>{{$note->title}}</td>
                                    <td class="d-none d-lg-table-cell">{{getDateInFormat($note->created_at)}}</td>
{{--                                    <td class="d-none d-lg-table-cell text-center">{{$note->image_files()->count()}}</td>--}}
{{--                                    <td class="d-none d-lg-table-cell text-center">{{$note->audio_files()->count()}}</td>--}}
{{--                                    <td class="d-none d-lg-table-cell text-center">{{$note->pdf_files()->count()}}</td>--}}
                                    <td>
                                        {!! getVerifyBadge($note->approve) !!}
                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @if(!empty($latestStudents))
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                    <!-- New Customers -->
                    <div class="card card-table-border-none" data-scroll-height="580"
                         style="height: 580px; overflow: hidden;">
                        <div class="card-header justify-content-between ">
                            <h2>{{__('New Students')}}</h2>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table ">
                                <tbody>
                                @foreach($latestStudents as $list)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="media-image mr-3 rounded-circle">
                                                    <a href=""><img class="rounded-circle w-45"
                                                                    src="{{$list->image_placeholder}}"
                                                                    alt="{{$list->name}}"></a>
                                                </div>
                                                <div class="media-body align-self-center">
                                                    <a href="profile.html"><h6
                                                            class="mt-0 text-dark font-weight-medium">{{$list->name}}</h6>
                                                    </a>
                                                    <small>{{$list->email}}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$list->stream->title}}</td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            @if(!empty($latestProfessors))
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                    <!-- New Customers -->
                    <div class="card card-table-border-none" data-scroll-height="580"
                         style="height: 580px; overflow: hidden;">
                        <div class="card-header justify-content-between ">
                            <h2>{{__('New Professors')}}</h2>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table ">
                                <tbody>
                                @foreach($latestProfessors as $list)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="media-image mr-3 rounded-circle">
                                                    <a href=""><img class="rounded-circle w-45"
                                                                    src="{{$list->image_placeholder}}"
                                                                    alt="{{$list->name}}"></a>
                                                </div>
                                                <div class="media-body align-self-center">
                                                    <a href="profile.html"><h6
                                                            class="mt-0 text-dark font-weight-medium">{{$list->name}}</h6>
                                                    </a>
                                                    <small>{{$list->email}}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$list->stream->title}}</td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        </div>
@endsection

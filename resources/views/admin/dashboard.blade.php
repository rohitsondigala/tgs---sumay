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
    </div>
@endsection

@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}} - {{$userDetail->name}}</h2>
                <a href="{{route('admin.professor.show',[$userDetail->uuid, 'type'=>'ACTIVITIES'])}}" class="btn btn-primary btn-sm">Back</a>

            </div>

            <div class="card-body">
                @livewire('admin.note-activity-list')
            </div>
        </div>
    </div>

@endsection

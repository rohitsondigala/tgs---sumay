@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
                <a href="{{route($route.'.create')}}" class="btn btn-primary">Add New</a>
            </div>

            <div class="card-body">
                @livewire('admin.moderator-list')
{{--                @include($directory.'.list')--}}
            </div>
        </div>
    </div>

@endsection

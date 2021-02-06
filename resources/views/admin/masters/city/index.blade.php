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
                <div class="row">
                    <div class="form-group col-md-4 mb-4">
                        {!! Form::select('country_id',$countries,null,['class'=>'form-control select2 change-country']) !!}
                        @if($errors->has('country_id'))
                            <span class="text text-danger">{{$errors->first('country_id')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-4 state-list">

                    </div>
            </div>
        </div>
    </div>

@endsection

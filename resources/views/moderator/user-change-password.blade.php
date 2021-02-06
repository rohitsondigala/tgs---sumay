@section('PAGE_TITLE',$pageTitle)
@extends('moderator.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                {!! Form::model($user,array('url'=>route('moderator.user-change-password'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                <div class="row">

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::password('current_password',['class'=>'form-control input-lg','placeholder'=>'Current Password']) !!}
                        @if($errors->has('current_password'))
                            <span class="text text-danger">{{$errors->first('current_password')}}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::password('new_password',['class'=>'form-control input-lg','placeholder'=>'New Password']) !!}
                        @if($errors->has('new_password'))
                            <span class="text text-danger">{{$errors->first('new_password')}}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::password('confirm_password',['class'=>'form-control input-lg','placeholder'=>'Confirm Password']) !!}
                        @if($errors->has('confirm_password'))
                            <span class="text text-danger">{{$errors->first('confirm_password')}}</span>
                        @endif
                    </div>      <div class="col-md-12"> <br></div>
                    <div class="col-md-12">
                        {!! Form::submit('Submit',['class'=>'btn btn-lg btn-primary ']) !!}
                        <a href="{{route('admin.dashboard')}}" class="btn btn-default">Cancel</a>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>

@endsection

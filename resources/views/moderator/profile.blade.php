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
                {!! Form::model($user,array('url'=>route('moderator.profile'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>{{__('Stream')}}</label>
                            {!! Form::text('subject',auth()->user()->moderator->stream->title,['class'=>'form-control input-lg','disabled']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{__('Subject')}}</label>
                            {!! Form::text('name',auth()->user()->moderator->subject->title,['class'=>'form-control input-lg','disabled']) !!}
                        </div>

                        <div class="form-group">
                            <label>{{__('Email')}}</label>
                            {!! Form::text('email',auth()->user()->email,['class'=>'form-control input-lg','placeholder'=>'Email','disabled']) !!}
                        </div>

                        <div class="form-group">
                            <label>{{__('Mobile')}}</label>
                            {!! Form::text('mobile',auth()->user()->mobile,['class'=>'form-control input-lg','placeholder'=>'Mobile','disabled']) !!}
                            @if($errors->has('mobile'))
                                <span class="text text-danger">{{$errors->first('mobile')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{__('Name')}}</label>
                            {!! Form::text('name',auth()->user()->name,['class'=>'form-control input-lg','placeholder'=>'Name']) !!}
                            @if($errors->has('name'))
                                <span class="text text-danger">{{$errors->first('name')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Select Country')}}</label>
                            {!! Form::select('country',$countries,auth()->user()->country,['class'=>'form-control select2','id'=>'country']) !!}
                            @if($errors->has('country'))
                                <span class="text text-danger">{{$errors->first('country')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{__('Select State')}}</label>
                            {!! Form::select('state',  $states ,auth()->user()->state,['class'=>'form-control select2','id'=>'state']) !!}
                            @if($errors->has('state'))
                                <span class="text text-danger">{{$errors->first('state')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{__('Select City')}}</label>
                            {!! Form::select('city',$cities,auth()->user()->city,['class'=>'form-control select2','id'=>'city']) !!}
                            @if($errors->has('city'))
                                <span class="text text-danger">{{$errors->first('city')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label>{{__('Photograph')}}</label><br>
                        {!! Form::file('image',['id'=>'photograph']) !!}<br>
                        @if($errors->has('image'))
                            <span class="text text-danger">{{$errors->first('image')}}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="row">

                            <div class="col-md-6" id="photograph-preview">
                                    <img src="{{user_image()}}" class="img-responsive" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"> <br></div>
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

@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-12 mt-3 ">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($user->uuid)
                    {!! Form::model($user,array('url'=>route($route.'.update',$user->uuid),'method'=>'PUT','files' => true,'enctype'=>'multipart/form-data')) !!}
                @else
                    {!! Form::model($user,array('url'=>route($route.'.store'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                @endif
                <div class="row">
                    <div class="form-group col-md-12 mb-4">
                        <label>{{__('Select Stream')}}</label>
                        {!! Form::select('stream_uuid',$streams,($user->uuid) ? $user->moderator->stream->uuid : null,['class'=>'form-control select2','id'=>'stream']) !!}
                        @if($errors->has('stream_uuid'))
                            <span class="text text-danger">{{$errors->first('stream_uuid')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 mb-4 subject-list">
                        <label>{{__('Select Subject')}}</label>
                        {!! Form::select('subject_uuid',($user->uuid) ? $subjects : [''=>'Select Subject'],($user->uuid) ? $user->moderator->subject->uuid : null,['class'=>'form-control select2','id'=>'subject']) !!}
                        @if($errors->has('subject_uuid'))
                            <span class="text text-danger">{{$errors->first('subject_uuid')}}</span>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label>{{__('Name')}}</label>
                                    {!! Form::text('name',null,['class'=>'form-control input-lg','placeholder'=>'John Doe']) !!}
                                    @if($errors->has('name'))
                                        <span class="text text-danger">{{$errors->first('name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                    <label>{{__('Email')}}</label>
                                    {!! Form::text('email',null,['class'=>'form-control input-lg','placeholder'=>'john@gmail.com']) !!}
                                    @if($errors->has('email'))
                                        <span class="text text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-4">
                                    <label>{{__('Phone Number')}}</label>
                                    {!! Form::text('mobile',null,['class'=>'form-control input-lg','placeholder'=>'9574785858']) !!}
                                    @if($errors->has('mobile'))
                                        <span class="text text-danger">{{$errors->first('mobile')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{__('Photograph')}}</label><br>
                                    {!! Form::file('image',['id'=>'photograph']) !!}<br>
                                    @if($errors->has('image'))
                                        <span class="text text-danger">{{$errors->first('image')}}</span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($user->uuid)
                                            <img src="{{$user->image}}" class="img-responsive" style="width: 100%;">
                                        @endif
                                    </div>
                                    <div class="col-md-6">

                                        <div id="photograph-preview">
                                            <img src="" class="img-responsive" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label>{{__('Select Country')}}</label>
                                    {!! Form::select('country',$countries,($user->uuid) ? $user->country : null,['class'=>'form-control select2','id'=>'country']) !!}
                                    @if($errors->has('country'))
                                        <span class="text text-danger">{{$errors->first('country')}}</span>
                                    @endif
                                </div>
                                <div class="form-group  mb-4 subject-list">
                                    <label>{{__('Select State')}}</label>
                                    {!! Form::select('state',($user->uuid) ? $states : [''=>'Select State'],($user->uuid) ? $user->state : null,['class'=>'form-control select2','id'=>'state']) !!}
                                    @if($errors->has('state'))
                                        <span class="text text-danger">{{$errors->first('state')}}</span>
                                    @endif
                                </div>
                                <div class="form-group  mb-4 subject-list">
                                    <label>{{__('Select City')}}</label>
                                    {!! Form::select('city',($user->uuid) ? $cities : [''=>'Select City'],($user->uuid) ? $user->city : null,['class'=>'form-control select2','id'=>'city']) !!}
                                    @if($errors->has('city'))
                                        <span class="text text-danger">{{$errors->first('city')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-right">
                        {!! Form::submit('Submit',['class'=>'btn  btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

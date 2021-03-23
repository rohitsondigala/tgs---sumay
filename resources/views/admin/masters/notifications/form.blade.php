@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-8 mt-3 offset-2">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($notification->uuid)
                    {!! Form::model($notification,array('url'=>route($route.'.update',$notification->uuid),'method'=>'PUT','files' => true,'enctype'=>'multipart/form-data')) !!}
                @else
                    {!! Form::model($notification,array('url'=>route($route.'.store'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                @endif
                <div class="row">


                    <div class="form-group col-md-12 ">
                        <label >
                            TO?
                        </label>
                        <ul class="list-unstyled list-inline">

                                <li class="d-inline-block mr-3">

                                    <label class="control outlined control-checkbox checkbox-primary">{{__('Professor')}}
                                        <input type="checkbox" name="professor" value="1">
                                        <div class="control-indicator"></div>
                                    </label>
                                </li>
                                <li class="d-inline-block mr-3">
                                    <label class="control outlined control-checkbox checkbox-primary">{{__('Student')}}
                                        <input type="checkbox" name="student" value="1" checked="checked">
                                        <div class="control-indicator"></div>
                                    </label>
                                </li>

                        </ul>

                        @if($errors->has('student'))
                            <span class="text text-danger">{{$errors->first('student')}}</span>
                        @endif

                    </div>
                    <div class="form-group col-md-12 mb-4">

                        {!! Form::text('title',null,['class'=>'form-control input-lg','placeholder'=>'Title']) !!}
                        @if($errors->has('title'))
                            <span class="text text-danger">{{$errors->first('title')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        {!! Form::textarea('description',null,['rows'=>2,'class'=>'form-control input-lg','placeholder'=>'Description']) !!}
                        @if($errors->has('description'))
                            <span class="text text-danger">{{$errors->first('description')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label>{{__('Image')}}</label><br>
                        {!! Form::file('image',['id'=>'photograph']) !!}<br>
                        @if($errors->has('image'))
                            <span class="text text-danger">{{$errors->first('image')}}</span>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6" id="photograph-preview">
                                    <img src="" class="img-responsive" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {!! Form::submit('Send',['class'=>'btn  btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

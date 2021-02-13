@section('PAGE_TITLE',$pageTitle)
@extends('moderator.template.main')
@section('content')
    <div class="col-md-8 mt-3 offset-2">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($dailyPost->uuid)
                    {!! Form::model($dailyPost,array('url'=>route($route.'.update',$dailyPost->uuid),'method'=>'PUT','files' => true,'enctype'=>'multipart/form-data')) !!}
                @else
                    {!! Form::model($dailyPost,array('url'=>route($route.'.store'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                @endif
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{__('Stream')}}</label><br>
                        {!! Form::text('stream',auth()->user()->moderator->stream->title,['class'=>'form-control input-lg','disabled']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{__('Subject')}}</label><br>
                        {!! Form::text('subject',auth()->user()->moderator->subject->title,['class'=>'form-control input-lg','disabled']) !!}
                    </div>
                    <div class="form-group col-md-12">
                        <label>{{__('Title')}}</label><br>
                        {!! Form::text('title',null,['class'=>'form-control input-lg','placeholder'=>'Enter Title']) !!}
                        @if($errors->has('title'))
                            <span class="text text-danger">{{$errors->first('title')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label>{{__('Description')}}</label><br>
                        {!! Form::textarea('description',null,['rows'=>2,'class'=>'form-control input-lg','placeholder'=>'Enter description']) !!}
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
                                @if($dailyPost->uuid)
                                    <img src="{{$dailyPost->image}}" class="img-responsive" style="width: 100%;">
                                @endif
                            </div>

                        </div>
                    </div>
                   <div class="col-md-12">
                       <hr>
                       {!! Form::submit('Submit',['class'=>'btn  btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    @include('common.globalAlerts')

    <div class="col-md-8 mt-3 offset-2">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($subject->id)
                    {!! Form::model($subject,array('url'=>route($route.'.update',$subject->uuid),'method'=>'PUT')) !!}
                @else
                    {!! Form::model($subject,array('url'=>route($route.'.store'),'method'=>'post')) !!}
                @endif
                <div class="row">

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::select('stream_uuid',$streams,null,['class'=>'form-control select2']) !!}
                        @if($errors->has('stream_uuid'))
                            <span class="text text-danger">{{$errors->first('stream_uuid')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        {!! Form::text('title',null,['class'=>'form-control input-lg','placeholder'=>'Botany,Hindi,English']) !!}
                        @if($errors->has('title'))
                            <span class="text text-danger">{{$errors->first('title')}}</span>
                        @endif
                    </div>

                    <div class="col-md-12">
                        {!! Form::submit('Submit',['class'=>'btn btn-lg btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

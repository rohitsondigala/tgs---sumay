@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-8 mt-3 offset-2">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($country->id)
                    {!! Form::model($country,array('url'=>route($route.'.update',$country->id),'method'=>'PUT')) !!}
                @else
                    {!! Form::model($country,array('url'=>route($route.'.store'),'method'=>'post')) !!}
                @endif
                <div class="row">

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::text('sortname',null,['class'=>'form-control input-lg','placeholder'=>'Sort Name']) !!}
                        @if($errors->has('sortname'))
                            <span class="text text-danger">{{$errors->first('sortname')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        {!! Form::text('name',null,['class'=>'form-control input-lg','placeholder'=>'Name']) !!}
                        @if($errors->has('name'))
                            <span class="text text-danger">{{$errors->first('name')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 ">
                        {!! Form::text('phonecode',null,['class'=>'form-control input-lg','placeholder'=>'PhoneCode']) !!}
                        @if($errors->has('phonecode'))
                            <span class="text text-danger">{{$errors->first('phonecode')}}</span>
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

@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-12 mt-3 ">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($purchasedPackage->uuid)
                    {!! Form::model($purchasedPackage,array('url'=>route($route.'.update',$purchasedPackage->uuid),'method'=>'PUT','files' => true,'enctype'=>'multipart/form-data')) !!}
                @else
                    {!! Form::model($purchasedPackage,array('url'=>route($route.'.store'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">

                            <div class="form-group col-md-12 mb-4">
                                <label> {{__('Student')}}</label>
                                {!! Form::select('user_uuid',$students,null,['class'=>'form-control select2','id'=>'studentListPackage']) !!}
                                @if($errors->has('user_uuid'))
                                    <span class="text text-danger">{{$errors->first('user_uuid')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label> {{__('Package')}}</label>
                                {!! Form::select('package_uuid',[''=>'Package'],null,['class'=>'form-control select2','id'=>'packageListStream']) !!}
                                @if($errors->has('package_uuid'))
                                    <span class="text text-danger">{{$errors->first('package_uuid')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label> {{__('Duration')}}</label>
                                {!! Form::select('duration_month',[''=>'Duration'],null,['class'=>'form-control select2','id'=>'packagePrice']) !!}
                                @if($errors->has('duration_month'))
                                    <span class="text text-danger">{{$errors->first('duration_month')}}</span>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 text-left">
                        {!! Form::submit('Submit',['class'=>'btn  btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

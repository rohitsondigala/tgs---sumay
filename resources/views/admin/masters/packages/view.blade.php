@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-12 mt-3 ">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
                <a href="{{route($route.".index")}}" class="btn btn-primary">Back</a>

            </div>

            <div class="card-body">
                @if($package->uuid)
                    {!! Form::model($package,array('url'=>route($route.'.update',$package->uuid),'method'=>'PUT','files' => true,'enctype'=>'multipart/form-data')) !!}
                @else
                    {!! Form::model($package,array('url'=>route($route.'.store'),'method'=>'post','files' => true,'enctype'=>'multipart/form-data')) !!}
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{__('Basic Detail')}}</h4>
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Package Name')}}</label>
                                {!! Form::text('title',null,['class'=>'form-control input-lg','placeholder'=>'Title','disabled']) !!}
                                @if($errors->has('title'))
                                    <span class="text text-danger">{{$errors->first('title')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{__('Description')}}</label><br>
                                {!! Form::textarea('description',null,['rows'=>2,'class'=>'form-control input-lg','placeholder'=>'Enter description','disabled']) !!}
                                @if($errors->has('description'))
                                    <span class="text text-danger">{{$errors->first('description')}}</span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6" id="photograph-preview">
                                        @if($package->uuid)
                                            <img src="{{$package->image}}" class="img-responsive" style="width: 100%;">
                                        @else
                                            <img src="" class="img-responsive" style="width: 100%;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="row">

                            @if(!$package->uuid)
                                <div class="col-md-12">
                                    <h4>{{__('Select Subjects')}}</h4>
                                    <hr>
                                </div>
                            <div class="form-group col-md-12 mb-4">
                                <label> {{__('Select Stream')}}</label>
                                {!! Form::select('stream_uuid',$streams,null,['class'=>'form-control select2','id'=>'streamList']) !!}
                                @if($errors->has('stream_uuid'))
                                    <span class="text text-danger">{{$errors->first('stream_uuid')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4" id="subjectList" style="height: 400px;
overflow-x: scroll;">

                                @if($errors->has('subjects'))
                                    <span class="text text-danger">{{$errors->first('subjects')}}</span>
                                @endif
                            </div>
                            @else
                                <div class="col-md-12">
                                    <h4>{{__('Selected Subjects')}}</h4>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <ul class="list-group">
                                    @forelse($package->subjects as $list)
                                            <li class="list-group-item font-size-16 text-dark">{{$list->subject->title}}</li>
                                    @empty
                                        NA
                                    @endforelse
                                    </ul>

                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{__('Add Prices')}}</h4>
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Price for 3 Months')}}</label>
                                {!! Form::text('price_month_3',null,['class'=>'form-control input-lg','placeholder'=>'Amount','disabled']) !!}
                                @if($errors->has('price_month_3'))
                                    <span class="text text-danger">{{$errors->first('price_month_3')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Price for 6 Months')}}</label>
                                {!! Form::text('price_month_6',null,['class'=>'form-control input-lg','placeholder'=>'Amount','disabled']) !!}
                                @if($errors->has('price_month_6'))
                                    <span class="text text-danger">{{$errors->first('price_month_6')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Price for 12 Months')}}</label>
                                {!! Form::text('price_month_12',null,['class'=>'form-control input-lg','placeholder'=>'Amount','disabled']) !!}
                                @if($errors->has('price_month_12'))
                                    <span class="text text-danger">{{$errors->first('price_month_12')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Price for 24 Months')}}</label>
                                {!! Form::text('price_month_24',null,['class'=>'form-control input-lg','placeholder'=>'Amount','disabled']) !!}
                                @if($errors->has('price_month_24'))
                                    <span class="text text-danger">{{$errors->first('price_month_24')}}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label>{{__('Price for 36 Months')}}</label>
                                {!! Form::text('price_month_36',null,['class'=>'form-control input-lg','placeholder'=>'Amount','disabled']) !!}
                                @if($errors->has('price_month_36'))
                                    <span class="text text-danger">{{$errors->first('price_month_6')}}</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

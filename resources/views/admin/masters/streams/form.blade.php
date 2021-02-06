@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-md-8 mt-3 offset-2">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                @if($stream->uuid)
                    {!! Form::model($stream,array('url'=>route($route.'.update',$stream->uuid),'method'=>'PUT')) !!}
                @else
                    {!! Form::model($stream,array('url'=>route($route.'.store'),'method'=>'post')) !!}
                @endif
                <div class="row">

                    <div class="form-group col-md-12 mb-4">
                        {!! Form::text('title',null,['class'=>'form-control input-lg','placeholder'=>'Science, Arts, Commerce']) !!}
                        @if($errors->has('title'))
                            <span class="text text-danger">{{$errors->first('title')}}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        <label >
                            Is Standard?
                        </label>
                        <ul class="list-unstyled list-inline">
                            @if($stream->uuid)
                            <li class="d-inline-block mr-3">

                                <label class="control outlined control-radio radio-primary">Yes
                                    <input type="radio" name="is_standard" value="1" {{($stream->is_standard == 1) ? 'checked' : ''}}>
                                    <div class="control-indicator"></div>
                                </label>
                            </li>
                            <li class="d-inline-block mr-3">

                                <label class="control outlined control-radio radio-primary">No
                                    <input type="radio" name="is_standard" value="0" {{($stream->is_standard == 0) ? 'checked' : ''}}>
                                    <div class="control-indicator"></div>
                                </label>
                            </li>
                            @else
                                <li class="d-inline-block mr-3">

                                    <label class="control outlined control-radio radio-primary">Yes
                                        <input type="radio" name="is_standard" value="1">
                                        <div class="control-indicator"></div>
                                    </label>
                                </li>
                                <li class="d-inline-block mr-3">

                                    <label class="control outlined control-radio radio-primary">No
                                        <input type="radio" name="is_standard" value="0" checked="checked">
                                        <div class="control-indicator"></div>
                                    </label>
                                </li>
                            @endif

                        </ul>

                        @if($errors->has('is_standard'))
                            <span class="text text-danger">{{$errors->first('is_standard')}}</span>
                        @endif
                    </div>
                    <div class="col-md-12">
                        {!! Form::submit('Submit',['class'=>'btn  btn-primary ']) !!}
                        <a href="{{route($route.".index")}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

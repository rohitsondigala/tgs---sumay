@section('PAGE_TITLE','Login')
@include('admin.template.head')
    <div class="container d-flex flex-column justify-content-between vh-100">
        <div class="row justify-content-center mt-5">
            <div class="col-xl-5 col-lg-6 col-md-10">
                <div class="card">
                    <div class="app-brand" style="padding: 10px 39px;">
                        <a href="/">
                            <img src="{{logo()}}">

                            <span class="brand-name">{{env('APP_NAME')}}</span>
                        </a>
                    </div>
                    <div class="card-header bg-primary">

                    </div>
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-12">
                                @if(session()->has('message'))
                                    <div class="alert {{session()->get('class')}}">{{session()->get('message')}}</div>
                                @endif
                            </div>
                        </div>
                        @if(!auth()->check())
                        <h4 class="text-dark mb-3">Sign In</h4>
                        {!! Form::open(['route'=>'login','method'=>'post']) !!}
                            <div class="row">

                                <div class="form-group col-md-12 mb-4">
                                    {!! Form::text('email',null,['class'=>'form-control input-lg','placeholder'=>'Email']) !!}
                                    @if($errors->has('email'))
                                        <span class="text text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-12 ">
                                    {!! Form::password('password',['class'=>'form-control input-lg','placeholder'=>'Password']) !!}
                                    @if($errors->has('password'))
                                        <span class="text text-danger">{{$errors->first('password')}}</span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex my-2 justify-content-between">
                                        <div class="d-inline-block mr-3">

                                        </div>
                                        <p><a class="text-blue" href="/forgot-password">Forgot Your Password?</a></p>
                                    </div>
                                    {!! Form::submit('Submit',['class'=>'btn btn-lg btn-primary btn-block mb-4']) !!}

                                </div>
                            </div>
                        {!! Form::close() !!}
                        @else
                            <div class="profile-content-left ">
                                <div class="card text-center widget-profile px-0 border-0">
                                    <div class="card-img mx-auto rounded-circle">
                                        <img src="{{user_image()}}" alt="{{auth()->user()->name}}" style="width: 100%;">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="py-2 text-dark">{{auth()->user()->name}}</h4>
                                        <p>{{auth()->user()->email}}</p>
                                        <a class="btn btn-primary btn-pill btn-lg my-4" href="{{route('index')}}">{{__('Go To Dashboard')}}</a>
                                    </div>
                                </div>

                            </div>
                       @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


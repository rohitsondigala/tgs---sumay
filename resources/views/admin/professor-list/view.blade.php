@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')
        <div class="text-right">
            <a href="{{route($route.'.index')}}"><i class="mdi mdi-chevron-double-left"></i> Return To Professors</a>
        </div>
        <br>
        <div class="row">

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->notes()->count()}}</h2>
                            <p>{{__('Uploaded Notes')}}</p>

                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->professor_post_queries()->count()}}</h2>
                            <p>{{__('Queries Recieved')}}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-sm-6">
                <a href="">
                    <div class="card card-mini mb-4">
                        <div class="card-body">
                            <h2 class="mb-1">{{$detail->reviews()->count()}}</h2>
                            <p>{{__('Reviews')}}</p>

                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}} - {{$detail->name}}</h2>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td>{{__('Subjects')}}</td>
                        <td>@if(!empty($detail->professor_subjects))
                                @foreach($detail->professor_subjects as $subjectList)
                                    <b>{{$subjectList->subject->title}}</b><br>
                                @endforeach
                            @else
                            @endif</td>
                    </tr>
                </table>
                <hr>
                <h6>{{__('Personal Details')}}</h6>
                <br>
                <table class="table table-bordered">
                    <tr>
                        <td>{{__('Name')}}</td>
                        <td>
{{--                            <div class="media pb-3 align-items-center justify-content-between">--}}
{{--                                <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-primary text-white">--}}
{{--                                    <div  style="background-image: url({{asset($detail->image_placeholder)}}); width: 100%;--}}
{{--                                        height: 100%;--}}
{{--                                        background-size: cover;--}}
{{--                                        background-position: center;--}}
{{--                                        border-radius: 100%;"></div>--}}
{{--                                </div>--}}
{{--                                <div class="media-body pr-3 ">--}}
{{--                                    <a class="mt-0 mb-1 font-size-15 text-dark" href="#">{{$detail->name}}</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            {{$detail->name}}
                            </td>
                    </tr>
                    <tr>
                        <td>{{__('Email')}}</td>
                        <td>{{$detail->email}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Mobile')}}</td>
                        <td>{{$detail->mobile}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Country')}}</td>
                        <td>{{!empty($detail->country_detail) ?$detail->country_detail->name : 'NA'}}</td>
                    </tr>

                    <tr>
                        <td>{{__('State')}}</td>
                        <td>{{!empty($detail->state_detail) ?$detail->state_detail->name : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('City')}}</td>
                        <td>{{!empty($detail->city_detail) ?$detail->city_detail->name : 'NA'}}</td>
                    </tr>

                </table>
                <hr>
                <h6>{{__('Educational Details')}}</h6>
                <br>
                <table class="table table-bordered">
                    <tr>
                        <td>{{__('University Name')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->university_name : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('College Name')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->college_name : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Educational Qualification')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->education_qualification : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Research Of Expertise')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->research_of_expertise : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Achivements')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->achievements : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Preferred Language')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->preferred_language : 'NA'}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Other Information')}}</td>
                        <td>{{!empty($detail->professor_detail) ? $detail->professor_detail->other_information : 'NA'}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

@endsection

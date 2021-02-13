@section('PAGE_TITLE',$pageTitle)
@extends('moderator.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
                <div class="pull-right">
                    {!! getVerifyBadge($note->approve) !!}
                    @if($note->approve == 0)
                        <a class="btn btn-sm btn-success text-white" data-toggle="modal"
                           data-target="#approve-{{$note->id}}" title="APPROVE">
                            <i class="mdi mdi-check"></i>
                        </a>
                        @include($route.'.approve')
                        <a class="btn btn-sm btn-danger text-white" data-toggle="modal"
                           data-target="#reject-{{$note->id}}" title="REJECT">
                            <i class="mdi mdi-close"></i>
                        </a>

                        @include($route.'.reject')
                    @endif
                    <a href="{{route($route.".index")}}" class="btn btn-sm btn-primary">Back</a>
                </div>


            </div>

            <div class="card-body">
                {{$note->description}}
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <h6><b>Image Files</b></h6>
                        <hr>
                        @forelse($note->image_files as $imageList)
                            <img src="{{$imageList->file_path}}" class="img-responsive" style="width: 100%">
                            <hr>
                        @empty
                            @include('common.no-record-found')
                        @endforelse
                    </div>
                    <div class="col-md-4">
                        <h6><b>PDF Files</b></h6>
                        <hr>

                        <ul class="list-group">
                            @forelse($note->pdf_files as $pdfList)
                                <li class="list-group-item font-size-16 text-dark">
                                    <a target="_blank" href="{{$pdfList->file_path}}">{{$loop->iteration}}
                                        ] {{$pdfList->file_name}}</a>
                                </li>
                            @empty
                                @include('common.no-record-found')
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6><b>Audio Files</b>
                            <hr>
                            @forelse($note->audio_files as $audioList)
                                <audio controls>
                                    <source src="{{$audioList->file_path}}" type="audio/ogg">
                                    <source src="{{$audioList->file_path}}" type="audio/mp3">
                                    Your browser does not support the audio tag.
                                </audio>
                                <hr>
                        @empty
                            @include('common.no-record-found')
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

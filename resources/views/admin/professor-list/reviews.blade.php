@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8 offset-2">
                        <div class="rating-block">
                            <h4>Average user rating</h4>
                            <h2 class="bold padding-bottom-7">{{number_format((float) $avgRating,1)}} <small>/ 5</small></h2>
                            {!!  getRatingStarHtmlAdmin($avgRating) !!}

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8 offset-2">
                        <hr/>
                        <div class="review-block">
                            @forelse($reviews as $review)

                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="{{asset($review->from_user->image_placeholder)}}" class="img-rounded" width="100%">
                                    <div class="review-block-name"><a href="#">{{$review->from_user->name}}</a></div>
                                    <div class="review-block-date">{{getDateInFormat($review->created_at)}}</div>
                                </div>
                                <div class="col-sm-9">
                                    {!!  getRatingStarHtmlAdmin($review->rating) !!}
                                    <hr>
                                    <div class="review-block-description">{{$review->description}}</div>
                                </div>
                            </div>
                            <hr/>
                                @empty

                            @endforelse
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@if(!empty($queries) && $queries->IsNotEmpty())
    <div id="content">
        <ul class="timeline">
            @foreach($queries as $list)
                <li class="event"
                    data-date="{{\Carbon\Carbon::parse($list->created_at)->format('d M Y')}}">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left text-uppercase text-primary">{{__('Student Name')}} : {{$list->from_user->name}}</div>
                                </div>
                                <div class="col-md-6">
                                    @if(!empty($list->post_reply))
                                        <div class="text-right text-success">{{__('REPLIED')}}</div>
                                    @else
                                        <div class="text-right text-danger">{{__('PENDING REPLY')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3>{{$list->title}}</h3>
                    <p>{{$list->subject->title}}</p>
                    <p>{{\Illuminate\Support\Str::limit($list->description,50)}}</p>
                    <a href="{{route($route.".show",$list->uuid)}}">View Detail</a>
                </li>
            @endforeach
        </ul>
        <hr>

    </div>
@endif

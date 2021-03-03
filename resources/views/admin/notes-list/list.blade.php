@if(!empty($notes) && $notes->IsNotEmpty())
    <div id="content">
        <ul class="timeline">
            @foreach($notes as $list)
                <li class="event"
                    data-date="{{\Carbon\Carbon::parse($list->created_at)->format('d M Y')}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 text-right">

                                        <a href="{{route('admin.moderator.edit',$list->approve_user->uuid)}}">
                                            {{__('Approved By')}} : {{$list->approve_user->name}}
                                        </a>
                                        <br>
                                        <small>
                                             Date : {{getDateInFormat($list->updated_at)}}
                                        </small>
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

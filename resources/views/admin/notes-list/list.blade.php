@if(!empty($notes) && $notes->IsNotEmpty())
    <div id="content">
        <ul class="timeline">
            @foreach($notes as $list)
                <li class="event"
                    data-date="{{\Carbon\Carbon::parse($list->created_at)->format('d M Y')}}">
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

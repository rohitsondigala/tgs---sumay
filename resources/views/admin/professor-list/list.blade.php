@if(!empty($professors) && !$professors->isEmpty())

<table  id="basic-data-table" class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Subjects')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @forelse($professors as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->email}}</td>
            <td>
                @if(!empty($list->professor_subjects))
                    @foreach($list->professor_subjects as $subjectList)
                        {{$loop->iteration}} - {{$subjectList->subject->title}}<br>
                    @endforeach
                @else
                @endif
            </td>
            <td>
                <a href="{{route($route.'.show',$list->uuid)}}">Activites </a>
                <span>|</span>
                <a href="{{route($route.'.show',$list->uuid)}}">Edit </a>

        </tr>
    @empty
        @include('common.no-record-found')
    @endforelse
    </tbody>
</table>
@else
    @include('common.no-record-found')
@endif

<table class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="6"><input type="text" class="form-control" placeholder="Search Words by name,email,subject"
                               wire:model="searchWords"></th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Description')}}</th>
        <th>{{__('Stream')}}</th>
        <th>{{__('Subject')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>

    <tbody class="search-results">
    @if(!empty($packages) && !$packages->isEmpty())

        @foreach($packages as $list)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$list->title}}</td>
                <td>{{$list->description}}</td>
                <td>{{$list->stream->title}}</td>
                <td>{{$list->subject->title}}</td>
                <td>
                    <a href="{{route($route.'.show',$list->uuid)}}">View </a>
                    <span>|</span>
                    <a href="{{route($route.'.edit',$list->uuid)}}">Edit </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>


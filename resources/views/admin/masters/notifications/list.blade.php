
<table class="table nowrap" style="width:100%">
    <thead>
    <tr>
        <th colspan="5"><input type="text" class="form-control" placeholder="Search Words by title"  wire:model="searchWords"></th>
    </tr>
    <tr>
        <th>{{__('No')}}</th>
        <th>{{__('TO')}}</th>
        <th width="30%">{{__('Title')}}</th>
        <th width="30%">{{__('Description')}}</th>
        <th>{{__('Image')}}</th>
    </tr>
    </thead>

    <tbody >
    @if(!empty($notifications) && !$notifications->isEmpty())

        @foreach($notifications as $list)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>@if($list->student)
                    <span class="mb-2 mr-2 badge badge-pill badge-primary">{{__('STUDENT')}}</span>
                @endif
                @if($list->professor)
                        <span class="mb-2 mr-2 badge badge-pill badge-primary">{{__('PROFESSOR')}}</span>
                @endif
            </td>
            <td>{{$list->title}}</td>
            <td>{{$list->description}}</td>
            <td><img src="{{$list->full_image_path}}" width="40%"> </td>

        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text-center">No Record Found</td>
        </tr>
    @endif
    </tbody>
</table>

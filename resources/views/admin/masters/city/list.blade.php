@section('PAGE_TITLE',$pageTitle)
@extends('admin.template.main')
@section('content')
    <div class="col-12 mt-3">
        @include('common.globalAlerts')

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>{{$pageTitle}}</h2>
                <a href="{{route($route.'.create')}}" class="btn btn-primary">Add New</a>
            </div>

            <div class="card-body">
                <table id="basic-data-table" class="table nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>{{__('No')}}</th>
                        <th>{{__('CountryName')}}</th>
                        <th>{{__('StateName')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($cities as $list)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{!empty($list->state->country) ? $list->state->country->name : 'NA'}}</td>
                            <td>{{!empty($list->state) ? $list->state->name : 'NA'}}</td>
                            <td>{{$list->name}}</td>
                            <td>
                                <a href="{{route($route.'.edit',$list->id)}}">Edit </a>
                                <span>|</span>
                                <a class="delete-item" data-delete='delete-form-{{$list->id}}' href="javascript:;">Delete</a>
                            {!! Form::model($cities,array('url'=>route($route.'.destroy',$list->id),'method'=>'DELETE','class'=>'delete-form-'.$list->id)) !!}
                            {!! Form::close() !!}
                        </tr>
                    @empty
                        @include('common.no-record-found')
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection

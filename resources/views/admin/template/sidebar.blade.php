<aside class="left-sidebar bg-sidebar">
    <div id="sidebar" class="sidebar ">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="{{route('index')}}" title="Sleek Dashboard">
                <img src="{{logo()}}">

                <span class="brand-name text-truncate">{{env('APP_NAME')}}</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-scrollbar">
            <ul class="nav sidebar-inner" id="sidebar-menu">

                <li class="{{(\Route::current()->getName() == 'admin.dashboard') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.dashboard')}}" >
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text">{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li class="{{(\Route::current()->getName() == 'admin.moderator.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.moderator.index')}}" >
                        <i class="mdi mdi-account-check"></i>
                        <span class="nav-text">{{__('Moderator')}}</span>
                    </a>
                </li>
                <li class="{{(\Route::current()->getName() == 'admin.streams.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.streams.index')}}" >
                        <i class="mdi mdi-playlist-check"></i>
                        <span class="nav-text">{{__('Streams')}}</span>
                    </a>
                </li>
                <li class="{{(\Route::current()->getName() == 'admin.subjects.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.subjects.index')}}" >
                        <i class="mdi mdi-file-document-box-multiple-outline"></i>
                        <span class="nav-text">{{__('Subjects')}}</span>
                    </a>
                </li>

                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                       data-target="#dashboard"
                       aria-expanded="false" aria-controls="dashboard">
                        <i class="mdi mdi-cogs"></i>
                        <span class="nav-text">{{__('Settings')}}</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse " id="dashboard"
                        data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="{{(\Route::current()->getName() == 'admin.country.index') ? 'active' : ''}}">
                                <a class="sidenav-item-link" href="{{route('admin.country.index')}}">
                                    <span class="nav-text">{{__('Countries')}}</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{route('admin.state.index')}}">
                                    <span class="nav-text">{{__('States')}}</span>
                                </a>
                            </li>
                            <li>
                                <a class="sidenav-item-link" href="{{route('admin.city.index')}}">
                                    <span class="nav-text">{{__('Cities')}}</span>
                                </a>
                            </li>
{{--                            <li>--}}
{{--                                <a class="sidenav-item-link" href="{{route('admin.roles.index')}}">--}}
{{--                                    <span class="nav-text">{{__('User Types')}}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        </div>
                    </ul>
                </li>


            </ul>

        </div>
    </div>
</aside>

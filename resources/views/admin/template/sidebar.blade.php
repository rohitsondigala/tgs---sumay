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
                <li class="{{(\Route::current()->getName() == 'admin.notifications.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.notifications.index')}}" >
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="nav-text">{{__('Send Notification')}}</span>
                    </a>
                </li>
                <li class="{{(\Route::current()->getName() == 'admin.generate-package.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.generate-package.index')}}" >
                        <i class="mdi mdi-currency-inr"></i>
                        <span class="nav-text">{{__('Free Package')}}</span>
                    </a>
                </li>
                <li class="{{(in_array(\Route::current()->getName(),['admin.packages.index','admin.packages.create','admin.packages.edit'])) ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.packages.index')}}" >
                        <i class="mdi mdi-image"></i>
                        <span class="nav-text">{{__('Packages')}}</span>
                    </a>
                </li>
                <li class="{{(in_array(\Route::current()->getName(),['admin.moderator.index','admin.moderator.create','admin.moderator.edit'])) ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.moderator.index')}}" >
                        <i class="mdi mdi-account-edit"></i>
                        <span class="nav-text">{{__('Moderator')}}</span>
                    </a>
                </li>
                <li class="{{(in_array(\Route::current()->getName(),['admin.professor.index','admin.professor.create','admin.professor.edit','admin.professor.show'])) ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.professor.index')}}" >
                        <i class="mdi mdi-account-check"></i>
                        <span class="nav-text">{{__('Professors')}}</span>
                    </a>
                </li>
                <li class="{{(in_array(\Route::current()->getName(),['admin.student.index','admin.student.create','admin.student.edit'])) ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.student.index')}}" >
                        <i class="mdi mdi-account-group"></i>
                        <span class="nav-text">{{__('Students')}}</span>
                    </a>
                </li>



                <li class="{{(in_array(\Route::current()->getName(),['admin.streams.index','admin.streams.create','admin.streams.edit'])) ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('admin.streams.index')}}" >
                        <i class="mdi mdi-playlist-check"></i>
                        <span class="nav-text">{{__('Streams')}}</span>
                    </a>
                </li>
                <li class="{{(in_array(\Route::current()->getName(),['admin.subjects.index','admin.subjects.create','admin.subjects.edit'])) ? 'active' : ''}}">
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

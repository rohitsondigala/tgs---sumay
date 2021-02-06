<aside class="left-sidebar bg-sidebar">
    <div id="sidebar" class="sidebar ">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="{{route('index')}}" title="Sleek Dashboard" style="border-right: 1px solid #dedede;">
                    <img src="{{logo()}}">
                <span class="brand-name text-truncate">{{env('APP_NAME')}}</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-scrollbar">
            <ul class="nav sidebar-inner" id="sidebar-menu">

                <li class="{{(\Route::current()->getName() == 'moderator.dashboard') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('moderator.dashboard')}}" >
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text">{{__('Dashboard')}}</span>
                    </a>
                </li>

                <li class="{{(\Route::current()->getName() == 'moderator.daily-posts.index') ? 'active' : ''}}">
                    <a class="sidenav-item-link" href="{{route('moderator.daily-posts.index')}}" >
                        <i class="mdi mdi-plus-box-outline"></i>
                        <span class="nav-text">{{__('Daily Post')}}</span>
                    </a>
                </li>



            </ul>

        </div>
    </div>
</aside>

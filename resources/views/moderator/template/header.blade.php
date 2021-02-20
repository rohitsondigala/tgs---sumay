<header class="main-header " id="header">
    <nav class="navbar navbar-static-top navbar-expand-lg">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>
        <!-- search form -->
        <div class="search-form d-none d-lg-inline-block">

        </div>

        <div class="navbar-right ">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <button class="dropdown-toggle" data-toggle="dropdown">
                        <i class="mdi mdi-bell-outline"></i>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="notification-badge">{{auth()->user()->unreadNotifications()->count()}}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">

                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                        <li>
                            <a href="{{route('moderator.read',[$notification->id,'link'=>$notification->data['url']])}}">
                                <i class="{{$notification->data['icon']}}"></i> <span class="{{($notification->data['type'] == 'New Note') ? 'text-success' : 'text-warning'}}">{{$notification->data['type']}}</span>  {{$notification->data['title']}}
                                <br>
                                <span class="ml-30 font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> {{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</span>
                            </a>
                        </li>
                        @empty
                             <li>
                                 <a>

                          <span>
                                 {{__('No new notification')}}
                          </span>
                                 </a>
                             </li>
                        @endforelse

                    </ul>
                </li>

                <!-- User Account -->
                <li class="dropdown user-menu">
                    <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">

                        <img src="{{user_image()}}" class="user-image" alt="User Image" />
                        <span class="d-none d-lg-inline-block">{{user_name()}}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- User image -->



                        <li>
                            <a href="{{route('moderator.profile')}}">
                                <i class="mdi mdi-account"></i> {{__('My Profile')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('moderator.user-change-password')}}">
                                <i class="mdi mdi-lock"></i> {{__('Change Password')}}
                            </a>
                        </li>

                        <li class="dropdown-footer">
                            <a href="{{route('logout')}}"> <i class="mdi mdi-logout"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


</header>

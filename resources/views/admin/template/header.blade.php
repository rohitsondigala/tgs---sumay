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
               <!-- User Account -->
                <li class="dropdown user-menu">
                    <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">

                        <img src="{{user_image()}}" class="user-image" alt="User Image" />
                        <span class="d-none d-lg-inline-block">{{user_name()}}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- User image -->


                        <li>
                            <a href="{{route('admin.profile')}}">
                                <i class="mdi mdi-account"></i> {{__('My Profile')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.change-password')}}">
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

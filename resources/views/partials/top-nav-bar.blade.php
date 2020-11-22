<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <!--Global Notifications-->
        <?php list($notifications, $actionNotifications) = get_global_notifications(); ?>

        <li class="nav-item">
            <a class="nav-link" href=" https://docs.google.com/document/d/1kR1wOHdrIwN_EwhRbcnQ7FzR5bzDioeltTqGEU_aUsY/edit" target="_blank">
                Jason Content To Do
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="https://master.cmsmax.com/webadmin" target="_blank">
                Master/Dev Area
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="https://www.cmsmax.com/versions" target="_blank">
                CMS Max Version Log
            </a>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if( count($notifications) + count($actionNotifications) > 0 )
                    <span class="badge badge-warning navbar-badge">{{ count($notifications) + count($actionNotifications) }}</span>
                @endif
            </a>
            @if( count($notifications) + count($actionNotifications) > 0 )
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @foreach ($notifications as $notification)
                        <a href="{{ $notification['link'] }}" class="dropdown-item">
                            <i class="{{ $notification['icon'] }}"></i> {{ $notification['text'] }}
                            <span class="float-right text-muted text-sm"></span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                    <!--action notifications-->
                    @foreach ($actionNotifications as $notification)
                        <a href="{{ $notification['targetLink'] }}" class="dropdown-item">
                            <i class="{{ $notification['icon'] }}"></i>&nbsp;{{ $notification['text'] }}
                            <span class="float-right text-muted text-sm"><i class="fa fa-times text-red archive-button pull-right"></i></span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                    <a href="{{ url('/webadmin') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            @endif
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                <i class="fas fa-sign-out-alt"></i>Sign Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>

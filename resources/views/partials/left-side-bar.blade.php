<aside class="main-sidebar elevation-4 sidebar-light-info">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-bold">
            <img src="{{ asset('assets/images/evolution-marketing-logo.png') }}" />
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->getPublicAvatarLink() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ url('/profile') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <?php
            $allPages = App\Http\Helpers\UserHelper::getAllPagePermissions();
        ?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/webadmin" class="nav-link {{ $currentSection == 'dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @foreach ( $allPages as $pageSection )
                    @if( isset($pageSection['subPages']) )
                        <?php
                            $isShowing = false;
                            $isActive = false;
                            foreach ( $pageSection['subPages'] as $subPage ) {
                                if( Auth::user()->hasPagePermission($subPage['name']) )
                                    $isShowing = true;
                                if( $currentSection == $subPage['section'] )
                                    $isActive = true;
                            }
                        ?>
                        @if( $isShowing )
                            <li class="nav-item has-treeview {{ $isActive ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $isActive ? 'active' : '' }}">
                                    <i class="nav-icon {{ $pageSection['icon'] }}"></i>
                                    <p>
                                        {{ isset($pageSection['title']) ? $pageSection['title'] : $pageSection['name'] }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach ($pageSection['subPages'] as $subPage)
                                        @if( Auth::user()->hasPagePermission($subPage['name']) )
                                            <li class="nav-item">
                                                <a href="{{ url($subPage['link']) }}" class="nav-link {{ $subPage['section'] == $currentSection ? 'active' : '' }}" target="{{ isset($subPage['target']) ? $subPage['target'] : '_self' }}">
                                                    <i class="{{ $subPage['icon'] }} nav-icon"></i>
                                                    <p>
                                                        {{ isset($subPage['title']) ? $subPage['title'] : $subPage['name'] }}
                                                        @if( isset($subPage['badgeContent']) )
                                                            {!! $subPage['badgeContent'] !!}
                                                        @endif
                                                    </p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        @if( Auth::user()->hasPagePermission($pageSection['name']) )
                            <li class="nav-item">
                                <a href="{{ url($pageSection['link']) }}" class="nav-link {{ $pageSection['section'] == $currentSection ? 'active' : '' }}" target="{{ isset($pageSection['target']) ? $pageSection['target'] : '_self' }}">
                                    <i class="nav-icon {{ $pageSection['icon'] }}"></i>
                                    <p>
                                        {{ isset($pageSection['title']) ? $pageSection['title'] : $pageSection['name'] }}
                                        @if( isset($pageSection['badgeContent']) )
                                            {!! $pageSection['badgeContent'] !!}
                                        @elseif( isset($pageSection['badgeFunction']) )
                                            {!! getBadgeContent($pageSection['badgeFunction']) !!}
                                        @endif

                                    </p>
                                </a>
                            </li>
                        @endif
                    @endif

                @endforeach
            </ul>
        </nav>
    <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

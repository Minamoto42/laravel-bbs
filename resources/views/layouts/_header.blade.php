<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        <!-- Branding Image -->
        <a class="navbar-brand " href="{{ url('/') }}">
            {{ env('APP_NAME') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ active_class(if_route('topics.index')) }}"
                                        href="{{ route('topics.index') }}">Topics</a></li>
                <li class="nav-item"><a class="nav-link {{ category_nav_active(1) }}"
                                        href="{{ route('categories.show', 1) }}">Shared</a></li>
                <li class="nav-item"><a class="nav-link {{ category_nav_active(2) }}"
                                        href="{{ route('categories.show', 2) }}">Tutorial</a></li>
                <li class="nav-item"><a class="nav-link {{ category_nav_active(3) }}"
                                        href="{{ route('categories.show', 3) }}">Q&A</a></li>
                <li class="nav-item"><a class="nav-link {{ category_nav_active(4) }}"
                                        href="{{ route('categories.show', 4) }}">Announcement</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Signup</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link mt-1 me-3 font-weight-bold" href="{{ route('topics.create') }}">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </li>
                    <li class="nav-item notification-badge">
                        <a class="nav-link ms-3 me-3 badge bg-secondary rounded-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary'}} text-white"
                           href="{{ route('notifications.index') }}">
                            {{ Auth::user()->notification_count }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="30px"
                                 height="30px" alt="Gravatar">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @can('manage_contents')
                                <a class="dropdown-item" href="{{ url(config('administrator.uri')) }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Admin
                                </a>
                                <div class="dropdown-divider"></div>
                            @endcan
                            <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">
                                <i class="far fa-user me-2"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                                <i class="far fa-edit me-2"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id="logout" href="#">
                                <form action="{{ route('logout') }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to logout?');">
                                    {{ csrf_field() }}
                                    <button class="btn btn-block btn-danger" type="submit" name="button">Logout</button>
                                </form>
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

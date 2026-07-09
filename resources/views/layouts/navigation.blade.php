<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            ⚡ InnovateHub
        </a>

        <button class="navbar-toggler" type="button"
                onclick="document.getElementById('navMain').classList.toggle('show')">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                @auth
                    @if (auth()->user()->isFounder())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('founder.dashboard') ? 'active' : '' }}"
                               href="{{ route('founder.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('founder.ideas.*') ? 'active' : '' }}"
                               href="{{ route('founder.ideas.index') }}">My Ideas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}"
                               href="{{ route('events.index') }}">Events</a>
                        </li>

                    @elseif (auth()->user()->isMentor())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}"
                               href="{{ route('mentor.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mentor.requests.*') ? 'active' : '' }}"
                               href="{{ route('mentor.requests.index') }}">Requests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mentor.startups.*') ? 'active' : '' }}"
                               href="{{ route('mentor.startups.index') }}">My Startups</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}"
                               href="{{ route('events.index') }}">Events</a>
                        </li>

                    @elseif (auth()->user()->isInvestor())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('investor.dashboard') ? 'active' : '' }}"
                               href="{{ route('investor.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('investor.browse') ? 'active' : '' }}"
                               href="{{ route('investor.browse') }}">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('investor.interests.*') ? 'active' : '' }}"
                               href="{{ route('investor.interests.index') }}">My Interests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}"
                               href="{{ route('events.index') }}">Events</a>
                        </li>

                    @elseif (auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                               href="{{ route('admin.users.index') }}">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.ideas.*') ? 'active' : '' }}"
                               href="{{ route('admin.ideas.index') }}">Ideas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                               href="{{ route('admin.events.index') }}">Events</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-1">
                @auth
                    {{-- Messages badge --}}
                    @php $unread = auth()->user()->unreadMessagesCount(); @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('messages.*') ? 'active' : '' }}"
                           href="{{ route('messages.index') }}">
                            💬 Messages
                            @if ($unread > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unread }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Avatar dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#" id="userDropdown"
                           onclick="
                               event.preventDefault();
                               const m = document.getElementById('userMenu');
                               m.classList.toggle('show');
                               document.addEventListener('click', function close(e) {
                                   if (!e.target.closest('#userDropdown') && !e.target.closest('#userMenu')) {
                                       m.classList.remove('show');
                                       document.removeEventListener('click', close);
                                   }
                               });
                           ">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 class="rounded-circle border border-2 border-white border-opacity-50"
                                 width="30" height="30" alt="avatar">
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end show-on-click" id="userMenu">
                            <li>
                                <div class="px-3 py-2 border-bottom">
                                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    👤 My Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        🚪 Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm ms-1" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
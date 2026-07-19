<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="brand-dot"></div>
            InnovateHub
        </a>

        <button class="navbar-toggler border-0 p-1" type="button"
                onclick="document.getElementById('navMain').classList.toggle('show')"
                style="color:rgba(255,255,255,0.7)">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z"/>
            </svg>
        </button>

        <div class="navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto gap-1">
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
                               href="{{ route('investor.interests.index') }}">Interests</a>
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

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                @auth
                    {{-- Messages --}}
                    @php $unread = auth()->user()->unreadMessagesCount(); @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('messages.*') ? 'active' : '' }}"
                           href="{{ route('messages.index') }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-2px">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                            <span class="ms-1 d-none d-lg-inline">Messages</span>
                            @if ($unread > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger unread-badge">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                            @else
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger unread-badge d-none">0</span>
                            @endif
                        </a>
                    </li>

                    {{-- Theme toggle --}}
                    <li class="nav-item">
                        <button class="theme-toggle" id="themeToggle" title="Toggle theme" type="button">
                            <span id="themeIcon">🌙</span>
                        </button>
                    </li>

                    {{-- Avatar dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-2 py-1"
                           href="#"
                           id="userDropdownToggle"
                           onclick="
                               event.preventDefault();
                               const m = document.getElementById('userDropdownMenu');
                               m.classList.toggle('show');
                               document.addEventListener('click', function close(e) {
                                   if (!e.target.closest('#userDropdownToggle') && !e.target.closest('#userDropdownMenu')) {
                                       m.classList.remove('show');
                                       document.removeEventListener('click', close);
                                   }
                               });
                           ">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 class="rounded-circle"
                                 style="width:30px;height:30px;object-fit:cover;border:1.5px solid rgba(255,255,255,0.2)"
                                 alt="avatar">
                            <span class="d-none d-xl-inline" style="font-size:0.875rem;font-weight:500;color:rgba(255,255,255,0.85)">
                                {{ auth()->user()->name }}
                            </span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:rgba(255,255,255,0.5)">
                                <path d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu">
                            <li>
                                <div class="dropdown-header-info">
                                    <div class="name">{{ auth()->user()->name }}</div>
                                    <div class="role-tag">{{ ucfirst(auth()->user()->role) }}</div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                    My Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign in</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm" href="{{ route('register') }}">Get started</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
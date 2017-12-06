<nav class="bg-blue-lightest h-12 shadow mb-8">
    <div class="container mx-auto h-full">
        <div class="flex items-center justify-center h-12">
            <div class="mr-6">
                <a href="{{ url('/') }}" class="no-underline text-black">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="mr-8 text-sm">
                <a href="{{ url('/about') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                    {{ trans('menu.links.about') }}
                </a>
                <a href="{{ url('/armory') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                    {{ trans('menu.links.armory') }}
                </a>
                <a href="{{ url('/schedule') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                    {{ trans('menu.links.schedule') }}
                </a>
                <a href="{{ url('/community') }}" class="no-underline hover:underline text-brand-darkest pr-2">
                    {{ trans('menu.links.community') }}
                </a>
            </div>

            <div class="flex-1 text-right text-sm">
                @guest
                <a class="no-underline hover:underline pr-3 text-brand-darkest" href="{{ url('/login') }}">Login</a>
                <a class="no-underline hover:underline text-brand-darkest" href="{{ url('/register') }}">Register</a>
                @else
                <span class=" pr-4">{{ Auth::user()->name }}</span>

                <a href="{{ route('logout') }}" class="no-underline hover:underline text-brand-darkest" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

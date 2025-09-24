@php
//Arreglo de iconos para el side bar, para no andar repitiendeo todo el código parte por parte
    $links =[
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard')
        ],
        [
            'name' => 'Cats',
            'icon' => 'fa-solid fa-cat',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard')
        ],
        [
            'name' => 'Playdates',
            'icon' => 'fa-solid fa-calendar',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard')
        ],
        [
            'name' => 'Food service',
            'icon' => 'fa-solid fa-fish',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard')
        ],
        [
            'name' => 'Settings',
            'icon' => 'fa-solid fa-gear',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('profile.show')
        ]
    ];
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-1 font-medium">
                @foreach ($links as $link)
                <li>
                    <a href= "{{ $link['href'] }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"> 
                        <span class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white {{$link['active'] ? 'dark:group-hover:text-white': ""}}"> <!-- ? es un pequeño if para cambiar el estilo en activación --> 
                            <i class="{{$link['icon']}}"></i> 
                        </span>
                        <span class ="ms-3" > {{$link['name']}} </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        </aside>
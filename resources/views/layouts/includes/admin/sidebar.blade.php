@php
// Arreglo de iconos para el side bar
$links = [
    [
        'name' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge',
        'href' => route('admin.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),
    ],
    [
        'header' => 'Management'
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
        'name' => 'Usuarios',
        'icon' => 'fa-solid fa-users',
        'href' => route('admin.users.index'),
        'active' => request()->routeIs('admin.users.*')
    ],
    [
        'name' => 'Roles y permisos',
        'icon' => 'fa-solid fa-shield-halved',
        'href' => route('admin.roles.index'),
        'active' => request()->routeIs('admin.roles.*')
    ],
    [
        'name' => 'More Options',
        'icon' => 'fa-solid fa-bars',
        'active' => false,
        'submenu' => [
            [
                'name' => 'Products',
                'href' => '#',
                'active' => false,
            ],
            [
                'name' => 'Billing',
                'href' => '#',
                'active' => false,
            ],
            [
                'name' => 'Invoice',
                'href' => '#',
                'active' => false,
            ],
        ]
    ],
];
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full 
    bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" 
    aria-label="Sidebar">

    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-1 font-medium">
            <div class="h-3"></div>{{-- espacio superior --}}
            @foreach ($links as $link)
                {{-- Si es un encabezado --}}
                @isset($link['header'])
                    <li>
                        <div class="px-3 mt-4 mb-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                            {{ $link['header'] }}
                        </div>
                    </li>
                @else
                    {{-- Si tiene submenu --}}
                    @isset($link['submenu'])
                        <li>
                            <button type="button"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group
                                hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                aria-controls="dropdown-{{ Str::slug($link['name']) }}"
                                data-collapse-toggle="dropdown-{{ Str::slug($link['name']) }}">
                                <span class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-800 
                                    group-hover:text-gray-600 dark:group-hover:text-white {{ $link['active'] ? 'dark:group-hover:text-white' : '' }}">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">
                                    {{ $link['name'] }}
                                </span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <ul id="dropdown-{{ Str::slug($link['name']) }}" class="hidden py-2 space-y-2">
                                @foreach ($link['submenu'] as $item)
                                    <li>
                                        <a href="{{ $item['href'] }}"
                                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group
                                            hover:bg-gray-400 dark:text-white dark:hover:bg-gray-700">
                                            {{ $item['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        {{-- Enlace normal --}}
                        <li>
                            <a href="{{ $link['href'] }}"
                                class="flex items-center p-2 text-gray-600 rounded-lg dark:text-white 
                                hover:bg-gray-300 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-300 dark:bg-gray-700' : '' }}">
                                <span class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 
                                    group-hover:text-gray-600 dark:group-hover:text-white">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="ms-3">{{ $link['name'] }}</span>
                            </a>
                        </li>
                    @endisset
                @endisset
            @endforeach
        </ul>
    </div>
</aside>

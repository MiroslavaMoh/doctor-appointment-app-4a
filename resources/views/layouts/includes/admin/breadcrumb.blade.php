{{--verifica si hay algúne leemntso que use puede poner ene breadcrumb--}}
@if (count($breadcrumbs))
    <nav class="ms-block">
        <ol class="flex flex-wrap text-slate-700 text-sm">
            {{--recorre los elementos del breadcrumb--}}
            @foreach ($breadcrumbs as $item)
                {{--centrar li--}}
                <li class="flex items-center">
                    @unless ($loop->first) {{-- si no es el primer elemento --}}
                        <span class="px-2 text-gray-400">/</span>
                    @endunless

                    @isset($item['href'])
                        <a href="{{ $item['href'] }}" class="opacity-60 hover:opacity-10 transition">
                            {{ $item['name'] }}
                        </a>
                    @else {{-- si no tiene href --}}
                        {{ $item['name'] }}
                    @endisset
                </li>
            @endforeach
        </ol>
        {{--Ultimo elemento del breadcrumb--}}
        @if(count($breadcrumbs))
            <h6 class="font-bold mt-2">
                {{ end($breadcrumbs)['name'] }}
            </h6>
        @endif
        </nav>
@endif

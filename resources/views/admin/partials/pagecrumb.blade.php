@if(isset($pagecrumbItems))
    <h1>
        @foreach($pagecrumbItems as $nav)

            @if($loop->first)
                @if(strlen($nav->icon) > 2)
                    <i class="fa fa-{{ $nav->icon }}"></i>
                @endif
                {{ $nav->name }}
            @else
                <small class="text-muted">
                    @if(strlen($nav->icon) > 2)
                        <i class="fa fa-{{ $nav->icon }}"></i>
                    @endif
                    {{ $nav->name }}
                </small>
            @endif
        @endforeach
    </h1>
@endif
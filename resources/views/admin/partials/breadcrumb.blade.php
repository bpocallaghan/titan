@if(isset($breadcrumbItems))
    <ol class="breadcrumb">
        @foreach($breadcrumbItems as $nav)
            <li>
                @if(!$loop->last)
                    <a href="{{ url($nav->url) }}">
                        @if(strlen($nav->icon) > 2)
                            <i class="fa fa-{{ $nav->icon }}"></i>
                        @endif
                        {{ $nav->name }}
                    </a>
                @else
                    <small class="text-muted">
                        @if(strlen($nav->icon) > 2)
                            <i class="fa fa-{{ $nav->icon }}"></i>
                        @endif
                        {{ $nav->name }}
                    </small>
                @endif
            </li>
        @endforeach
    </ol>
@endif
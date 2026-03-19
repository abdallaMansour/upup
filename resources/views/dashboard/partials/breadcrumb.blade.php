@props(['items' => []])

@if(count($items) > 0)
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        @foreach($items as $index => $item)
            @if(isset($item['url']) && $item['url'])
                <li class="breadcrumb-item"><a class="text-primary" href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
@endif

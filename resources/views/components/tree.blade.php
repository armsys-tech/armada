@props([
    'items' => [],      // [['label' => 'src', 'icon' => 'folder', 'href' => null, 'active' => false, 'expanded' => true, 'children' => [...]], …]
    'nested' => false,
])
<ul {{ $attributes->class(['arm-tree' => ! $nested]) }} @unless ($nested) role="tree" @endunless>
    @foreach ($items as $item)
        <li>
            @if (! empty($item['children']))
                <details class="arm-tree__node" @if ($item['expanded'] ?? false) open @endif>
                    <summary><armada:icon name="chevron-right" class="arm-tree__caret" /><armada:icon :name="$item['icon'] ?? 'folder'" />{{ $item['label'] }}</summary>
                    <armada:tree :items="$item['children']" nested />
                </details>
            @else
                <a @class(['arm-tree__leaf', 'is-active' => $item['active'] ?? false]) href="{{ $item['href'] ?? '#' }}"><armada:icon :name="$item['icon'] ?? 'file'" />{{ $item['label'] }}</a>
            @endif
        </li>
    @endforeach
</ul>

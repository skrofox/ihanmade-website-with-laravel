{{-- resources/views/components/category-tree.blade.php --}}
@props(['nodes', 'level' => 0])

<ul class="{{ $level === 0 ? 'space-y-1' : 'ml-4 pl-3 border-l border-gray-200 space-y-1' }}">
    @foreach($nodes as $node)
        <li class="flex items-start gap-2">
            <span class="inline-flex items-center justify-center mt-0.5 w-1.5 h-1.5 rounded-full bg-gray-400"></span>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-medium {{ $level === 0 ? 'text-gray-900' : 'text-gray-700' }}">
                        {{ $node->name }}
                    </span>
                    @if($node->slug)
                        <span class="text-xs text-gray-500">/{{ $node->slug }}</span>
                    @endif
                </div>
                @if($node->relationLoaded('childrenRecursive') && $node->childrenRecursive->isNotEmpty())
                    <x-category-tree :nodes="$node->childrenRecursive" :level="$level + 1"/>
                @endif
            </div>
        </li>
    @endforeach
</ul>

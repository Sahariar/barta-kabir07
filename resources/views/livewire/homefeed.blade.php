<div class="space-y-6">
    <div class="space-y-4 my-8">
        @foreach ($posts as $post)
        <div class="col-md-4">
            <x-card
            :post="$post"
            />
        </div>
        @endforeach
    </div>

    @if ($posts->hasMorePages())
        <div class="text-center my-4">
            <button
                wire:click="loadMore"
                class="px-4 py-2 bg-neutral-700 text-white rounded hover:bg-neutral-900">
                Show More
            </button>
        </div>
    @endif
</div>

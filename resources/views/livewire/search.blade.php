<?php
 
use App\Models\Post;
use function Livewire\Volt\{computed, state, layout};
 
layout('layout');

state(['query'])->url();

state(['time']);

$posts = computed(function () {

    \DB::listen(function($query) {
       $this->time = round($query->time, 2);
    });

    return \DB::select(
        "SELECT rowid, snippet(posts_index, '<mark><b>', '</b></mark>') AS content FROM posts_index WHERE title MATCH ? LIMIT 100",  
        [$this->query]
    );
});

?>
 
<div>

    <div>
        <input wire:model.live.debounce.250ms="query" type="search" placeholder="Search posts by title...">
    </div>
    
    <div wire:loading> 
        Searching posts...
    </div>

    @if($this->query != '')
        @if (count($this->posts) > 0)
            <p>
                Found <b>{{ count($this->posts) }}</b> result(s) in <b>{{ $this->time }}ms</b>
            </p>

        
            <ul>
                @foreach($this->posts as $post)
                    <li wire:key="{{ $post->rowid }}">
                        <a href="https://news.ycombinator.com/item?id={{ $post->rowid }}" target="_blank">
                            {!! $post->content !!}
                        </a>
                    </li>
                @endforeach
            </ul>
            
        @else
            <p>No results found.</p>
        @endif
    @else
        <p wire:loading.remove>
            Results will show up here
        </p>
    @endif
</div>
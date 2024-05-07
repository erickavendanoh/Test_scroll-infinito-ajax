@foreach ($posts as $post)
    <div class="card mb-4">
        <div class="card-header">
            {{ $post->id }} : {{ $post->title }}
        </div>
        <div class="card-body">
            <p>{{ $post->content }}</p>
        </div>
    </div>
@endforeach



@extends ('layouts.app')
@section('title', $news->title)
@section('content')
<!-- Detail Berita -->
<div class="container mx-auto px-4 md:px-10 lg:px-14 py-8">
  <div class="border border-slate-200 p-3 rounded-xl hover:border-primary">
    <div class="font-bold text-xl md:text-2xl lg:text-3xl mb-6 text-center">
      <p>{{$news->title}}</p>
    </div>
    
    <!-- Berita Utama - Full Width -->
    <div class="w-full">
      <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{$news->title}}" class="w-full rounded-xl mb-6 news-thumbnail">
      <div class="news-content">
        {!! $news->content !!}
      </div>
    </div>
  </div>

  <!-- Berita Terbaru Lainnya - Moved Below -->
  <div class="mt-10">
    <p class="font-bold mb-8 text-xl lg:text-2xl">Berita Terbaru Lainnya</p>
    <!-- Berita Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach ($newests as $new)
      <a href="{{ route('news.show', $new->slug) }}" class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out flex flex-col">
        <img src="{{ asset('storage/' . $new->thumbnail) }}" alt="{{ $new->title }}" 
             class="rounded-xl w-full mb-3 news-thumbnail-medium">
        <div class="category-badge">
          {{ $new->newsCategory->title }}
        </div>
        <div class="mt-2">
          <p class="font-semibold text-lg line-clamp-2">{{ $new->title }}</p>
          <p class="text-slate-400 mt-3 text-sm font-normal">
            {!! \Str::limit($new->content, 100) !!}
          </p>
        </div>
      </a>
      @endforeach
    </div>
  </div>

  <!-- Author Section -->
  <div class="flex flex-col gap-4 mb-10 p-4 lg:p-10 w-full">
    <p class="font-semibold text-xl lg:text-2xl mb-2">Author</p>
    <a href="author.html">
      <div
        class="flex flex-col md:flex-row gap-4 items-center border border-slate-300 rounded-xl p-6 lg:p-8 hover:border-primary transition">
        <img src="{{ $news->author->avatar_url }}" 
             alt="{{ $news->author->name }}" 
             class="rounded-full w-24 lg:w-28 border-2 border-primary author-image author-img">
        <div class="text-center md:text-left">
          <p class="font-bold text-lg lg:text-xl">{{ $news->author->user->name }}</p>
          <p class="text-sm lg:text-base leading-relaxed">
           {{ \Str::limit($news->author->bio, 150) }}
          </p>
        </div>
      </div>
    </a>
  </div>
</div>

@endsection
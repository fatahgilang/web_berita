@extends('layouts.app')
@section('title', 'Semua Berita - Maos')
@section('content')

<div class="container mx-auto px-4 md:px-10 lg:px-14 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Semua Berita</h1>
        <p class="text-gray-600">Temukan berita terbaru dan terpercaya</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-8">
        <form method="GET" action="{{ route('news.all') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berita..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <!-- Category Filter -->
            <div class="md:w-64">
                <select name="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" 
                                {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Search Button -->
            <button type="submit" 
                    class="bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary-dark transition duration-300 font-semibold">
                Cari
            </button>
        </form>
    </div>

    <!-- Results Info -->
    @if(request('search') || request('category'))
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">
                Menampilkan {{ $news->count() }} dari {{ $news->total() }} berita 
                @if(request('search'))
                    untuk pencarian "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('category'))
                    @php
                        $selectedCategory = $categories->where('slug', request('category'))->first();
                    @endphp
                    dalam kategori "<strong>{{ $selectedCategory->title ?? '' }}</strong>"
                @endif
            </p>
            @if(request('search') || request('category'))
                <a href="{{ route('news.all') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Hapus filter
                </a>
            @endif
        </div>
    @endif

    <!-- News Grid -->
    @if($news->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($news as $item)
                <article class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:border-primary hover:shadow-lg transition duration-300 ease-in-out">
                    <a href="{{ route('news.show', $item->slug) }}" class="block">
                        <!-- Category Badge -->
                            <div class="absolute top-3 left-3 bg-primary text-white text-xs rounded-lg px-3 py-1 font-medium">
                                {{ $item->newsCategory->title }}
                            </div>
                    
                    <!-- Thumbnail -->
                        <div class="relative">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-48 object-cover">  
                        </div>
                        
                        <!-- Content -->
                        <div class="p-4">
                            <!-- Title -->
                            <h3 class="font-semibold text-lg leading-tight mb-2 line-clamp-2 hover:text-primary transition duration-300">
                                {{ $item->title }}
                            </h3>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                {!! \Str::limit(strip_tags($item->content), 120) !!}
                            </p>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center gap-2">
                                    @if($item->author && $item->author->avatar)
                                        <img src="{{ $item->author->avatar_url }}" 
                                             alt="{{ $item->author->name }}" 
                                             class="w-4 h-4 rounded-full"
                                             onerror="this.src='{{ asset('img/profile.svg') }}'">
                                    @endif
                                    <!-- <span>{{ $item->author->name ?? 'Unknown' }}</span> -->
                                </div>
                                <span>{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $news->withQueryString()->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-12">
            <div class="mb-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada berita ditemukan</h3>
            <p class="text-gray-500 mb-4">
                @if(request('search') || request('category'))
                    Coba ubah kata kunci pencarian atau kategori yang dipilih.
                @else
                    Belum ada berita yang tersedia saat ini.
                @endif
            </p>
            @if(request('search') || request('category'))
                <a href="{{ route('news.all') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark transition duration-300">
                    Lihat Semua Berita
                </a>
            @endif
        </div>
    @endif
</div>

@endsection
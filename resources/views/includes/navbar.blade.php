<!-- Nav -->
<nav class="sticky top-0 z-50 bg-white shadow-sm">
  <div class="container mx-auto px-4 lg:px-14">
    <div class="flex justify-between items-center py-5">
      <!-- Logo -->
      <a href="{{ route('landing') }}" class="flex items-center gap-2">
        <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-8 lg:w-10">
        <p class="text-lg lg:text-xl font-bold">Maos</p>
      </a>

      <!-- Mobile menu button -->
      <button class="lg:hidden text-primary text-2xl focus:outline-none" id="menu-toggle">
        ☰
      </button>

      <!-- Desktop Menu -->
      <div class="hidden lg:flex items-center gap-10">
        <ul class="flex items-center gap-4 font-medium text-base">
          <li>
            <a href="{{ route('landing') }}" class="{{ request()->is('/') ? 'text-primary' : 'hover:text-gray-600' }}">
              Beranda
            </a>
          </li>
          @foreach (App\Models\NewsCategory::all() as $category)
          <li>
            <a href="{{ route('news.category', $category->slug) }}" 
               class="{{ request()->is('category/'.$category->slug) ? 'text-primary' : 'hover:text-primary' }}">
              {{ $category->title }}
            </a>
          </li>
          @endforeach
        </ul>

        <!-- Search and Login -->
        <div class="flex items-center gap-2">
          <form action="{{ route('news.all') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Cari Berita"
              class="border border-slate-300 rounded-full px-4 py-2 pl-8 text-sm font-normal focus:outline-none focus:ring-primary focus:border-primary"
              id="searchInput" />
            <!-- Icon Search -->
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
              <img src="{{ asset('img/search.png') }}" alt="" class="w-4">
            </span>
          </form>
          <a href="/admin/login"
            class="bg-primary px-8 py-2 rounded-full text-white font-semibold h-fit text-sm lg:text-base login-button">
            Masuk
          </a>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden pb-5">
      <ul class="flex flex-col gap-4 font-medium text-base py-4">
        <li>
          <a href="{{ route('landing') }}" class="{{ request()->is('/') ? 'text-primary' : 'hover:text-gray-600' }}">
            Beranda
          </a>
        </li>
        @foreach (App\Models\NewsCategory::all() as $category)
        <li>
          <a href="{{ route('news.category', $category->slug) }}" 
             class="{{ request()->is('category/'.$category->slug) ? 'text-primary' : 'hover:text-primary' }}">
            {{ $category->title }}
          </a>
        </li>
        @endforeach
      </ul>

      <!-- Mobile Search and Login -->
      <div class="flex flex-col gap-4 mt-4">
        <form action="{{ route('news.all') }}" method="GET" class="relative">
          <input type="text" name="search" placeholder="Cari Berita"
            class="border border-slate-300 rounded-full px-4 py-2 pl-8 w-full text-sm font-normal focus:outline-none focus:ring-primary focus:border-primary"
            id="mobileSearchInput" />
          <!-- Icon Search -->
          <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
            <img src="{{ asset('img/search.png') }}" alt="" class="w-4">
          </span>
        </form>
        <a href="/admin/login"
          class="bg-primary px-8 py-2 rounded-full text-white font-semibold h-fit text-sm text-center login-button">
          Masuk
        </a>
      </div>
    </div>
  </div>
</nav>

<script>
  document.getElementById('menu-toggle').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
  });

  // Add functionality to submit search on Enter key
  document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      const searchValue = this.value.trim();
      if (searchValue) {
        window.location.href = "{{ route('news.all') }}?search=" + encodeURIComponent(searchValue);
      }
    }
  });

  document.getElementById('mobileSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      const searchValue = this.value.trim();
      if (searchValue) {
        window.location.href = "{{ route('news.all') }}?search=" + encodeURIComponent(searchValue);
      }
    }
  });
</script>
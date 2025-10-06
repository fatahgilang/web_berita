@extends (layouts.app)
@section('title', $author->name)
@section('content')
<!-- Author -->
    <div class="flex gap-4 items-center mb-10 text-white p-10 bg-cover"
    style="background-image: url('{{ asset('assets/img/bg-profile.png') }}');">
      <img src="{{ asset('storage/' . $author->avatar) }}" alt="profile" class="rounded-full max-w-28 ">
      <div class="">
        <p class="font-bold text-lg">{{ $author->name}}</p>
        <p>
            {{ $author->bio }}
        </p>
      </div>
    </div>

    <!-- Berita -->
    <div class=" flex flex-col gap-5 px-4 lg:px-14">
      <div class="grid sm:grid-cols-1 gap-5 lg:grid-cols-4">
        <a href="detail-MotoGp.html">
          <div
            class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out">
            <div class="bg-primary text-white rounded-full w-fit px-5 py-1 font-normal ml-2 mt-2 text-sm absolute">
              Pariwisata</div>
            <img src="img/Berita-Liburan.png" alt="" class="w-full rounded-xl mb-3">
            <p class="font-bold text-base mb-1">Spot Liburan Asyik Di Banyumas Yang Bisa Kamu Kunjungi</p>
            <p class="text-slate-400">22 Januari 2024</p>
          </div>
        </a>
        <a href="detail-MotoGp.html">
          <div
            class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out">
            <div class="bg-primary text-white rounded-full w-fit px-5 py-1 font-normal ml-2 mt-2 text-sm absolute">
              Politik</div>
            <img src="img/Berita-Demo.png" alt="" class="w-full rounded-xl mb-3">
            <p class="font-bold text-base mb-1">Demo Terjadi Di Banyumas <br>Dikarenakan Kenaikan BBM</p>
            <p class="text-slate-400">22 Januari 2024</p>
          </div>
        </a>
        <a href="detail-MotoGp.html">
          <div
            class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out">
            <div class="bg-primary text-white rounded-full w-fit px-4 py-1 font-normal ml-2 mt-2 text-sm absolute">
              Olahraga</div>
            <img src="img/Berita-Motor.png" alt="" class="w-full rounded-xl mb-3">
            <p class="font-bold text-base mb-1">MotoGp 2025 Akan Diadakan Di <br>Sirkuit Mandalika</p>
            <p class="text-slate-400">22 Januari 2024</p>
          </div>
        </a>
        <a href="detail-MotoGp.html">
          <div
            class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out">
            <div class="bg-primary text-white rounded-full w-fit px-4 py-1 font-normal ml-2 mt-2 text-sm absolute">
              Gaya Hidup</div>
            <img src="img/Berita-Golf.png" alt="" class="w-full rounded-xl mb-3">
            <p class="font-bold text-base mb-1">Manfaat Bermain Golf Untuk <br>Menumbuhkan Koneksi</p>
            <p class="text-slate-400">22 Januari 2024</p>
          </div>
        </a>
      </div>

    </div>
@endsection
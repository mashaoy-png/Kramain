@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')

<div class="mb-7 text-center">
    <h1 class="font-display font-bold text-xl" style="color:var(--ink)">Selamat datang kembali</h1>
    <p class="text-sm mt-1" style="color:var(--muted)">Masuk untuk melanjutkan belajar</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-5" role="alert">
    <ul class="text-sm text-red-700 space-y-1">
        @foreach($errors->all() as $error)
        <li class="flex items-start gap-2"><span class="mt-0.5 flex-shrink-0">•</span>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               required autofocus autocomplete="email"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-[var(--line)] bg-white' }}"
               placeholder="nama@email.com">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">Kata Sandi</label>
        <input type="password" id="password" name="password"
               required autocomplete="current-password"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-[var(--line)] bg-white' }}"
               placeholder="••••••••">
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="remember" name="remember"
               class="w-4 h-4 rounded border-[var(--line)] text-[var(--wedel)] focus:ring-[var(--wedel)] focus:ring-offset-0 cursor-pointer">
        <label for="remember" class="text-sm cursor-pointer select-none" style="color:var(--muted)">
            Ingat saya di perangkat ini
        </label>
    </div>

    <button type="submit"
            class="w-full py-3 px-4 text-white font-semibold text-sm rounded-full transition
                   hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--wedel)]
                   focus:ring-offset-2 active:scale-95"
            style="background:var(--wedel)">
        Masuk
    </button>
</form>

<p class="text-center text-sm mt-6" style="color:var(--muted)">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-medium hover:underline" style="color:var(--wedel)">
        Daftar sekarang
    </a>
</p>

@endsection

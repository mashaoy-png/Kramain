@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')

<div class="mb-7 text-center">
    <h1 class="font-display font-bold text-xl" style="color:var(--ink)">Buat akun belajar</h1>
    <p class="text-sm mt-1" style="color:var(--muted)">Gratis · Mulai dari Level 1 Ngoko Lugu</p>
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

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               required autofocus autocomplete="name"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-[var(--line)] bg-white' }}"
               placeholder="Nama kamu">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               required autocomplete="email"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-[var(--line)] bg-white' }}"
               placeholder="nama@email.com">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">Kata Sandi</label>
        <input type="password" id="password" name="password"
               required autocomplete="new-password"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-[var(--line)] bg-white' }}"
               placeholder="Minimal 8 karakter">
        <p class="text-xs mt-1.5" style="color:var(--muted)">Gunakan minimal 8 karakter</p>
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1.5" style="color:var(--ink)">
            Konfirmasi Kata Sandi
        </label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               required autocomplete="new-password"
               class="w-full px-3.5 py-2.5 rounded-xl border text-sm transition
                      focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent
                      border-[var(--line)] bg-white"
               placeholder="Ulangi kata sandi">
    </div>

    <div class="pt-2">
        <button type="submit"
                class="w-full py-3 px-4 text-white font-semibold text-sm rounded-full transition
                       hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--wedel)]
                       focus:ring-offset-2 active:scale-95"
                style="background:var(--wedel)">
            Buat Akun &amp; Mulai Belajar
        </button>
        <p class="text-xs text-center mt-3 leading-relaxed" style="color:var(--muted)">
            Dengan mendaftar, akun kamu akan otomatis dimulai dari Level 1 Ngoko Lugu.
        </p>
    </div>
</form>

<p class="text-center text-sm mt-5" style="color:var(--muted)">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-medium hover:underline" style="color:var(--wedel)">
        Masuk di sini
    </a>
</p>

@endsection

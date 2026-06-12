@extends('layout.app')
@section('title', 'Edit Profil')

@section('content')

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4">

        <div class="mt-12 mb-6">
            <a href="{{ route('pelanggan.profile') }}"
                class="text-orange-500 hover:text-orange-600 font-bold text-sm inline-flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Profil

            </a>
        </div>

        {{-- GRID LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h1 class="text-2xl font-bold text-slate-800 mt-3 flex items-center gap-2">
                        <i class="fas fa-user-edit text-orange-500"></i>
                        Edit Profil
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Ubah data pribadi dan foto profil Anda.
                    </p>
                </div>

                <form action="{{ route('pelanggan.profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text"
                            name="name"
                            value="{{ $user->name }}"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text"
                            value="{{ $user->email }}"
                            class="form-control"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Nomor Handphone (WhatsApp)
                        </label>

                        <input type="text"
                            name="phone"
                            value="{{ $user->phone }}"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file"
                            name="foto"
                            class="form-control">
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-200 mt-6">
                        <button
                            type="submit"
                            class="px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-semibold transition">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('pelanggan.profile') }}"
                        class="px-8 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
            

            {{-- PREVIEW PROFIL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 h-fit">

                <h3 class="text-xl font-semibold text-slate-800 mb-8">
                    Preview Profil
                </h3>

                <div>
                    <div class="flex justify-center">
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $user->foto) }}"
                                class="w-36 h-36 rounded-full object-cover border-4 border-orange-500">

                            <div class="absolute bottom-0 right-2 w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center border-4 border-white">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 space-y-5">

                    {{-- NAMA --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-orange-500"></i>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400">
                                Nama
                            </span>

                            <span class="text-sm text-slate-700">
                                {{ $user->name }}
                            </span>
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-orange-500"></i>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400">
                                Email
                            </span>

                            <span class="text-sm text-slate-700 break-all">
                                {{ $user->email }}
                            </span>
                        </div>
                    </div>

                    {{-- WHATSAPP --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-whatsapp text-orange-500"></i>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400">
                                WhatsApp
                            </span>

                            <span class="text-sm text-slate-700">
                                {{ $user->phone ?? '-' }}
                            </span>
                        </div>
                    </div>

                </div>

                </div>

            </div>
        </div>
    </div>
</div>
    
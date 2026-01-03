@extends('layouts.app')

@section('title', 'Kelola Lokasi Gudang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola Lokasi Gudang</h1>
</div>

{{-- LOGIKA TAB AKTIF --}}
@php
    // Ambil tab aktif dari session controller, default ke 'gudang' jika kosong
    $activeTab = session('active_tab', 'gudang');
@endphp

<div class="card shadow border-0">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="location-tabs" role="tablist">
            {{-- Tab Gudang --}}
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'gudang' ? 'active' : '' }}"
                   id="gudang-tab" data-bs-toggle="tab" href="#gudang-content" role="tab">
                   Gudang
                </a>
            </li>
            {{-- Tab Gate --}}
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'gate' ? 'active' : '' }}"
                   id="gate-tab" data-bs-toggle="tab" href="#gate-content" role="tab">
                   Gate
                </a>
            </li>
            {{-- Tab Block --}}
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'block' ? 'active' : '' }}"
                   id="block-tab" data-bs-toggle="tab" href="#block-content" role="tab">
                   Block
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="location-tabs-content">

            {{-- Konten Gudang --}}
            <div class="tab-pane fade {{ $activeTab == 'gudang' ? 'show active' : '' }}"
                 id="gudang-content" role="tabpanel">
                @include('settings.partials.locations_gudang')
            </div>

            {{-- Konten Gate --}}
            <div class="tab-pane fade {{ $activeTab == 'gate' ? 'show active' : '' }}"
                 id="gate-content" role="tabpanel">
                @include('settings.partials.locations_gate')
            </div>

            {{-- Konten Block --}}
            <div class="tab-pane fade {{ $activeTab == 'block' ? 'show active' : '' }}"
                 id="block-content" role="tabpanel">
                @include('settings.partials.locations_block')
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(formId, message) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush

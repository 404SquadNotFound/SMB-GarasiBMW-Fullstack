{{--
    resources/views/layouts/form_wrapper_antrian.blade.php
    =========================================================
    Layout reusable untuk halaman Tambah & Edit Antrian Pengerjaan.

    Props yang dikirim via @include:
      $backUrl       – URL tombol Kembali
      $submitBtnText – Label tombol submit
      $sectionTitle  – Judul section (tidak dipakai di layout ini, diteruskan ke form_wrapper asli)

    Sections yang harus didefinisikan di view pemanggil:
      @section('form_icon')   – ikon header card
      @section('form_title')  – teks header card
      @section('form_fields') – seluruh isi field form

    CSS yang ada di sini:
      - .main-form-content
      - html, body reset
      - #stokDropdownWrapper
      - .stok-option-item dan turunannya
      - #stokDropdownItems scrollbar
      - .custom-scrollbar
--}}

<style>
    .main-form-content {
        max-width: 100%;
        overflow: visible;
    }

    html, body {
        height: 100%;
        margin: 0;
    }

    #stokDropdownWrapper {
        position: relative;
    }

    .form-container-class {
        overflow: visible !important;
    }

    .stok-option-item {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid #F0F4FA;
        cursor: pointer;
        transition: background 0.15s;
    }
    .stok-option-item:last-child {
        border-bottom: none;
    }
    .stok-option-item:hover {
        background-color: #F9FBFF;
    }
    .stok-option-item.selected {
        background-color: #EAF2FF;
    }
    .stok-option-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border-right: 1px solid #F0F4FA;
        min-width: 64px;
        background-color: #F9FBFF;
    }
    .stok-option-left-label {
        font-size: 11px;
        color: #9CA3AF;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .stok-option-left-value {
        font-size: 22px;
        font-weight: 800;
        color: #213F5C;
        line-height: 1;
    }
    .stok-option-right {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 12px 16px;
        flex: 1;
    }
    .stok-option-name {
        font-size: 14px;
        font-weight: 800;
        color: #213F5C;
        margin-bottom: 4px;
    }
    .stok-option-name span.qty {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        margin-left: 6px;
    }
    .stok-option-meta {
        font-size: 11px;
        color: #9CA3AF;
    }
    .stok-option-meta span {
        margin-right: 4px;
    }

    #stokDropdownItems {
        max-height: 320px !important;
        overflow-y: auto !important;
        display: block !important;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

{{-- Render layout dua kolom persis seperti form_wrapper asli --}}
@include('layouts.form_wrapper', [
    'backUrl'       => $backUrl,
    'submitBtnText' => $submitBtnText,
    'sectionTitle'  => $sectionTitle,
])
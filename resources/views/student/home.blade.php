@extends('layouts.vhelp')

@section('content')
@php
  $typeCount = \App\Models\RequestType::where('is_active', true)->count();
  $icons = [
    'cert' => 'bi-patch-check-fill', 'transcript' => 'bi-journal-text', 'edit_info' => 'bi-pencil-square',
    'scholar' => 'bi-award-fill', 'tuition' => 'bi-cash-coin', 'counsel' => 'bi-chat-heart-fill',
    'repair' => 'bi-tools', 'lab' => 'bi-pc-display',
    'net_reset' => 'bi-wifi', 'email_help' => 'bi-envelope-exclamation-fill',
  ];
  $quick = [
    ['ยื่นคำร้องออนไลน์', "เลือกได้ {$typeCount} ประเภท", 'bi-file-earmark-plus-fill', '#003399', '#e8eefc', route('student.create')],
    ['ขอคำปรึกษา', 'ด้านการเรียน', 'bi-chat-heart-fill', '#FF5E00', '#ffece0', route('student.create', ['type' => 'counsel'])],
    ['ตรวจสอบสถานะ', 'ติดตามความคืบหน้า', 'bi-search-heart-fill', '#198754', '#e3f6ea', route('student.index')],
    ['สอบถามเจ้าหน้าที่', 'ช่องทางติดต่อ', 'bi-headset', '#1f5bd6', '#e6eefc', route('student.contact')],
  ];
@endphp

<style>
  .vh-hero2{position:relative;overflow:hidden;border-radius:22px;padding:38px 40px;color:#fff;background:linear-gradient(120deg,#002a80 0%,#003399 55%,#1f5bd6 100%);box-shadow:0 14px 30px rgba(0,51,153,.28)}
  .vh-hero2::before{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.10)}
  .vh-hero2::after{content:"";position:absolute;border-radius:50%;background:rgba(255,94,0,.85)}
  .vh-hero2::before{width:320px;height:320px;right:-70px;top:-110px}
  .vh-hero2::after{width:200px;height:200px;right:150px;bottom:-110px}
  .vh-hero2 h2{font-weight:700;line-height:1.35}
  .vh-hero-art{position:absolute;right:48px;top:50%;transform:translateY(-50%);width:170px;height:170px;border-radius:50%;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;z-index:1}
  .vh-hero-art .main{font-size:5rem}
  .vh-hero-art .chip{position:absolute;width:46px;height:46px;border-radius:14px;background:#fff;color:#003399;display:flex;align-items:center;justify-content:center;font-size:1.3rem;box-shadow:0 8px 18px rgba(0,0,0,.18)}
  .vh-hero-art .c1{left:-26px;top:16px}
  .vh-hero-art .c2{right:-18px;bottom:6px;color:#198754}
  .vh-q{display:flex;align-items:center;gap:14px;padding:18px;height:100%;text-decoration:none;color:#2b2f55;transition:.15s}
  .vh-q:hover{transform:translateY(-3px);box-shadow:0 12px 26px rgba(40,50,120,.14)!important;color:#2b2f55}
  .vh-q .ico{width:54px;height:54px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0}
  .vh-q .go{margin-left:auto;color:#a3a8d0}
  .vh-rec{display:flex;align-items:center;gap:14px;padding:14px 8px;border-top:1px solid #eef0f8;text-decoration:none;color:inherit;border-radius:12px}
  .vh-rec:hover{background:#f4f7fe;color:inherit}
  .vh-rec .ico{width:48px;height:48px;border-radius:14px;background:#e8eefc;color:#003399;display:flex;align-items:center;justify-content:center;font-size:1.35rem;flex-shrink:0}
</style>

<div class="mb-3">
  <h4 class="fw-bold mb-0">สวัสดี, {{ auth()->user()->name }} 👋</h4>
  <div class="text-muted">วันนี้ต้องการให้ศูนย์บริการช่วยเรื่องอะไรคะ</div>
</div>

<div class="vh-hero2 mb-4">
  <div class="position-relative" style="z-index:2;max-width:560px">
    <div class="small opacity-75 mb-1">V-Help · วิทยาลัยอาชีวศึกษาฉะเชิงเทรา</div>
    <h2 class="mb-2">ศูนย์บริการนักศึกษา<br>พร้อมดูแลในทุกเรื่องของคุณ</h2>
    <p class="mb-4 opacity-75">ยื่นคำร้อง แนบเอกสาร และติดตามสถานะได้ในที่เดียว</p>
    <a href="{{ route('student.create') }}" class="btn btn-light btn-lg fw-semibold px-4" style="color:#003399">
      ยื่นคำร้องเลย <i class="bi bi-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="vh-hero-art d-none d-md-flex">
    <i class="bi bi-mortarboard-fill main"></i>
    <span class="chip c1"><i class="bi bi-file-earmark-check-fill"></i></span>
    <span class="chip c2"><i class="bi bi-bell-fill"></i></span>
  </div>
</div>

<div class="row g-3 mb-4">
  @foreach($quick as [$title, $sub, $icon, $color, $bg, $url])
    <div class="col-12 col-md-6 col-xl-3">
      <a href="{{ $url }}" class="vh-card vh-q">
        <div class="ico" style="background:{{ $bg }};color:{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        <div>
          <div class="fw-bold">{{ $title }}</div>
          <small class="text-muted">{{ $sub }}</small>
        </div>
        <i class="bi bi-chevron-right go"></i>
      </a>
    </div>
  @endforeach
</div>

<div class="vh-card p-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold mb-0">คำร้องล่าสุดของคุณ</h5>
    <a href="{{ route('student.index') }}" class="text-decoration-none fw-semibold" style="color:#003399">ดูทั้งหมด <i class="bi bi-chevron-right"></i></a>
  </div>

  @forelse($recent as $r)
    <a href="{{ route('student.show', $r) }}" class="vh-rec">
      <div class="ico"><i class="bi {{ $icons[$r->type->code] ?? 'bi-file-earmark-text' }}"></i></div>
      <div class="flex-grow-1" style="min-width:0">
        <div class="fw-semibold">{{ $r->type->name }}</div>
        <small class="text-muted">{{ $r->ticket_no }} · วันที่ยื่น: {{ $r->thai_date }}</small>
      </div>
      <span class="badge rounded-pill text-bg-{{ $r->status_color }} px-3 py-2">{{ $r->status_label }}</span>
    </a>
  @empty
    <div class="text-center py-4">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <div class="text-muted mb-3">ยังไม่มีคำร้อง เริ่มต้นได้เลยที่ปุ่ม "ยื่นคำร้องเลย"</div>
    </div>
  @endforelse
</div>
@endsection

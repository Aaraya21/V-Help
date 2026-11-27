@extends('layouts.vhelp')

@section('content')
@php
  $c = fn ($k) => (int) ($counts[$k] ?? 0);
  $total = $counts->sum();
  $stats = [
    ['ทั้งหมด',        $total,                          'bi-collection-fill',      '#003399', '#e8eefc'],
    ['รอตรวจสอบ',      $c('pending') + $c('reviewing'), 'bi-hourglass-split',      '#d98a00', '#fff4dc'],
    ['กำลังดำเนินการ', $c('approved') + $c('processing'),'bi-arrow-repeat',        '#0b8fb3', '#e0f6fc'],
    ['เสร็จสิ้น',      $c('completed'),                 'bi-check-circle-fill',    '#198754', '#e3f6ea'],
  ];
  $icons = [
    'cert' => 'bi-patch-check-fill', 'transcript' => 'bi-journal-text', 'edit_info' => 'bi-pencil-square',
    'scholar' => 'bi-award-fill', 'tuition' => 'bi-cash-coin', 'counsel' => 'bi-chat-heart-fill',
    'repair' => 'bi-tools', 'lab' => 'bi-pc-display',
    'net_reset' => 'bi-wifi', 'email_help' => 'bi-envelope-exclamation-fill',
  ];
  $stepOf = ['pending' => 1, 'reviewing' => 2, 'approved' => 3, 'processing' => 3, 'completed' => 4, 'rejected' => 0];
  $current = request('status');
@endphp

<style>
  .vh-stat{display:flex;align-items:center;gap:14px;padding:16px 18px}
  .vh-stat .ico{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
  .vh-stat .num{font-size:1.6rem;font-weight:700;line-height:1}
  .vh-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:999px;background:#fff;border:1px solid #e3e5f5;color:#4a4f7a;text-decoration:none;font-size:.88rem;font-weight:500}
  .vh-pill:hover{border-color:#9db6ea;color:#002a80}
  .vh-pill.active{background:linear-gradient(90deg,#FF5E00,#ff8a3d);border-color:transparent;color:#fff}
  .vh-pill .cnt{background:rgba(0,0,0,.08);border-radius:999px;padding:0 8px;font-size:.78rem}
  .vh-pill.active .cnt{background:rgba(255,255,255,.25)}
  .vh-req{display:flex;align-items:center;gap:16px;padding:16px 18px;border-top:1px solid #eef0f8}
  .vh-req:first-child{border-top:0}
  .vh-req .ico{width:50px;height:50px;flex-shrink:0;border-radius:14px;background:#e8eefc;color:#003399;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
  .vh-bar{display:flex;gap:4px;width:130px}
  .vh-bar span{flex:1;height:6px;border-radius:4px;background:#e4e6f4}
  .vh-bar.s1 span:nth-child(-n+1),.vh-bar.s2 span:nth-child(-n+2),.vh-bar.s3 span:nth-child(-n+3){background:#003399}
  .vh-bar.s4 span{background:#198754}
  .vh-bar.s0 span{background:#dc3545}
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h3 class="fw-bold mb-1">ติดตามสถานะคำร้อง</h3>
    <div class="text-muted">ดูความคืบหน้าและประวัติคำร้องทั้งหมดของคุณ</div>
  </div>
  <a href="{{ route('student.create') }}" class="btn btn-vh"><i class="bi bi-plus-lg"></i> ยื่นคำร้องใหม่</a>
</div>

<div class="row g-3 mb-4">
  @foreach($stats as [$label, $num, $icon, $color, $bg])
    <div class="col-6 col-lg-3">
      <div class="vh-card vh-stat">
        <div class="ico" style="background:{{ $bg }};color:{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        <div>
          <div class="num">{{ $num }}</div>
          <small class="text-muted">{{ $label }}</small>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
  <a href="{{ route('student.index') }}" class="vh-pill {{ $current ? '' : 'active' }}">ทั้งหมด <span class="cnt">{{ $total }}</span></a>
  @foreach(\App\Models\ServiceRequest::STATUS as $key => $label)
    <a href="{{ route('student.index', ['status' => $key]) }}" class="vh-pill {{ $current === $key ? 'active' : '' }}">
      {{ $label }} <span class="cnt">{{ $c($key) }}</span>
    </a>
  @endforeach
</div>

<div class="vh-card overflow-hidden">
  @forelse($requests as $r)
    <div class="vh-req">
      <div class="ico"><i class="bi {{ $icons[$r->type->code] ?? 'bi-file-earmark-text' }}"></i></div>
      <div class="flex-grow-1" style="min-width:0">
        <div class="fw-semibold">{{ $r->type->name }}</div>
        <div class="text-truncate">{{ $r->subject }}</div>
        <small class="text-muted">{{ $r->ticket_no }} · {{ $r->thai_date }}</small>
      </div>
      <div class="d-none d-md-block text-center">
        <div class="vh-bar s{{ $stepOf[$r->status] ?? 0 }} mb-2"><span></span><span></span><span></span><span></span></div>
        <span class="badge rounded-pill text-bg-{{ $r->status_color }} px-3 py-2">{{ $r->status_label }}</span>
      </div>
      <a class="btn btn-sm btn-outline-primary" href="{{ route('student.show', $r) }}">ดูรายละเอียด</a>
    </div>
  @empty
    <div class="text-center py-5">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <div class="text-muted mb-3">{{ $current ? 'ไม่มีคำร้องในสถานะนี้' : 'ยังไม่มีคำร้อง' }}</div>
      <a href="{{ route('student.create') }}" class="btn btn-vh">ยื่นคำร้องแรกของคุณ</a>
    </div>
  @endforelse
</div>

<div class="mt-3">{{ $requests->links('pagination::bootstrap-5') }}</div>
@endsection

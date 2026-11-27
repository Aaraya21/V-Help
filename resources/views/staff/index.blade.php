@extends('layouts.vhelp')

@section('content')
@php
  $c = fn ($k) => (int) ($counts[$k] ?? 0);
  $total = $counts->sum();
  $stats = [
    ['ทั้งหมด',              $total,                                          'bi-collection-fill',   '#003399', '#e6edfb'],
    ['รอรับเรื่อง',          $c('pending'),                                   'bi-bell-fill',         '#FF5E00', '#ffece0'],
    ['กำลังดำเนินการ',       $c('reviewing') + $c('approved') + $c('processing'), 'bi-arrow-repeat',  '#1f5bd6', '#e6eefc'],
    ['เสร็จสิ้น',            $c('completed'),                                 'bi-check-circle-fill', '#198754', '#e3f6ea'],
  ];
  $icons = [
    'cert' => 'bi-patch-check-fill', 'transcript' => 'bi-journal-text', 'edit_info' => 'bi-pencil-square',
    'scholar' => 'bi-award-fill', 'tuition' => 'bi-cash-coin', 'counsel' => 'bi-chat-heart-fill',
    'repair' => 'bi-tools', 'lab' => 'bi-pc-display',
    'net_reset' => 'bi-wifi', 'email_help' => 'bi-envelope-exclamation-fill',
  ];
  $current = request('status');
  $keep = request()->except('status', 'page');
@endphp

<style>
  .vh-stat{display:flex;align-items:center;gap:14px;padding:16px 18px}
  .vh-stat .ico{width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
  .vh-stat .num{font-size:1.7rem;font-weight:700;line-height:1}
  .vh-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:999px;background:#fff;border:1px solid #dfe5f5;color:#3b4570;text-decoration:none;font-size:.88rem;font-weight:500}
  .vh-pill:hover{border-color:#9db6ea;color:#003399}
  .vh-pill.active{background:linear-gradient(90deg,#FF5E00,#ff8a3d);border-color:transparent;color:#fff}
  .vh-pill .cnt{background:rgba(0,0,0,.08);border-radius:999px;padding:0 8px;font-size:.78rem}
  .vh-pill.active .cnt{background:rgba(255,255,255,.28)}
  .vh-tbl thead th{background:#003399!important;color:#fff!important;font-weight:600;border:0;padding:14px 16px;white-space:nowrap}
  .vh-tbl thead th:first-child{border-top-left-radius:14px}
  .vh-tbl thead th:last-child{border-top-right-radius:14px}
  .vh-tbl td{padding:14px 16px;vertical-align:middle;border-color:#edf0f9}
  .vh-tbl tbody tr:hover{background:#f4f7fe}
  .vh-tbl tr.is-new{background:#fff8f2;box-shadow:inset 4px 0 0 #FF5E00}
  .vh-tbl tr.is-new:hover{background:#fff1e7}
  .vh-av{width:38px;height:38px;border-radius:50%;background:#e6edfb;color:#003399;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
  .vh-tico{width:34px;height:34px;border-radius:10px;background:#e6edfb;color:#003399;display:flex;align-items:center;justify-content:center;flex-shrink:0}
  .btn-act{background:linear-gradient(90deg,#FF5E00,#ff8a3d);color:#fff;border:0;border-radius:10px;font-weight:600;padding:.4rem 1rem;white-space:nowrap}
  .btn-act:hover{color:#fff;filter:brightness(1.06)}
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h3 class="fw-bold mb-1">จัดการคำร้อง</h3>
    <div class="text-muted">ตรวจสอบและดำเนินการคำร้องของนักศึกษา</div>
  </div>
  <a href="{{ route('staff.report') }}" class="btn btn-outline-primary"><i class="bi bi-bar-chart-fill me-1"></i> รายงานสถิติ</a>
</div>

<div class="row g-3 mb-4">
  @foreach($stats as [$label, $num, $icon, $color, $bg])
    <div class="col-6 col-xl-3">
      <div class="vh-card vh-stat">
        <div class="ico" style="background:{{ $bg }};color:{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        <div>
          <div class="num" style="color:{{ $color }}">{{ $num }}</div>
          <small class="text-muted">{{ $label }}</small>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- ค้นหา --}}
<form method="GET" class="vh-card p-3 mb-3">
  @if($current)<input type="hidden" name="status" value="{{ $current }}">@endif
  <div class="row g-2 align-items-center">
    <div class="col-lg-5">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="ค้นหาเลขที่ / หัวข้อ / ชื่อ / รหัสนักศึกษา">
      </div>
    </div>
    <div class="col-lg-4">
      <select name="type" class="form-select">
        <option value="">ทุกประเภทคำร้อง</option>
        @foreach($types as $t)
          <option value="{{ $t->id }}" @selected(request('type') == $t->id)>{{ $t->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-lg-3 d-flex gap-2">
      <button class="btn btn-vh flex-grow-1"><i class="bi bi-funnel-fill me-1"></i> ค้นหา</button>
      @if(request()->hasAny(['q','type','status']))
        <a href="{{ route('staff.index') }}" class="btn btn-light" title="ล้างตัวกรอง"><i class="bi bi-x-lg"></i></a>
      @endif
    </div>
  </div>
</form>

{{-- กรองตามสถานะ --}}
<div class="d-flex flex-wrap gap-2 mb-3">
  <a href="{{ route('staff.index', $keep) }}" class="vh-pill {{ $current ? '' : 'active' }}">ทั้งหมด <span class="cnt">{{ $total }}</span></a>
  @foreach(\App\Models\ServiceRequest::STATUS as $key => $label)
    <a href="{{ route('staff.index', array_merge($keep, ['status' => $key])) }}" class="vh-pill {{ $current === $key ? 'active' : '' }}">
      {{ $label }} <span class="cnt">{{ $c($key) }}</span>
    </a>
  @endforeach
</div>

<div class="vh-card overflow-hidden">
  <div class="table-responsive">
    <table class="table vh-tbl mb-0 align-middle">
      <thead>
        <tr>
          <th>เลขที่</th><th>นักศึกษา</th><th>ประเภท</th><th>หัวข้อ</th><th>สถานะ</th><th>วันที่ยื่น</th><th></th>
        </tr>
      </thead>
      <tbody>
      @forelse($requests as $r)
        <tr class="{{ $r->status === 'pending' ? 'is-new' : '' }}">
          <td class="fw-bold" style="color:#003399;white-space:nowrap">{{ $r->ticket_no }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="vh-av">{{ mb_substr($r->user->name, 0, 1) }}</div>
              <div>
                <div class="fw-semibold lh-sm">{{ $r->user->name }}</div>
                <small class="text-muted">{{ $r->user->student_code ?: '-' }}</small>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="vh-tico"><i class="bi {{ $icons[$r->type->code] ?? 'bi-file-earmark-text' }}"></i></div>
              <div>
                <div class="lh-sm">{{ $r->type->name }}</div>
                @if($r->type->need_approval)<span class="badge rounded-pill" style="background:#ffece0;color:#FF5E00">ต้องอนุมัติ</span>@endif
              </div>
            </div>
          </td>
          <td style="max-width:220px"><div class="text-truncate">{{ $r->subject }}</div></td>
          <td><span class="badge rounded-pill text-bg-{{ $r->status_color }} px-3 py-2">{{ $r->status_label }}</span></td>
          <td style="white-space:nowrap">
            {{ $r->created_at->format('d/m/') . ($r->created_at->year + 543) }}<br>
            <small class="text-muted">{{ $r->created_at->format('H:i') }} น.</small>
          </td>
          <td class="text-end"><a class="btn btn-act btn-sm" href="{{ route('staff.show', $r) }}">ดำเนินการ <i class="bi bi-arrow-right"></i></a></td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center py-5">
          <i class="bi bi-inbox fs-1 text-muted"></i>
          <div class="text-muted">ไม่พบคำร้องตามเงื่อนไขที่เลือก</div>
        </td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $requests->links('pagination::bootstrap-5') }}</div>
@endsection

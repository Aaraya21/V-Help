@extends('layouts.vhelp')

@section('content')
@php
  $statusLabels = \App\Models\ServiceRequest::STATUS;
  $statusColors = ['pending'=>'#FF5E00','reviewing'=>'#FFB27A','approved'=>'#6b95e6','processing'=>'#003399','completed'=>'#198754','rejected'=>'#dc3545'];
  $done = (int) $byStatus->get('completed', 0);
  $waiting = (int) $byStatus->get('pending', 0);
  $rate = $total > 0 ? round($done * 100 / $total) : 0;
  $maxType = max(1, (int) $byType->max());
  $stats = [
    ['คำร้องทั้งหมด',  $total,     'bi-collection-fill',   '#003399', '#e6edfb', 'ทุกสถานะ'],
    ['เดือนนี้',       $thisMonth, 'bi-calendar-event-fill','#1f5bd6', '#e6eefc', 'ยื่นในเดือนปัจจุบัน'],
    ['รอรับเรื่อง',    $waiting,   'bi-bell-fill',         '#FF5E00', '#ffece0', 'ยังไม่มีผู้ดำเนินการ'],
    ['เสร็จสิ้น',      $done,      'bi-check-circle-fill', '#198754', '#e3f6ea', 'คิดเป็น '.$rate.'% ของทั้งหมด'],
  ];
@endphp

<style>
  .vh-stat{display:flex;align-items:center;gap:14px;padding:18px}
  .vh-stat .ico{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
  .vh-stat .num{font-size:1.8rem;font-weight:700;line-height:1}
  .vh-title{display:flex;align-items:center;gap:10px;font-weight:700;margin-bottom:16px}
  .vh-title i{width:34px;height:34px;border-radius:10px;background:#e6edfb;color:#003399;display:flex;align-items:center;justify-content:center}
  .vh-legend li{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #eef0f8}
  .vh-legend li:last-child{border-bottom:0}
  .vh-dot{width:12px;height:12px;border-radius:50%;flex-shrink:0}
  .vh-rank{display:flex;align-items:center;gap:12px;margin-bottom:14px}
  .vh-rank .no{width:26px;height:26px;border-radius:50%;background:#e6edfb;color:#003399;font-weight:700;font-size:.8rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
  .vh-rank:first-child .no{background:#FF5E00;color:#fff}
  .vh-track{height:10px;border-radius:6px;background:#edf0f9;overflow:hidden}
  .vh-fill{height:100%;border-radius:6px;background:linear-gradient(90deg,#003399,#1f5bd6)}
  .vh-rank:first-child .vh-fill{background:linear-gradient(90deg,#FF5E00,#ff8a3d)}
  .vh-donut{position:relative;max-width:230px;margin:0 auto}
  .vh-donut .center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none}
  @media print{.vh-sidebar,.vh-top,.no-print{display:none!important}.vh-main{width:100%}}
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h3 class="fw-bold mb-1">รายงานสถิติการให้บริการ</h3>
    <div class="text-muted">สรุปภาพรวมคำร้องทั้งหมด ณ วันที่ {{ now()->format('d/m/') . (now()->year + 543) }}</div>
  </div>
  <button onclick="window.print()" class="btn btn-outline-primary no-print"><i class="bi bi-printer-fill me-1"></i> พิมพ์รายงาน</button>
</div>

{{-- สรุปตัวเลข --}}
<div class="row g-3 mb-4">
  @foreach($stats as [$label, $num, $icon, $color, $bg, $note])
    <div class="col-6 col-xl-3">
      <div class="vh-card vh-stat">
        <div class="ico" style="background:{{ $bg }};color:{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        <div>
          <div class="num" style="color:{{ $color }}">{{ $num }}</div>
          <div class="fw-semibold small">{{ $label }}</div>
          <small class="text-muted">{{ $note }}</small>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="row g-3 mb-3">
  {{-- สถานะ --}}
  <div class="col-lg-5">
    <div class="vh-card p-4 h-100">
      <div class="vh-title"><i class="bi bi-pie-chart-fill"></i> สัดส่วนตามสถานะ</div>
      @if($total > 0)
        <div class="vh-donut mb-3">
          <canvas id="cStatus"></canvas>
          <div class="center"><div class="fs-2 fw-bold" style="color:#003399">{{ $total }}</div><small class="text-muted">คำร้อง</small></div>
        </div>
      @endif
      <ul class="list-unstyled vh-legend mb-0">
        @foreach($statusLabels as $key => $label)
          @php $n = (int) $byStatus->get($key, 0); @endphp
          <li>
            <span class="vh-dot" style="background:{{ $statusColors[$key] }}"></span>
            <span class="flex-grow-1">{{ $label }}</span>
            <span class="fw-bold">{{ $n }}</span>
            <small class="text-muted" style="width:48px;text-align:right">{{ $total > 0 ? round($n * 100 / $total) : 0 }}%</small>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  {{-- ประเภทคำร้อง --}}
  <div class="col-lg-7">
    <div class="vh-card p-4 h-100">
      <div class="vh-title"><i class="bi bi-trophy-fill"></i> ประเภทคำร้องที่ขอมากที่สุด</div>
      @foreach($byType as $name => $n)
        <div class="vh-rank">
          <span class="no">{{ $loop->iteration }}</span>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between mb-1">
              <span class="fw-medium">{{ $name }}</span>
              <span class="fw-bold">{{ $n }} <small class="text-muted fw-normal">รายการ</small></span>
            </div>
            <div class="vh-track"><div class="vh-fill" style="width:{{ $n > 0 ? max(4, round($n * 100 / $maxType)) : 0 }}%"></div></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- รายเดือน --}}
<div class="vh-card p-4">
  <div class="vh-title"><i class="bi bi-bar-chart-fill"></i> จำนวนคำร้อง 6 เดือนล่าสุด</div>
  <div style="height:280px"><canvas id="cMonth"></canvas></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Noto Sans Thai', sans-serif";
Chart.defaults.color = '#4a4f7a';

@if($total > 0)
new Chart(document.getElementById('cStatus'), {
  type: 'doughnut',
  data: {
    labels: @json(array_values($statusLabels)),
    datasets: [{
      data: @json($byStatus->values()),
      backgroundColor: @json(array_values($statusColors)),
      borderWidth: 3, borderColor: '#fff'
    }]
  },
  options: { cutout: '68%', plugins: { legend: { display: false } } }
});
@endif

const monthly = @json($monthly);
const lastIndex = monthly.length - 1;
new Chart(document.getElementById('cMonth'), {
  type: 'bar',
  data: {
    labels: monthly.map(m => m.label),
    datasets: [{
      label: 'จำนวนคำร้อง',
      data: monthly.map(m => m.total),
      backgroundColor: monthly.map((m, i) => i === lastIndex ? '#FF5E00' : '#003399'),
      borderRadius: 8, maxBarThickness: 56
    }]
  },
  options: {
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 }, grid: { color: '#eef0f8' } },
      x: { grid: { display: false } }
    }
  }
});
</script>
@endpush

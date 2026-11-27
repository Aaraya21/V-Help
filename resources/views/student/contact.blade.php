@extends('layouts.vhelp')

@section('content')
{{-- แก้ข้อมูลติดต่อจริงของวิทยาลัยในตัวแปร $channels ด้านล่าง --}}
@php
$channels = [
  ['สถานที่',    'อาคารอำนวยการ ชั้น 1',          'bi-geo-alt-fill',   '#003399', '#e8eefc'],
  ['โทรศัพท์',   '0-0000-0000',                   'bi-telephone-fill', '#198754', '#e3f6ea'],
  ['อีเมล',      'service@college.ac.th',         'bi-envelope-fill',  '#FF5E00', '#ffece0'],
  ['เวลาทำการ',  'จันทร์-ศุกร์ 08:30-16:30 น.',   'bi-clock-fill',     '#1f5bd6', '#e6eefc'],
];
@endphp

<style>
  .vh-ch{padding:22px;height:100%;text-align:center}
  .vh-ch .ico{width:64px;height:64px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 12px}
</style>

<div class="mb-4">
  <h3 class="fw-bold mb-1">ติดต่อเรา</h3>
  <div class="text-muted">ศูนย์บริการนักศึกษา วิทยาลัยอาชีวศึกษา พร้อมให้ความช่วยเหลือ</div>
</div>

<div class="row g-3 mb-4">
  @foreach($channels as [$label, $value, $icon, $color, $bg])
    <div class="col-12 col-md-6 col-xl-3">
      <div class="vh-card vh-ch">
        <div class="ico" style="background:{{ $bg }};color:{{ $color }}"><i class="bi {{ $icon }}"></i></div>
        <div class="text-muted small">{{ $label }}</div>
        <div class="fw-bold">{{ $value }}</div>
      </div>
    </div>
  @endforeach
</div>

<div class="row g-3">
  <div class="col-lg-7">
    <div class="vh-card p-4 h-100">
      <h5 class="fw-bold mb-3"><i class="bi bi-clipboard-check-fill me-2" style="color:#003399"></i>ก่อนติดต่อเจ้าหน้าที่</h5>
      <ul class="list-unstyled mb-0">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>เตรียมเลขที่คำร้อง เช่น VH-2569-0001</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>เตรียมรหัสนักศึกษาและชื่อ-นามสกุล</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>ตรวจสอบสถานะและหมายเหตุของคำร้องในระบบก่อน</li>
        <li><i class="bi bi-check-circle-fill text-success me-2"></i>แจ้งรายละเอียดที่ต้องการสอบถามให้ชัดเจน</li>
      </ul>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="vh-card p-4 h-100" style="background:#e8eefc!important">
      <h5 class="fw-bold mb-2">ตรวจสอบคำร้องของคุณ</h5>
      <p class="text-muted small">ดูสถานะและหมายเหตุจากเจ้าหน้าที่ได้ทันที โดยไม่ต้องรอติดต่อ</p>
      <a href="{{ route('student.index') }}" class="btn btn-vh"><i class="bi bi-search-heart me-1"></i> ติดตามสถานะ</a>
      <a href="{{ route('student.faq') }}" class="btn btn-light ms-1">คำถามที่พบบ่อย</a>
    </div>
  </div>
</div>
@endsection

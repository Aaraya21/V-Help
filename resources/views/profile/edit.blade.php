@extends('layouts.vhelp')

@section('content')
@php
  $roleTh = ['student'=>'นักศึกษา','staff'=>'เจ้าหน้าที่','supervisor'=>'หัวหน้างาน','admin'=>'ผู้ดูแลระบบ'][$user->role] ?? $user->role;
@endphp

<style>
  .vh-prof-hero{height:92px;border-radius:18px 18px 0 0;background:linear-gradient(120deg,#002a80,#003399 55%,#1f5bd6)}
  .vh-prof-avatar{width:92px;height:92px;border-radius:50%;margin:-46px auto 10px;background:linear-gradient(135deg,#FF5E00,#ff8a3d);border:5px solid #fff;color:#fff;font-size:2.2rem;font-weight:700;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(255,94,0,.3)}
  .vh-sec{display:flex;align-items:center;gap:10px;font-weight:700;margin-bottom:16px}
  .vh-sec i{width:34px;height:34px;border-radius:10px;background:#e8eefc;color:#003399;display:flex;align-items:center;justify-content:center}
</style>

<div class="mb-4">
  <h3 class="fw-bold mb-1">โปรไฟล์ของฉัน</h3>
  <div class="text-muted">จัดการข้อมูลส่วนตัวและรหัสผ่านของคุณ</div>
</div>

@if(session('status') === 'password-updated')
  <div class="alert alert-success">เปลี่ยนรหัสผ่านเรียบร้อยแล้ว</div>
@endif

<div class="row g-4">
  {{-- สรุปโปรไฟล์ --}}
  <div class="col-lg-4">
    <div class="vh-card text-center pb-4 overflow-hidden">
      <div class="vh-prof-hero"></div>
      <div class="vh-prof-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
      <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
      <span class="badge rounded-pill text-bg-primary px-3 py-2 mb-3">{{ $roleTh }}</span>
      <ul class="list-unstyled text-start px-4 mb-0 small">
        @if($user->role === 'student')
          <li class="mb-2"><i class="bi bi-person-badge-fill text-primary me-2"></i>{{ $user->student_code ?: 'ยังไม่ได้ระบุรหัสนักศึกษา' }}</li>
        @endif
        <li class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i>{{ $user->email }}</li>
        <li class="mb-2"><i class="bi bi-building-fill text-primary me-2"></i>{{ $user->department ?: 'ยังไม่ได้ระบุแผนก/สาขา' }}</li>
        <li><i class="bi bi-telephone-fill text-primary me-2"></i>{{ $user->phone ?: 'ยังไม่ได้ระบุเบอร์โทรศัพท์' }}</li>
      </ul>
    </div>
  </div>

  <div class="col-lg-8">
    {{-- ข้อมูลส่วนตัว --}}
    <div class="vh-card p-4 mb-4">
      <div class="vh-sec"><i class="bi bi-person-lines-fill"></i> ข้อมูลส่วนตัว</div>
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PATCH')
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">ชื่อ-นามสกุล</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">อีเมล</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
          </div>

          @if($user->role === 'student')
            <div class="col-md-6">
              <label class="form-label fw-semibold">รหัสนักศึกษา</label>
              @if($user->student_code)
                <input value="{{ $user->student_code }}" class="form-control" readonly disabled>
                <div class="form-text">หากต้องการแก้ไขรหัสนักศึกษา กรุณาติดต่อเจ้าหน้าที่</div>
              @else
                <input name="student_code" value="{{ old('student_code') }}" class="form-control" placeholder="เช่น 66000001">
                <div class="form-text">กรอกได้ครั้งเดียว หลังบันทึกแล้วจะแก้ไขเองไม่ได้</div>
              @endif
            </div>
          @endif

          <div class="col-md-6">
            <label class="form-label fw-semibold">แผนก / สาขาวิชา</label>
            <input name="department" value="{{ old('department', $user->department) }}" class="form-control" placeholder="เช่น เทคโนโลยีสารสนเทศ">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">เบอร์โทรศัพท์</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="เช่น 0812345678">
          </div>
        </div>
        <button class="btn btn-vh mt-4"><i class="bi bi-check2-circle me-1"></i> บันทึกข้อมูล</button>
      </form>
    </div>

    {{-- เปลี่ยนรหัสผ่าน --}}
    <div class="vh-card p-4">
      <div class="vh-sec"><i class="bi bi-shield-lock-fill"></i> เปลี่ยนรหัสผ่าน</div>
      <form method="POST" action="{{ route('password.update') }}">
        @csrf @method('PUT')
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">รหัสผ่านปัจจุบัน</label>
            <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
            @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">รหัสผ่านใหม่</label>
            <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
            @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
          </div>
        </div>
        <button class="btn btn-outline-primary mt-4"><i class="bi bi-key-fill me-1"></i> เปลี่ยนรหัสผ่าน</button>
      </form>
    </div>
  </div>
</div>
@endsection

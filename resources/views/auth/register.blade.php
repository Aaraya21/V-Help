@extends('layouts.auth')
@section('title', 'สมัครสมาชิก')

@section('content')
<h3 class="fw-bold mb-1">สมัครสมาชิก</h3>
<p class="text-muted mb-4">สร้างบัญชีนักศึกษาเพื่อใช้งานระบบ V-Help</p>

<form method="POST" action="{{ route('register') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label fw-semibold">ชื่อ-นามสกุล</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
      <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name">
    </div>
    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold">อีเมล</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
      <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
    </div>
    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold">รหัสผ่าน</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
    </div>
    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-4">
    <label class="form-label fw-semibold">ยืนยันรหัสผ่าน</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
      <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
    </div>
  </div>

  <button class="btn btn-brand w-100"><i class="bi bi-person-plus-fill me-1"></i> สมัครสมาชิก</button>
</form>

<div class="text-center mt-4 text-muted">
  มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">เข้าสู่ระบบ</a>
</div>
@endsection

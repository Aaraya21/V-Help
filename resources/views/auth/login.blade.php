@extends('layouts.auth')
@section('title', 'เข้าสู่ระบบ')

@section('content')
<h3 class="fw-bold mb-1">เข้าสู่ระบบ</h3>
<p class="text-muted mb-4">ยินดีต้อนรับสู่ศูนย์บริการนักศึกษาออนไลน์</p>

@if(session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label fw-semibold">อีเมล</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
      <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" required autofocus autocomplete="username">
    </div>
    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold">รหัสผ่าน</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required autocomplete="current-password">
    </div>
    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label" for="remember">จดจำฉันไว้</label>
    </div>
    @if(Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="small text-decoration-none">ลืมรหัสผ่าน?</a>
    @endif
  </div>

  <button class="btn btn-brand w-100"><i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ</button>
</form>

@if(Route::has('register'))
  <div class="text-center mt-4 text-muted">
    ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">สมัครสมาชิก</a>
  </div>
@endif
@endsection

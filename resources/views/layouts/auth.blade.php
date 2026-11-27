<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'เข้าสู่ระบบ') | V-Help</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root{--blue:#003399;--blue-d:#002a80;--orange:#FF5E00;--bs-primary:#003399;--bs-primary-rgb:0,51,153;--bs-link-color:#003399;--bs-link-color-rgb:0,51,153;--bs-link-hover-color:#FF5E00}
    body{font-family:'Noto Sans Thai',sans-serif;min-height:100vh;background:#f5f7fc;color:#2b2f55}
    .auth-wrap{min-height:100vh}
    .auth-brand{position:relative;overflow:hidden;color:#fff;background:linear-gradient(150deg,#00205f 0%,#003399 55%,#1f5bd6 120%);display:flex;align-items:center;justify-content:center;padding:48px}
    .auth-brand::before{content:"";position:absolute;width:420px;height:420px;border-radius:50%;background:rgba(255,255,255,.07);right:-140px;top:-120px}
    .auth-brand::after{content:"";position:absolute;width:260px;height:260px;border-radius:50%;background:rgba(255,94,0,.9);left:-90px;bottom:-110px}
    .auth-brand .inner{position:relative;z-index:1;max-width:420px}
    .logo-ring{width:150px;height:150px;border-radius:50%;background:#fff;padding:6px;box-shadow:0 14px 34px rgba(0,0,0,.3),0 0 0 6px rgba(255,94,0,.9)}
    .logo-ring img{width:100%;height:100%;object-fit:contain;border-radius:50%}
    .auth-brand h1{font-weight:700;letter-spacing:1px}
    .auth-feature{display:flex;align-items:center;gap:12px;margin-bottom:12px;color:#e3ecff}
    .auth-feature i{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;color:#ffb27a}
    .auth-card{width:100%;max-width:430px}
    .form-control{border-radius:12px;padding:.7rem .9rem;border-color:#d9deef}
    .input-group-text{border-radius:12px 0 0 12px;background:#f1f4fc;border-color:#d9deef;color:#003399}
    .input-group .form-control{border-radius:0 12px 12px 0}
    .form-control:focus{border-color:var(--blue);box-shadow:0 0 0 .2rem rgba(0,51,153,.15)}
    .btn-brand{background:linear-gradient(90deg,#FF5E00,#ff8a3d);color:#fff;border:0;border-radius:12px;padding:.75rem;font-weight:600;box-shadow:0 8px 18px rgba(255,94,0,.3)}
    .btn-brand:hover{color:#fff;filter:brightness(1.05)}
    .form-check-input:checked{background-color:var(--blue);border-color:var(--blue)}
    a{color:var(--blue)} a:hover{color:var(--orange)}
  </style>
</head>
<body>
<div class="row g-0 auth-wrap">
  <div class="col-lg-6 d-none d-lg-flex auth-brand">
    <div class="inner text-center text-lg-start">
      <div class="logo-ring mb-4 mx-auto mx-lg-0"><img src="{{ asset('images/logo.png') }}" alt="ตราวิทยาลัย"></div>
      <h1 class="display-5 mb-1">V-Help</h1>
      <div class="fs-5 mb-1">ระบบศูนย์บริการนักศึกษาออนไลน์</div>
      <div class="mb-4 text-white-50">วิทยาลัยอาชีวศึกษาฉะเชิงเทรา</div>
      <div class="auth-feature"><i class="bi bi-file-earmark-text-fill"></i> ยื่นคำร้องออนไลน์ แนบเอกสารได้ทันที</div>
      <div class="auth-feature"><i class="bi bi-card-checklist"></i> ติดตามสถานะคำร้องได้ตลอดเวลา</div>
      <div class="auth-feature"><i class="bi bi-bell-fill"></i> แจ้งเตือนเมื่อเจ้าหน้าที่ดำเนินการ</div>
    </div>
  </div>

  <div class="col-lg-6 d-flex align-items-center justify-content-center p-4">
    <div class="auth-card">
      <div class="text-center d-lg-none mb-4">
        <div class="logo-ring mx-auto mb-3" style="width:100px;height:100px"><img src="{{ asset('images/logo.png') }}" alt="ตราวิทยาลัย"></div>
        <h3 class="fw-bold mb-0" style="color:#003399">V-Help</h3>
        <small class="text-muted">วิทยาลัยอาชีวศึกษาฉะเชิงเทรา</small>
      </div>
      @yield('content')
    </div>
  </div>
</div>
</body>
</html>

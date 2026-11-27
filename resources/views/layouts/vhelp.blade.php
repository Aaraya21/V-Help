<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>V-Help | ศูนย์บริการนักศึกษาออนไลน์</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root{--navy:#00205f;--primary:#003399;--primary2:#FF5E00;--bg:#f5f7fc;--bs-primary:#003399;--bs-primary-rgb:0,51,153;--bs-link-color:#003399;--bs-link-color-rgb:0,51,153;--bs-link-hover-color:#FF5E00;--bs-link-hover-color-rgb:255,94,0}
    body{font-family:'Noto Sans Thai',sans-serif;background:#f4f5fc;color:#2b2f55;min-height:100vh}
    .vh-sidebar{width:260px!important;flex:0 0 260px!important;min-height:100vh;background:linear-gradient(180deg,#00205f 0%,#003399 100%)!important;color:#fff;border:0!important}
    .vh-sidebar .brand{padding:28px 20px 18px;text-align:center}
    .vh-sidebar .brand .logo{width:86px;height:86px;margin:0 auto;border-radius:50%;background:#fff;padding:4px;box-shadow:0 8px 20px rgba(0,0,0,.25),0 0 0 4px rgba(255,94,0,.85)}
    .vh-sidebar .brand .logo img{width:100%;height:100%;object-fit:contain;border-radius:50%}
    .vh-sidebar .brand h5{margin:12px 0 2px;font-weight:700;letter-spacing:.5px}
    .vh-sidebar .brand small{color:#cfdcff;font-size:.74rem}
    .vh-menu{padding:8px 14px}
    .vh-menu a{display:flex;align-items:center;gap:12px;padding:11px 14px;margin-bottom:6px;border-radius:12px;color:#dbe6ff;text-decoration:none;font-weight:500}
    .vh-menu a:hover{background:rgba(255,255,255,.08);color:#fff}
    .vh-menu a.active{background:linear-gradient(90deg,#FF5E00,#ff8a3d);color:#fff;box-shadow:0 8px 18px rgba(255,94,0,.35)}
    .vh-user{margin-top:auto;padding:18px;border-top:1px solid rgba(255,255,255,.14);text-align:center;color:#fff}
    .vh-avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#FF5E00,#ff8a3d);border:3px solid rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;margin:0 auto 8px}
    .vh-user a{display:block;color:inherit;text-decoration:none;border-radius:14px;padding:8px}
    .vh-user a:hover{background:rgba(255,255,255,.08);color:inherit}
    .vh-main{flex:1;min-width:0}
    .vh-top{display:flex;align-items:center;justify-content:flex-end;gap:12px;padding:14px 24px}
    .vh-card,.card{background:#fff;border:1px solid #e8eaf6!important;border-radius:18px!important;box-shadow:0 4px 18px rgba(40,50,120,.07)!important}
    .vh-hero{border-radius:20px;padding:34px;color:#fff;background:linear-gradient(120deg,#002a80,#003399 55%,#1f5bd6);position:relative;overflow:hidden}
    .vh-hero .bi-person-workspace{position:absolute;right:30px;bottom:-14px;font-size:9rem;opacity:.22}
    .vh-hero h2{font-weight:700}
    .vh-quick{display:block;text-align:center;padding:22px 12px;border-radius:18px;text-decoration:none;color:#2b2f55;font-weight:600;border:1px solid rgba(0,0,0,.04);transition:.15s}
    .vh-quick:hover{transform:translateY(-3px);box-shadow:0 10px 24px rgba(60,70,140,.15);color:#2b2f55}
    .vh-quick i{font-size:2.2rem;display:block;margin-bottom:8px}
    .vh-recent{display:flex;align-items:center;gap:14px;padding:14px 4px;border-top:1px solid #eef0f6;text-decoration:none;color:inherit}
    .vh-recent .ico{width:48px;height:48px;border-radius:12px;background:#e8eefc;color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .btn-vh{background:linear-gradient(90deg,#FF5E00,#ff8a3d);color:#fff;border:0;border-radius:10px;padding:.5rem 1.1rem}
    .btn-primary{background:var(--primary);border-color:var(--primary)}
    .btn-primary:hover{background:#002a80;border-color:#002a80}
    .badge.text-bg-primary{background:var(--primary)!important}
    .btn-outline-primary{color:var(--primary);border-color:var(--primary)}
    .btn-outline-primary:hover{background:var(--primary);border-color:var(--primary);color:#fff}
    .table thead th{background:#f1f2fb!important;color:#4a4f7a;font-weight:600;border-bottom:0}
    .form-control:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 .2rem rgba(0,51,153,.15)}
    .btn-vh:hover{color:#fff;opacity:.92}
    @media (min-width:992px){.vh-sidebar{position:sticky!important;top:0;height:100vh!important;display:flex!important;flex-direction:column}}
  </style>
</head>
<body>
@php
  $u = auth()->user();
  $roleTh = ['student'=>'นักศึกษา','staff'=>'เจ้าหน้าที่','supervisor'=>'หัวหน้างาน','admin'=>'ผู้ดูแลระบบ'][$u->role] ?? $u->role;
  $unread = $u->role === 'student' ? $u->unreadNotifications : collect();
@endphp
<div class="d-flex">
  <aside class="offcanvas-lg offcanvas-start vh-sidebar" tabindex="-1" id="sidebar">
    <div class="brand">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="logo"></div>
      <h5>V-Help</h5>
      <small>วิทยาลัยอาชีวศึกษาฉะเชิงเทรา</small>
    </div>
    <nav class="vh-menu">
      @if($u->role === 'student')
        <a href="{{ route('student.home') }}" class="{{ request()->routeIs('student.home') ? 'active' : '' }}"><i class="bi bi-house-door-fill"></i> หน้าหลัก</a>
        <a href="{{ route('student.create') }}" class="{{ request()->routeIs('student.create') ? 'active' : '' }}"><i class="bi bi-clipboard-plus"></i> ยื่นคำร้อง</a>
        <a href="{{ route('student.index') }}" class="{{ request()->routeIs('student.index','student.show') ? 'active' : '' }}"><i class="bi bi-card-checklist"></i> ติดตามสถานะ</a>
        <a href="{{ route('student.faq') }}" class="{{ request()->routeIs('student.faq') ? 'active' : '' }}"><i class="bi bi-question-circle"></i> คำถามที่พบบ่อย</a>
        <a href="{{ route('student.contact') }}" class="{{ request()->routeIs('student.contact') ? 'active' : '' }}"><i class="bi bi-envelope"></i> ติดต่อเรา</a>
      @else
        <a href="{{ route('staff.index') }}" class="{{ request()->routeIs('staff.index','staff.show') ? 'active' : '' }}"><i class="bi bi-inbox-fill"></i> จัดการคำร้อง</a>
        <a href="{{ route('staff.report') }}" class="{{ request()->routeIs('staff.report') ? 'active' : '' }}"><i class="bi bi-bar-chart-fill"></i> รายงานสถิติ</a>
      @endif
      <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="bi bi-person-circle"></i> โปรไฟล์ของฉัน</a>
    </nav>
    <div class="vh-user">
      <a href="{{ route('profile.edit') }}" title="แก้ไขโปรไฟล์">
        <div class="vh-avatar">{{ mb_substr($u->name, 0, 1) }}</div>
        <div class="fw-bold">{{ $u->name }}</div>
        <small class="text-white-50">{{ $roleTh }}</small>
        <div class="mt-2"><span class="badge rounded-pill bg-light text-dark fw-medium"><i class="bi bi-pencil-fill me-1"></i>แก้ไขโปรไฟล์</span></div>
      </a>
    </div>
  </aside>

  <div class="vh-main">
    <header class="vh-top">
      <button class="btn btn-light d-lg-none me-auto" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>

      @if($u->role === 'student')
      <div class="dropdown">
        <button class="btn btn-light position-relative rounded-circle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
          <i class="bi bi-bell"></i>
          @if($unread->count())
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread->count() }}</span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:300px">
          <div class="fw-bold mb-2">การแจ้งเตือน</div>
          @forelse($unread as $n)
            <div class="small border-bottom py-2">{{ $n->data['message'] ?? '' }}</div>
          @empty
            <div class="small text-muted">ไม่มีการแจ้งเตือนใหม่</div>
          @endforelse
          @if($unread->count())
            <form method="POST" action="{{ route('student.notifications.read') }}" class="mt-2">
              @csrf
              <button class="btn btn-sm btn-outline-primary w-100">ทำเครื่องหมายว่าอ่านแล้ว</button>
            </form>
          @endif
        </div>
      </div>
      @endif

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light" title="ออกจากระบบ"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</button>
      </form>
    </header>

    <main class="px-3 px-lg-4 pb-4">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul></div>
      @endif
      @yield('content')
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

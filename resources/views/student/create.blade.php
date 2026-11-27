@extends('layouts.vhelp')

@section('content')
@php
  $icons = [
    'cert' => 'bi-patch-check-fill', 'transcript' => 'bi-journal-text', 'edit_info' => 'bi-pencil-square',
    'scholar' => 'bi-award-fill', 'tuition' => 'bi-cash-coin', 'counsel' => 'bi-chat-heart-fill',
    'repair' => 'bi-tools', 'lab' => 'bi-pc-display',
    'net_reset' => 'bi-wifi', 'email_help' => 'bi-envelope-exclamation-fill',
  ];
  $current = old('request_type_id');
@endphp

<style>
  .vh-step{display:flex;align-items:center;gap:10px;font-weight:700;margin-bottom:14px}
  .vh-step .n{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#FF5E00,#ff8a3d);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.9rem}
  .vh-type{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:6px;height:100%;min-height:98px;padding:12px 8px;border:2px solid #e6e8f6;border-radius:14px;background:#fff;cursor:pointer;font-size:.88rem;font-weight:500;transition:.15s;color:#3b3f6a}
  .vh-type i{font-size:1.6rem;color:#4d78c9}
  .vh-type:hover{border-color:#9db6ea;background:#f4f7fe}
  .btn-check:checked + .vh-type{border-color:#003399;background:#e8eefc;box-shadow:0 6px 16px rgba(0,51,153,.18);color:#002a80}
  .btn-check:checked + .vh-type i{color:#003399}
  .vh-extra{background:#fff5ee;border:1px dashed #ffc9a6;border-radius:14px;padding:16px}
  .vh-side li{margin-bottom:10px}
</style>

<div class="mb-3">
  <h3 class="fw-bold mb-1">ยื่นคำร้องใหม่</h3>
  <div class="text-muted">กรอกข้อมูลให้ครบถ้วน เจ้าหน้าที่จะตรวจสอบและแจ้งผลให้ทราบผ่านระบบ</div>
</div>

<form method="POST" action="{{ route('student.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-4">
  <div class="col-lg-8">
    <div class="vh-card p-4">

      {{-- 1. ประเภทคำร้อง --}}
      <div class="vh-step"><span class="n">1</span> เลือกประเภทคำร้อง</div>
      <div class="row g-2 mb-4">
        @foreach($types as $t)
          <div class="col-6 col-md-3">
            <input type="radio" class="btn-check" name="request_type_id" id="type{{ $t->id }}"
                   value="{{ $t->id }}" data-code="{{ $t->code }}" required
                   @checked($current ? $current == $t->id : ($selected ?? null) === $t->code)>
            <label class="vh-type" for="type{{ $t->id }}">
              <i class="bi {{ $icons[$t->code] ?? 'bi-file-earmark-text' }}"></i>
              <span>{{ $t->name }}</span>
            </label>
          </div>
        @endforeach
      </div>

      {{-- 2. รายละเอียด --}}
      <div class="vh-step"><span class="n">2</span> กรอกรายละเอียด</div>

      <div class="mb-3">
        <label class="form-label fw-semibold">หัวข้อคำร้อง <span class="text-danger">*</span></label>
        <input name="subject" value="{{ old('subject') }}" class="form-control form-control-lg" placeholder="เช่น ขอหนังสือรับรองเพื่อสมัครงาน" required>
      </div>

      <div class="extra d-none mb-3 vh-extra" data-for="cert">
        <label class="form-label fw-semibold">วัตถุประสงค์การขอ</label>
        <input name="extra[purpose]" class="form-control" placeholder="เช่น ใช้สมัครงาน / ยื่นกู้ กยศ.">
      </div>
      <div class="extra d-none mb-3 vh-extra" data-for="transcript">
        <label class="form-label fw-semibold">วัตถุประสงค์การขอ</label>
        <input name="extra[purpose]" class="form-control" placeholder="เช่น ศึกษาต่อ">
      </div>
      <div class="extra d-none mb-3 vh-extra" data-for="lab">
        <label class="form-label fw-semibold">ข้อมูลการใช้ห้องปฏิบัติการ</label>
        <input name="extra[room]" class="form-control mb-2" placeholder="ชื่อห้องปฏิบัติการ">
        <div class="row g-2">
          <div class="col-md-6"><input type="date" name="extra[date]" class="form-control"></div>
          <div class="col-md-6"><input name="extra[time]" class="form-control" placeholder="เวลา เช่น 13:00-16:00"></div>
        </div>
      </div>
      <div class="extra d-none mb-3 vh-extra" data-for="repair">
        <label class="form-label fw-semibold">สถานที่ / อุปกรณ์ที่ชำรุด</label>
        <input name="extra[location]" class="form-control" placeholder="เช่น ห้อง 3201 โปรเจกเตอร์เสีย">
      </div>
      <div class="extra d-none mb-3 vh-extra" data-for="tuition">
        <label class="form-label fw-semibold">ขอผ่อนผันถึงวันที่</label>
        <input type="date" name="extra[until]" class="form-control">
      </div>

      <div class="extra d-none mb-3 vh-extra" data-for="net_reset">
        <label class="form-label fw-semibold">ข้อมูลบัญชีอินเทอร์เน็ต</label>
        <input name="extra[net_user]" class="form-control mb-2" placeholder="ชื่อผู้ใช้ (Username) ที่ใช้เข้าอินเทอร์เน็ตของวิทยาลัย">
        <input name="extra[device]" class="form-control mb-2" placeholder="อุปกรณ์ / สถานที่ที่ใช้งาน เช่น Wi-Fi อาคาร 3">
        <input name="extra[contact_phone]" class="form-control" placeholder="เบอร์โทรติดต่อกลับ">
        <div class="form-text text-danger mt-2"><i class="bi bi-shield-exclamation"></i> ห้ามกรอกรหัสผ่านลงในระบบ เจ้าหน้าที่จะตรวจสอบตัวตนก่อนดำเนินการรีเซ็ต</div>
      </div>
      <div class="extra d-none mb-3 vh-extra" data-for="email_help">
        <label class="form-label fw-semibold">ข้อมูลอีเมลวิทยาลัย</label>
        <input type="email" name="extra[college_email]" class="form-control mb-2" placeholder="อีเมลวิทยาลัยที่มีปัญหา">
        <select name="extra[problem]" class="form-select mb-2">
          <option value="">-- เลือกปัญหาที่พบ --</option>
          <option>ลืมรหัสผ่าน</option>
          <option>บัญชีถูกบล็อก / ล็อก</option>
          <option>เข้าสู่ระบบไม่ได้</option>
          <option>อื่น ๆ</option>
        </select>
        <input name="extra[contact_phone]" class="form-control" placeholder="เบอร์โทรติดต่อกลับ">
        <div class="form-text mt-2">กรณีเร่งด่วน ติดต่อผู้ดูแลระบบได้ที่เมนู "ติดต่อเรา"</div>
        <div class="form-text text-danger"><i class="bi bi-shield-exclamation"></i> ห้ามกรอกรหัสผ่านลงในระบบ เจ้าหน้าที่จะตรวจสอบตัวตนก่อนดำเนินการ</div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">รายละเอียดเพิ่มเติม</label>
        <textarea name="detail" rows="4" class="form-control" placeholder="อธิบายเหตุผลหรือรายละเอียดที่ต้องการแจ้งเจ้าหน้าที่">{{ old('detail') }}</textarea>
      </div>

      {{-- 3. เอกสาร --}}
      <div class="vh-step"><span class="n">3</span> แนบเอกสาร <small class="text-muted fw-normal">(ถ้ามี)</small></div>
      <input type="file" name="attachment" class="form-control mb-1" accept=".pdf,.jpg,.jpeg,.png">
      <div class="form-text mb-4">รองรับ PDF, JPG, PNG ขนาดไม่เกิน 5 MB</div>

      <div class="d-flex gap-2">
        <button class="btn btn-vh btn-lg px-4"><i class="bi bi-send-fill me-1"></i> ส่งคำร้อง</button>
        <a href="{{ route('student.home') }}" class="btn btn-light btn-lg">ยกเลิก</a>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="vh-card p-4 vh-side">
      <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb-fill text-warning me-1"></i> ขั้นตอนการดำเนินการ</h6>
      <ul class="list-unstyled small mb-0">
        <li><i class="bi bi-1-circle-fill text-primary me-2"></i>ยื่นคำร้องผ่านระบบ</li>
        <li><i class="bi bi-2-circle-fill text-primary me-2"></i>เจ้าหน้าที่ตรวจสอบข้อมูล</li>
        <li><i class="bi bi-3-circle-fill text-primary me-2"></i>อนุมัติ / ดำเนินการ (ถ้าจำเป็น)</li>
        <li><i class="bi bi-4-circle-fill text-primary me-2"></i>แจ้งผลผ่านการแจ้งเตือนในระบบ</li>
      </ul>
    </div>
    <div class="vh-card p-4 mt-3" style="background:#e8eefc!important">
      <h6 class="fw-bold mb-2">ติดตามสถานะได้ตลอด</h6>
      <p class="small text-muted mb-3">หลังส่งคำร้อง จะได้เลขที่คำร้องสำหรับตรวจสอบสถานะที่เมนู "ติดตามสถานะ"</p>
      <a href="{{ route('student.index') }}" class="btn btn-sm btn-outline-primary">ดูคำร้องของฉัน</a>
    </div>
  </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const radios = document.querySelectorAll('input[name="request_type_id"]');
function toggleExtra() {
  const checked = document.querySelector('input[name="request_type_id"]:checked');
  const code = checked ? checked.dataset.code : null;
  document.querySelectorAll('.extra').forEach(el => {
    const show = el.dataset.for === code;
    el.classList.toggle('d-none', !show);
    el.querySelectorAll('input,select,textarea').forEach(i => i.disabled = !show);
  });
}
radios.forEach(r => r.addEventListener('change', toggleExtra));
toggleExtra();
</script>
@endpush

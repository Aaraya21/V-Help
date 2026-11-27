@extends('layouts.vhelp')

@section('content')
{{-- แก้ไขคำถาม-คำตอบให้ตรงกับระเบียบของวิทยาลัยได้ที่ไฟล์นี้ --}}
@php
$faqs = [
  ['ยื่นคำร้องแล้วต้องรอนานแค่ไหน?', 'ระยะเวลาขึ้นอยู่กับประเภทคำร้องและขั้นตอนของวิทยาลัย สามารถติดตามสถานะได้ที่เมนู "ติดตามสถานะ" และจะมีการแจ้งเตือนเมื่อสถานะเปลี่ยน'],
  ['แนบเอกสารได้แบบไหน?', 'รองรับไฟล์ PDF, JPG และ PNG ขนาดไม่เกิน 5 MB ต่อคำร้อง'],
  ['ต้องแนบเอกสารทุกคำร้องหรือไม่?', 'ไม่จำเป็นสำหรับทุกคำร้อง ขึ้นอยู่กับประเภทคำร้อง หากต้องใช้เอกสารเพิ่ม เจ้าหน้าที่จะแจ้งไว้ในหมายเหตุของคำร้อง'],
  ['ยื่นผิดประเภทหรือกรอกข้อมูลผิดทำอย่างไร?', 'ติดต่อเจ้าหน้าที่ตามช่องทางในหน้า "ติดต่อเรา" พร้อมแจ้งเลขที่คำร้อง'],
  ['สถานะ "ไม่อนุมัติ" หมายความว่าอะไร?', 'เจ้าหน้าที่ไม่สามารถดำเนินการตามคำร้องได้ ให้ดูหมายเหตุจากเจ้าหน้าที่ในหน้ารายละเอียดคำร้อง'],
  ['คำร้องประเภทใดต้องรอการอนุมัติ?', 'คำร้องแก้ไขข้อมูล ขอทุน ขอผ่อนผันค่าเล่าเรียน และขอใช้ห้องปฏิบัติการ ต้องผ่านการอนุมัติก่อนดำเนินการ'],
  ['ลืมรหัสผ่านอินเทอร์เน็ตหรืออีเมลวิทยาลัยทำอย่างไร?', 'ยื่นคำร้อง "ขอ Reset รหัสผ่านเข้าใช้งานอินเทอร์เน็ต" หรือ "แก้ปัญหาการเข้าใช้งาน E-mail ของวิทยาลัย" เจ้าหน้าที่จะตรวจสอบตัวตนก่อนดำเนินการ และกรุณาอย่าระบุรหัสผ่านของคุณในคำร้อง'],
];
@endphp

<style>
  .vh-faq .accordion-item{border:1px solid #e8eaf6;border-radius:14px!important;margin-bottom:12px;overflow:hidden;background:#fff;box-shadow:0 3px 12px rgba(40,50,120,.05)}
  .vh-faq .accordion-button{font-weight:600;color:#2b2f55;background:#fff;padding:18px 20px}
  .vh-faq .accordion-button:not(.collapsed){background:#e8eefc;color:#002a80;box-shadow:none}
  .vh-faq .accordion-button:focus{box-shadow:none;border-color:transparent}
  .vh-faq .accordion-button .q{width:30px;height:30px;border-radius:50%;background:#e8eefc;color:#003399;display:inline-flex;align-items:center;justify-content:center;margin-right:12px;font-size:.95rem;flex-shrink:0}
  .vh-faq .accordion-body{color:#4a4f7a;padding:6px 22px 20px 62px}
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <div>
    <h3 class="fw-bold mb-1">คำถามที่พบบ่อย</h3>
    <div class="text-muted">คำตอบสำหรับเรื่องที่นักศึกษาสอบถามบ่อย</div>
  </div>
  <div class="position-relative" style="min-width:280px">
    <i class="bi bi-search position-absolute" style="left:14px;top:11px;color:#8b90b8"></i>
    <input id="faqSearch" class="form-control ps-5 rounded-pill" placeholder="ค้นหาคำถาม...">
  </div>
</div>

<div class="accordion vh-faq" id="faq">
  @foreach($faqs as $i => [$q, $a])
  <div class="accordion-item faq-item">
    <h2 class="accordion-header">
      <button class="accordion-button {{ $i ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#f{{ $i }}">
        <span class="q"><i class="bi bi-question-lg"></i></span>{{ $q }}
      </button>
    </h2>
    <div id="f{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faq">
      <div class="accordion-body">{{ $a }}</div>
    </div>
  </div>
  @endforeach
</div>
<div id="faqEmpty" class="text-center text-muted py-4 d-none">
  <i class="bi bi-emoji-neutral fs-1"></i><div>ไม่พบคำถามที่ตรงกับคำค้น</div>
</div>

<div class="vh-card p-4 mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background:#e8eefc!important">
  <div>
    <div class="fw-bold">ยังไม่พบคำตอบที่ต้องการ?</div>
    <small class="text-muted">ติดต่อเจ้าหน้าที่ศูนย์บริการนักศึกษาได้โดยตรง</small>
  </div>
  <a href="{{ route('student.contact') }}" class="btn btn-vh"><i class="bi bi-headset me-1"></i> ติดต่อเรา</a>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('faqSearch').addEventListener('input', function () {
  const kw = this.value.trim().toLowerCase();
  let shown = 0;
  document.querySelectorAll('.faq-item').forEach(el => {
    const ok = !kw || el.textContent.toLowerCase().includes(kw);
    el.classList.toggle('d-none', !ok);
    if (ok) shown++;
  });
  document.getElementById('faqEmpty').classList.toggle('d-none', shown > 0);
});
</script>
@endpush

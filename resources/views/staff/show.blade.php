@extends('layouts.vhelp')
@section('content')
<a href="{{ route('staff.index') }}" class="btn btn-sm btn-light mb-3">← กลับ</a>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="card shadow-sm mb-3"><div class="card-body">
      <div class="d-flex justify-content-between">
        <h5>{{ $sr->ticket_no }}</h5>
        <span class="badge text-bg-{{ $sr->status_color }} align-self-start">{{ $sr->status_label }}</span>
      </div>
      <p class="text-muted mb-1">{{ $sr->type->name }}
        @if($sr->type->need_approval)<span class="badge text-bg-warning">ต้องอนุมัติ</span>@endif
      </p>
      <p class="mb-2">ผู้ยื่น: <strong>{{ $sr->user->name }}</strong> ({{ $sr->user->student_code ?? '-' }})</p>
      <h6>{{ $sr->subject }}</h6>
      <p>{!! nl2br(e($sr->detail)) !!}</p>
      @if($sr->extra)
        <ul class="list-unstyled">
          @foreach($sr->extra as $k => $v)
            <li><strong>{{ \App\Models\ServiceRequest::EXTRA_LABELS[$k] ?? $k }}:</strong> {{ $v }}</li>
          @endforeach
        </ul>
      @endif
      @if($sr->attachment)
        <a href="{{ asset('storage/'.$sr->attachment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">📎 ไฟล์แนบ</a>
      @endif
    </div></div>

    <div class="card shadow-sm"><div class="card-body">
      <h6>อัปเดตสถานะ</h6>
      <form method="POST" action="{{ route('staff.status', $sr) }}">
        @csrf @method('PATCH')
        <div class="mb-2">
          <select name="status" class="form-select">
            @foreach(\App\Models\ServiceRequest::STATUS as $k => $v)
              <option value="{{ $k }}" @selected($sr->status === $k)>{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-2">
          <textarea name="note" rows="3" class="form-control" placeholder="หมายเหตุถึงนักศึกษา (ถ้ามี)">{{ $sr->staff_note }}</textarea>
        </div>
        <button class="btn btn-primary">บันทึก</button>
      </form>
    </div></div>
  </div>

  <div class="col-lg-5">
    <div class="card shadow-sm"><div class="card-body">
      <h6>ประวัติการดำเนินการ</h6>
      @foreach($sr->logs as $log)
        <div class="border-start border-3 border-{{ \App\Models\ServiceRequest::COLORS[$log->status] ?? 'secondary' }} ps-3 mb-3">
          <div class="fw-semibold">{{ \App\Models\ServiceRequest::STATUS[$log->status] ?? $log->status }}</div>
          <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }} · {{ $log->user->name }}</small>
          @if($log->note)<div>{{ $log->note }}</div>@endif
        </div>
      @endforeach
    </div></div>
  </div>
</div>
@endsection

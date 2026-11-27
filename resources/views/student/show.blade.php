@extends('layouts.vhelp')
@section('content')
<a href="{{ route('student.index') }}" class="btn btn-sm btn-light mb-3">← กลับ</a>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="card shadow-sm"><div class="card-body">
      <div class="d-flex justify-content-between">
        <h5>{{ $sr->ticket_no }}</h5>
        <span class="badge text-bg-{{ $sr->status_color }} align-self-start">{{ $sr->status_label }}</span>
      </div>
      <p class="text-muted mb-2">{{ $sr->type->name }}</p>
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
      @if($sr->staff_note)
        <div class="alert alert-warning mt-3 mb-0"><strong>หมายเหตุจากเจ้าหน้าที่:</strong> {{ $sr->staff_note }}</div>
      @endif
    </div></div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm"><div class="card-body">
      <h6>ติดตามสถานะ</h6>
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

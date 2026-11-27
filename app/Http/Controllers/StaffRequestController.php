<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\RequestType;
use App\Models\ServiceRequest;
use App\Notifications\RequestStatusChanged;
use Illuminate\Http\Request;

class StaffRequestController extends Controller
{
    public function index(Request $request)
    {
        $counts = ServiceRequest::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $q = ServiceRequest::with('type', 'user')->latest();

        if ($request->filled('status')) $q->where('status', $request->status);
        if ($request->filled('type'))   $q->where('request_type_id', $request->type);
        if ($request->filled('q')) {
            $kw = $request->q;
            $q->where(fn ($w) => $w->where('ticket_no', 'like', "%$kw%")
                ->orWhere('subject', 'like', "%$kw%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$kw%")->orWhere('student_code', 'like', "%$kw%")));
        }

        return view('staff.index', [
            'requests' => $q->paginate(15)->withQueryString(),
            'types'    => RequestType::all(),
            'counts'   => $counts,
        ]);
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load('type', 'user', 'logs.user');
        return view('staff.show', ['sr' => $serviceRequest]);
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(ServiceRequest::STATUS)),
            'note'   => 'nullable|string',
        ]);

        $serviceRequest->update([
            'status'      => $data['status'],
            'staff_note'  => $data['note'],
            'assigned_to' => auth()->id(),
        ]);

        RequestLog::create([
            'service_request_id' => $serviceRequest->id,
            'user_id' => auth()->id(),
            'status'  => $data['status'],
            'note'    => $data['note'],
        ]);

        $serviceRequest->user->notify(new RequestStatusChanged($serviceRequest));

        return back()->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }

    public function report()
    {
        // สถานะ: นับให้ครบทุกสถานะ (ถ้าไม่มีให้เป็น 0)
        $raw = ServiceRequest::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $byStatus = collect(ServiceRequest::STATUS)->map(fn ($label, $key) => (int) ($raw[$key] ?? 0));
        $total = $byStatus->sum();

        $thisMonth = ServiceRequest::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)->count();

        // ประเภทคำร้อง: แสดงครบทุกประเภท เรียงจากมากไปน้อย
        $byType = RequestType::leftJoin('service_requests', 'service_requests.request_type_id', '=', 'request_types.id')
            ->selectRaw('request_types.name as name, COUNT(service_requests.id) as total')
            ->groupBy('request_types.id', 'request_types.name')
            ->orderByDesc('total')->orderBy('request_types.id')
            ->pluck('total', 'name');

        // รายเดือน: 6 เดือนล่าสุด (เดือนที่ไม่มีคำร้องแสดงเป็น 0)
        $thMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $perMonth = ServiceRequest::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->groupBy('ym')->pluck('total', 'ym');

        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->startOfMonth()->subMonths($i);
            $monthly[] = [
                'label' => $thMonths[$d->month] . ' ' . substr((string) ($d->year + 543), -2),
                'total' => (int) ($perMonth[$d->format('Y-m')] ?? 0),
            ];
        }

        return view('staff.report', compact('byStatus', 'total', 'thisMonth', 'byType', 'monthly'));
    }
}

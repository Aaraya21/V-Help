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
        $byStatus = ServiceRequest::selectRaw('status, COUNT(*) total')->groupBy('status')->pluck('total', 'status');
        $byType = ServiceRequest::join('request_types', 'request_types.id', '=', 'service_requests.request_type_id')
            ->selectRaw('request_types.name as name, COUNT(*) total')->groupBy('request_types.name')->pluck('total', 'name');
        $byMonth = ServiceRequest::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) total')
            ->groupBy('ym')->orderBy('ym')->pluck('total', 'ym');

        $labels = ServiceRequest::STATUS;
        return view('staff.report', compact('byStatus', 'byType', 'byMonth', 'labels'));
    }
}

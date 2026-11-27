<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\RequestType;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class StudentRequestController extends Controller
{
    public function home()
    {
        $recent = ServiceRequest::with('type')
            ->where('user_id', auth()->id())->latest()->take(5)->get();

        return view('student.home', compact('recent'));
    }

    public function faq()     { return view('student.faq'); }
    public function contact() { return view('student.contact'); }

    public function markRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function index(Request $request)
    {
        $counts = ServiceRequest::where('user_id', auth()->id())
            ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $q = ServiceRequest::with('type')->where('user_id', auth()->id())->latest();
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        $requests = $q->paginate(10)->withQueryString();

        return view('student.index', compact('requests', 'counts'));
    }

    public function create(Request $request)
    {
        $types = RequestType::where('is_active', true)->get();
        $selected = $request->query('type'); // เช่น ?type=counsel
        return view('student.create', compact('types', 'selected'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'subject'         => 'required|string|max:255',
            'detail'          => 'nullable|string',
            'extra'           => 'nullable|array',
            'extra.*'         => 'nullable|string|max:255',
            'attachment'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
        if (!empty($data['extra'])) {
            $data['extra'] = array_filter($data['extra'], fn ($v) => $v !== null && $v !== '');
        }

        $data['user_id']   = auth()->id();
        $data['ticket_no'] = 'TMP-' . uniqid();
        $sr = ServiceRequest::create($data);

        $sr->update([
            'ticket_no' => 'VH-' . (date('Y') + 543) . '-' . str_pad($sr->id, 4, '0', STR_PAD_LEFT),
        ]);

        RequestLog::create([
            'service_request_id' => $sr->id,
            'user_id' => auth()->id(),
            'status'  => 'pending',
            'note'    => 'ยื่นคำร้อง',
        ]);

        return redirect()->route('student.show', $sr)->with('success', 'ยื่นคำร้องเรียบร้อยแล้ว');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->user_id === auth()->id(), 403);
        $serviceRequest->load('type', 'logs.user');
        return view('student.show', ['sr' => $serviceRequest]);
    }
}

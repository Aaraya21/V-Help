<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'ticket_no', 'user_id', 'request_type_id', 'assigned_to',
        'subject', 'detail', 'extra', 'attachment', 'status', 'staff_note',
    ];

    protected $casts = ['extra' => 'array'];

    public const STATUS = [
        'pending'    => 'รอดำเนินการ',
        'reviewing'  => 'กำลังตรวจสอบ',
        'approved'   => 'อนุมัติแล้ว',
        'processing' => 'กำลังดำเนินการ',
        'completed'  => 'เสร็จสิ้น',
        'rejected'   => 'ไม่อนุมัติ',
    ];

    public const COLORS = [
        'pending' => 'secondary', 'reviewing' => 'info', 'approved' => 'primary',
        'processing' => 'warning', 'completed' => 'success', 'rejected' => 'danger',
    ];

    public const EXTRA_LABELS = [
        'purpose' => 'วัตถุประสงค์', 'room' => 'ห้องปฏิบัติการ', 'date' => 'วันที่',
        'time' => 'เวลา', 'location' => 'สถานที่/อุปกรณ์', 'until' => 'ขอผ่อนผันถึงวันที่',
        'net_user' => 'ชื่อผู้ใช้อินเทอร์เน็ต', 'device' => 'อุปกรณ์/สถานที่ใช้งาน', 'contact_phone' => 'เบอร์ติดต่อกลับ',
        'college_email' => 'อีเมลวิทยาลัย', 'problem' => 'ปัญหาที่พบ',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function type()    { return $this->belongsTo(RequestType::class, 'request_type_id'); }
    public function logs()    { return $this->hasMany(RequestLog::class)->latest(); }

    public function getStatusLabelAttribute() { return self::STATUS[$this->status] ?? $this->status; }
    public function getStatusColorAttribute() { return self::COLORS[$this->status] ?? 'secondary'; }

    public function getThaiDateAttribute()
    {
        $months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $d = $this->created_at;
        return $d->day.' '.$months[$d->month].' '.($d->year + 543).' เวลา '.$d->format('H:i').' น.';
    }
}

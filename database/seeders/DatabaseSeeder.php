<?php

namespace Database\Seeders;

use App\Models\RequestType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['cert',       'ขอหนังสือรับรองการเป็นนักศึกษา', false],
            ['transcript', 'ขอใบรับรองผลการเรียน',           false],
            ['edit_info',  'ขอแก้ไขข้อมูลนักศึกษา',          true],
            ['scholar',    'ขอทุนการศึกษา',                  true],
            ['tuition',    'ขอผ่อนผันค่าเล่าเรียน',          true],
            ['counsel',    'ขอคำปรึกษาด้านการเรียน',         false],
            ['repair',     'แจ้งซ่อมอุปกรณ์หรือห้องเรียน',   false],
            ['lab',        'ขอใช้ห้องปฏิบัติการ',            true],
        ];
        foreach ($types as [$code, $name, $approval]) {
            RequestType::updateOrCreate(['code' => $code], ['name' => $name, 'need_approval' => $approval]);
        }

        // บัญชีทดสอบ (รหัสผ่านทุกบัญชี: password)
        $users = [
            ['admin@vhelp.test',   'ผู้ดูแลระบบ',     'admin',      null],
            ['super@vhelp.test',   'หัวหน้างานทดสอบ', 'supervisor', null],
            ['staff@vhelp.test',   'เจ้าหน้าที่ทดสอบ', 'staff',      null],
            ['student@vhelp.test', 'นักศึกษาทดสอบ',   'student',    '66000001'],
        ];
        foreach ($users as [$email, $name, $role, $code]) {
            User::updateOrCreate(['email' => $email], [
                'name' => $name, 'password' => 'password', 'role' => $role, 'student_code' => $code,
            ]);
        }
    }
}

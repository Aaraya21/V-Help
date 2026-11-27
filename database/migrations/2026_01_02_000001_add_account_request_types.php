<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $types = [
            ['net_reset',  'ขอ Reset รหัสผ่านเข้าใช้งานอินเทอร์เน็ต',        false],
            ['email_help', 'แก้ปัญหาการเข้าใช้งาน E-mail ของวิทยาลัย',        false],
        ];

        foreach ($types as [$code, $name, $approval]) {
            DB::table('request_types')->updateOrInsert(
                ['code' => $code],
                ['name' => $name, 'need_approval' => $approval, 'is_active' => true,
                 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('request_types')->whereIn('code', ['net_reset', 'email_help'])->delete();
    }
};

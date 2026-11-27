<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->enum('role', ['student', 'staff', 'supervisor', 'admin'])->default('student');
            $t->string('student_code')->nullable()->unique();
            $t->string('department')->nullable();
            $t->string('phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['role', 'student_code', 'department', 'phone']);
        });
    }
};

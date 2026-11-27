<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $t) {
            $t->id();
            $t->string('ticket_no')->unique();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('request_type_id')->constrained();
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->string('subject');
            $t->text('detail')->nullable();
            $t->json('extra')->nullable();
            $t->string('attachment')->nullable();
            $t->enum('status', ['pending', 'reviewing', 'approved', 'processing', 'completed', 'rejected'])->default('pending');
            $t->text('staff_note')->nullable();
            $t->timestamps();
        });

        Schema::create('request_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained();
            $t->string('status');
            $t->text('note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_logs');
        Schema::dropIfExists('service_requests');
    }
};

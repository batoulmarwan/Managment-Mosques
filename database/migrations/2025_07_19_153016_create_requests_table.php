<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->enum('type', ['صيانة', 'تعيين', 'مواد', 'مالية', 'أخرى']);
            $table->text('description');
            $table->enum('status', ['قيد المعالجة', 'مقبول', 'مرفوض'])->default('قيد المعالجة');
            $table->enum('progress', ['جاري التنفيذ', 'تم التنفيذ', 'ملغي'])->default('جاري التنفيذ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

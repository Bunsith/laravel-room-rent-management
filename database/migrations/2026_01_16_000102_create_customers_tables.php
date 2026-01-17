<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->unsignedInteger('member_count')->default(1);
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->text('note')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('national_id')->nullable();
            $table->date('national_valid_until')->nullable();
            $table->string('passport_id')->nullable();
            $table->date('passport_valid_until')->nullable();
            $table->string('visa_id')->nullable();
            $table->date('visa_valid_until')->nullable();
            $table->json('attachment_file')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_phones');
        Schema::dropIfExists('customer_documents');
        Schema::dropIfExists('customers');
    }
};

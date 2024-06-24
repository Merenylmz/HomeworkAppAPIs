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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger("from");
            $table->foreign("from")->references("id")->on("users");
            $table->unsignedBigInteger("toUser");
            $table->foreign("toUser")->references("id")->on("users")->onDelete("cascade");
            $table->boolean("isRead")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

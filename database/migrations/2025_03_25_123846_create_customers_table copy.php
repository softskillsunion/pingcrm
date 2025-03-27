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
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('user_id')->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 50);
            $table->string('phone')->nullable();   // 室內電話
            $table->string('mobile')->nullable();  // 行動電話
            $table->string('mailing_address')->nullable(); // 通訊地址
            $table->string('registered_address')->nullable(); // 戶籍地址
            $table->string('photo_path', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

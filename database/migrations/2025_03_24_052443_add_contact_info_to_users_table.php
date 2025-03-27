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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(4)->after('email'); // 分潤成數
            $table->string('phone')->nullable()->after('commission_rate');   // 一般電話
            $table->string('mobile')->nullable()->after('phone');  // 行動電話
            $table->string('mailing_address')->nullable()->after('mobile'); // 通訊地址
            $table->string('registered_address')->nullable()->after('mailing_address'); // 戶籍地址
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'phone', 'mobile', 'mailing_address', 'registered_address']);
        });
    }
};

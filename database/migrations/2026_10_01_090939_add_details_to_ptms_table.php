<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ptms', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('title');
            $table->date('end_date')->nullable()->after('start_date');

            $table->json('ptm_for')->nullable()->after('end_date');

            $table->json('staff_departments')
                ->nullable()
                ->after('ptm_for');

            $table->json('student_departments')
                ->nullable()
                ->after('staff_departments');

            $table->json('standards')
                ->nullable()
                ->after('student_departments');

            $table->json('divisions')
                ->nullable()
                ->after('standards');

            $table->boolean('send_sms')
                ->default(false)
                ->after('divisions');
        });
    }

    public function down(): void
    {
        Schema::table('ptms', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
                'ptm_for',
                'staff_departments',
                'student_departments',
                'standards',
                'divisions',
                'send_sms',
            ]);
        });
    }
};
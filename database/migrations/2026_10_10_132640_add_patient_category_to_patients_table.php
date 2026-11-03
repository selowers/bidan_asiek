<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('patient_category', 20)->nullable();
        });

        $today = now()->toDateString();
        $childCutoff = now()->subYears(18)->toDateString();

        DB::table('patients')
            ->whereNotNull('date_of_birth')
            ->whereDate('date_of_birth', '>', $childCutoff)
            ->whereDate('date_of_birth', '<=', $today)
            ->update(['patient_category' => 'child']);

        DB::table('patients')->whereNull('patient_category')->where('gender', 'male')->update(['patient_category' => 'male']);
        DB::table('patients')->whereNull('patient_category')->where('gender', 'female')->update(['patient_category' => 'female']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('patient_category');
        });
    }
};

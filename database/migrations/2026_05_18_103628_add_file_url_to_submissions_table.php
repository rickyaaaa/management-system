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
        Schema::table('submissions', function (Blueprint $table) {
            // Add unified file column (nullable so existing rows don't break)
            $table->text('file_url')->nullable()->after('version');
        });

        // Migrate existing data: prefer blend file, fallback to mov
        \DB::table('submissions')->orderBy('id')->each(function ($row) {
            $url = $row->file_blend_url ?? $row->file_mov_url ?? null;
            \DB::table('submissions')->where('id', $row->id)->update(['file_url' => $url]);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['file_blend_url', 'file_mov_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->text('file_blend_url')->nullable()->after('version');
            $table->text('file_mov_url')->nullable()->after('file_blend_url');
            $table->dropColumn('file_url');
        });
    }
};

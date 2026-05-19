<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Two separate optional upload columns
            $table->text('blend_url')->nullable()->after('file_url');
            $table->text('video_url')->nullable()->after('blend_url');
        });

        // Migrate existing file_url data into the correct column based on extension
        \DB::table('submissions')->orderBy('id')->each(function ($row) {
            if (!$row->file_url) return;
            $ext = strtolower(pathinfo($row->file_url, PATHINFO_EXTENSION));
            if ($ext === 'blend') {
                \DB::table('submissions')->where('id', $row->id)->update(['blend_url' => $row->file_url]);
            } elseif (in_array($ext, ['mp4', 'mov', 'avi'])) {
                \DB::table('submissions')->where('id', $row->id)->update(['video_url' => $row->file_url]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['blend_url', 'video_url']);
        });
    }
};

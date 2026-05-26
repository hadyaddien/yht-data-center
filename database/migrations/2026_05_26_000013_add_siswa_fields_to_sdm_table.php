<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm', function (Blueprint $table) {
            $table->unsignedSmallInteger('jumlah_murid_total')->default(0)->after('jumlah_rombel');
            $table->unsignedSmallInteger('jumlah_murid_laki')->default(0)->after('jumlah_murid_total');
            $table->unsignedSmallInteger('jumlah_murid_perempuan')->default(0)->after('jumlah_murid_laki');

            $table->unsignedSmallInteger('murid_ortu_tni_al')->default(0)->after('jumlah_murid_perempuan');
            $table->unsignedSmallInteger('murid_ortu_tni')->default(0)->after('murid_ortu_tni_al');
            $table->unsignedSmallInteger('murid_ortu_polisi')->default(0)->after('murid_ortu_tni');
            $table->unsignedSmallInteger('murid_ortu_pns')->default(0)->after('murid_ortu_polisi');
            $table->unsignedSmallInteger('murid_ortu_pengusaha')->default(0)->after('murid_ortu_pns');
            $table->unsignedSmallInteger('murid_ortu_wiraswasta')->default(0)->after('murid_ortu_pengusaha');
            $table->unsignedSmallInteger('murid_ortu_buruh')->default(0)->after('murid_ortu_wiraswasta');
            $table->unsignedSmallInteger('murid_ortu_guru')->default(0)->after('murid_ortu_buruh');
            $table->string('murid_ortu_lainnya_label', 100)->nullable()->after('murid_ortu_guru');
            $table->unsignedSmallInteger('murid_ortu_lainnya_jumlah')->default(0)->after('murid_ortu_lainnya_label');
        });
    }

    public function down(): void
    {
        Schema::table('sdm', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_murid_total',
                'jumlah_murid_laki',
                'jumlah_murid_perempuan',
                'murid_ortu_tni_al',
                'murid_ortu_tni',
                'murid_ortu_polisi',
                'murid_ortu_pns',
                'murid_ortu_pengusaha',
                'murid_ortu_wiraswasta',
                'murid_ortu_buruh',
                'murid_ortu_guru',
                'murid_ortu_lainnya_label',
                'murid_ortu_lainnya_jumlah',
            ]);
        });
    }
};

<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\KotaKabupaten;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Kelurahan::truncate();
        Kecamatan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        /**
         * Format:
         * 'KODE_KOTA' => [
         *     'Nama Kecamatan' => ['Kelurahan 1', 'Kelurahan 2', ...],
         * ]
         */
        $data = [

            // ── KOTA JAKARTA SELATAN ────────────────────────────────
            '3171' => [
                'Tebet' => ['Tebet Barat', 'Tebet Timur', 'Menteng Dalam', 'Bukit Duri', 'Manggarai', 'Manggarai Selatan', 'Kebon Baru'],
                'Setiabudi' => ['Setiabudi', 'Karet Semanggi', 'Karet', 'Karet Kuningan', 'Kuningan Timur', 'Menteng Atas', 'Pasar Manggis', 'Guntur'],
                'Mampang Prapatan' => ['Kuningan Barat', 'Bangka', 'Pela Mampang', 'Tegal Parang', 'Mampang Prapatan'],
                'Pasar Minggu' => ['Pasar Minggu', 'Jati Padang', 'Cilandak Timur', 'Ragunan', 'Kebagusan', 'Pejaten Barat', 'Pejaten Timur', 'Pengadegan'],
                'Kebayoran Lama' => ['Kebayoran Lama Selatan', 'Kebayoran Lama Utara', 'Grogol Selatan', 'Grogol Utara', 'Cipulir', 'Pesanggrahan'],
                'Cilandak' => ['Cilandak Barat', 'Lebak Bulus', 'Fatmawati', 'Gandaria Selatan', 'Pondok Labu'],
                'Kebayoran Baru' => ['Petogogan', 'Gunung', 'Kramat Pela', 'Pulo', 'Gandaria Utara', 'Cipete Utara', 'Melawai', 'Cipete Selatan', 'Senayan', 'Selong', 'Rawa Barat'],
                'Pesanggrahan' => ['Pesanggrahan', 'Ulujami', 'Bintaro', 'Petukangan Utara', 'Petukangan Selatan'],
                'Jagakarsa' => ['Jagakarsa', 'Lenteng Agung', 'Ciganjur', 'Cipedak', 'Tanjung Barat', 'Srengseng Sawah'],
                'Pancoran' => ['Pancoran', 'Kalibata', 'Pengadegan', 'Duren Tiga', 'Rawajati', 'Cikoko'],
            ],

            // ── KOTA JAKARTA TIMUR ────────────────────────────────
            '3172' => [
                'Matraman' => ['Palmeriam', 'Kayu Manis', 'Kebon Manggis', 'Utan Kayu Selatan', 'Utan Kayu Utara'],
                'Pulo Gadung' => ['Kayu Putih', 'Jati', 'Pisangan Timur', 'Cipinang', 'Rawamangun', 'Pulo Gadung'],
                'Jatinegara' => ['Bidara Cina', 'Bali Mester', 'Rawa Bunga', 'Kampung Melayu', 'Cipinang Besar Selatan', 'Cipinang Besar Utara', 'Cipinang Cempedak', 'Cipinang Muara'],
                'Duren Sawit' => ['Klender', 'Pondok Kopi', 'Duren Sawit', 'Pondok Bambu', 'Malaka Jaya', 'Malaka Sari', 'Pondok Kelapa'],
                'Kramat Jati' => ['Cawang', 'Cililitan', 'Kramat Jati', 'Balekambang', 'Dukuh', 'Batu Ampar'],
                'Pasar Rebo' => ['Pekayon', 'Kalisari', 'Baru', 'Cijantung', 'Gedong'],
                'Ciracas' => ['Ciracas', 'Cibubur', 'Kelapa Dua Wetan', 'Susukan', 'Rambutan'],
                'Cipayung' => ['Cipayung', 'Cilangkap', 'Setu', 'Bambu Apus', 'Munjul', 'Pondok Ranggon', 'Lubang Buaya'],
                'Makasar' => ['Makasar', 'Cipinang Melayu', 'Halim Perdana Kusuma', 'Kebon Pala', 'Pinang Ranti'],
                'Cakung' => ['Jatinegara', 'Penggilingan', 'Cakung Barat', 'Cakung Timur', 'Rawa Terate', 'Pulo Gebang', 'Ujung Menteng'],
            ],

            // ── KOTA JAKARTA PUSAT ────────────────────────────────
            '3173' => [
                'Gambir' => ['Gambir', 'Kebon Kelapa', 'Petojo Selatan', 'Petojo Utara', 'Cideng', 'Duri Pulo'],
                'Sawah Besar' => ['Pasar Baru', 'Kartini', 'Gunung Sahari Utara', 'Karang Anyar', 'Mangga Dua Selatan'],
                'Kemayoran' => ['Gunung Sahari Selatan', 'Serdang', 'Utan Panjang', 'Sumur Batu', 'Harapan Mulia', 'Cempaka Baru', 'Kebon Kosong', 'Kemayoran'],
                'Senen' => ['Senen', 'Kenari', 'Paseban', 'Bungur', 'Kramat', 'Kwitang'],
                'Cempaka Putih' => ['Cempaka Putih Barat', 'Cempaka Putih Timur', 'Rawasari'],
                'Johar Baru' => ['Johar Baru', 'Tanah Tinggi', 'Galur', 'Kampung Rawa'],
                'Menteng' => ['Menteng', 'Pegangsaan', 'Cikini', 'Gondangdia', 'Kebon Sirih'],
                'Tanah Abang' => ['Tanah Abang', 'Jati Baru', 'Kebon Melati', 'Kebon Kacang', 'Bendungan Hilir', 'Gelora', 'Petamburan'],
            ],

            // ── KOTA JAKARTA BARAT ────────────────────────────────
            '3174' => [
                'Cengkareng' => ['Cengkareng Barat', 'Cengkareng Timur', 'Kapuk', 'Kedaung Kaliangke', 'Rawa Buaya', 'Duri Kosambi'],
                'Grogol Petamburan' => ['Grogol', 'Tomang', 'Jelambar', 'Jelambar Baru', 'Tanjung Duren Selatan', 'Tanjung Duren Utara', 'Wijaya Kusuma'],
                'Taman Sari' => ['Taman Sari', 'Krukut', 'Maphar', 'Tangki', 'Mangga Besar', 'Glodok', 'Pinangsia'],
                'Tambora' => ['Tambora', 'Kali Anyar', 'Duri Selatan', 'Duri Utara', 'Tanah Sereal', 'Angke', 'Jembatan Lima', 'Jembatan Besi'],
                'Kebon Jeruk' => ['Kebon Jeruk', 'Sukabumi Utara', 'Sukabumi Selatan', 'Kelapa Dua', 'Duri Kepa', 'Kedoya Selatan', 'Kedoya Utara'],
                'Kembangan' => ['Kembangan Utara', 'Kembangan Selatan', 'Meruya Utara', 'Meruya Selatan', 'Joglo', 'Srengseng'],
                'Kalideres' => ['Kalideres', 'Semanan', 'Tegal Alur', 'Pegadungan', 'Kamal'],
                'Pal Merah' => ['Pal Merah', 'Jati Pulo', 'Kota Bambu Selatan', 'Kota Bambu Utara', 'Kemanggisan', 'Slipi'],
            ],

            // ── KOTA JAKARTA UTARA ────────────────────────────────
            '3175' => [
                'Penjaringan' => ['Penjaringan', 'Penjagalan', 'Kamal Muara', 'Kapuk Muara', 'Pluit'],
                'Pademangan' => ['Pademangan Barat', 'Pademangan Timur', 'Ancol'],
                'Tanjung Priok' => ['Tanjung Priok', 'Sunter Agung', 'Papanggo', 'Sungai Bambu', 'Kebon Bawang', 'Sunter Jaya'],
                'Koja' => ['Koja', 'Tugu Selatan', 'Tugu Utara', 'Lagoa', 'Rawa Badak Selatan', 'Rawa Badak Utara'],
                'Kelapa Gading' => ['Kelapa Gading Barat', 'Kelapa Gading Timur', 'Pegangsaan Dua'],
                'Cilincing' => ['Cilincing', 'Semper Barat', 'Semper Timur', 'Rorotan', 'Sukapura', 'Marunda', 'Kalibaru'],
            ],

            // ── KOTA SURABAYA ─────────────────────────────────────
            '3578' => [
                'Tegalsari' => ['Keputran', 'Kedungdoro', 'Dr. Sutomo', 'Wonorejo', 'Tegalsari'],
                'Genteng' => ['Ketabang', 'Kapasari', 'Peneleh', 'Embong Kaliasin', 'Genteng'],
                'Bubutan' => ['Bubutan', 'Alun-Alun Contong', 'Gundih', 'Jepara', 'Tembok Dukuh'],
                'Simokerto' => ['Simokerto', 'Simolawang', 'Kapasan', 'Tambak Rejo', 'Sidodadi'],
                'Pabean Cantikan' => ['Pabean Cantikan', 'Bongkaran', 'Nyamplungan', 'Krembangan Selatan', 'Perak Barat', 'Perak Timur'],
                'Semampir' => ['Wonokusumo', 'Pegirian', 'Ujung', 'Ampel', 'Sidotopo'],
                'Krembangan' => ['Dupak', 'Kemayoran', 'Morokrembangan', 'Perak Utara'],
                'Kenjeran' => ['Kenjeran', 'Sidotopo Wetan', 'Tambak Wedi', 'Bulak Banteng'],
                'Tambaksari' => ['Tambaksari', 'Ploso', 'Rangkah', 'Pacarkeling', 'Gading', 'Kapas Madya'],
                'Gubeng' => ['Gubeng', 'Mojo', 'Airlangga', 'Pucang Sewu', 'Kertajaya'],
                'Wonokromo' => ['Wonokromo', 'Ngagel', 'Ngagel Rejo', 'Jagir', 'Sawunggaling'],
                'Sawahan' => ['Sawahan', 'Kupang Krajan', 'Putat Jaya', 'Petemon', 'Banyu Urip'],
                'Rungkut' => ['Rungkut Kidul', 'Kali Rungkut', 'Medokan Ayu', 'Wonorejo', 'Kedung Baruk'],
                'Sukolilo' => ['Keputih', 'Gebang Putih', 'Semolowaru', 'Medokan Semampir', 'Klampisngasem'],
                'Mulyorejo' => ['Mulyorejo', 'Kalijudan', 'Dukuh Sutorejo', 'Kalisari', 'Kejawan Putih Tambak'],
            ],

            // ── KOTA MALANG ───────────────────────────────────────
            '3573' => [
                'Kedungkandang' => ['Arjowinangun', 'Cemorokandang', 'Lesanpuro', 'Madyopuro', 'Sawojajar', 'Bumiayu', 'Buring'],
                'Sukun' => ['Bandungrejosari', 'Bakalankrajan', 'Ciptomulyo', 'Gadang', 'Kebonsari', 'Sukun', 'Tanjungrejo'],
                'Klojen' => ['Bareng', 'Gadingkasri', 'Kasin', 'Kauman', 'Klojen', 'Oro-Oro Dowo', 'Rampal Celaket'],
                'Blimbing' => ['Arjosari', 'Balearjosari', 'Blimbing', 'Bunulrejo', 'Jodipan', 'Purwodadi', 'Purwantoro'],
                'Lowokwaru' => ['Dinoyo', 'Jatimulyo', 'Lowokwaru', 'Merjosari', 'Mojolangu', 'Sumbersari', 'Tlogomas'],
            ],

            // ── KOTA MAKASSAR ─────────────────────────────────────
            '7371' => [
                'Ujung Pandang' => ['Losari', 'Ujung Pandang', 'Bulo', 'Lajangiru', 'Pisang Selatan', 'Mangkura'],
                'Makassar' => ['Ballaparang', 'Maccini', 'Bara-Baraya', 'Bara-Baraya Selatan', 'Bara-Baraya Timur', 'Maccini Gusung'],
                'Bontoala' => ['Bontoala', 'Bontoala Parang', 'Layang', 'Baraya', 'Tompo Balang', 'Wajo Baru'],
                'Mamajang' => ['Mamajang Dalam', 'Mamajang Luar', 'Kampung Buyang', 'Labuang Baji', 'Bontobiraeng'],
                'Mariso' => ['Mariso', 'Panambungan', 'Mattoangin', 'Kunjung Mae', 'Lette', 'Mario', 'Tamarunang'],
                'Rappocini' => ['Rappocini', 'Banta-Bantaeng', 'Gunung Sari', 'Karunrung', 'Tidung', 'Mappala'],
                'Tamalate' => ['Jongaya', 'Bongaya', 'Parang Tambung', 'Mannuruki', 'Tanjung Merdeka', 'Maccini Sombala'],
                'Panakkukang' => ['Panakkukang', 'Pampang', 'Karuwisi', 'Masale', 'Tamamaung', 'Tello Baru'],
                'Manggala' => ['Manggala', 'Tamangapa', 'Bangkala', 'Antang', 'Borong', 'Batua'],
                'Biringkanaya' => ['Bulurokeng', 'Sudiang Raya', 'Sudiang', 'Pai', 'Paccerakang', 'Daya'],
                'Tamalanrea' => ['Tamalanrea', 'Kapasa', 'Parangloe', 'Tamalanrea Jaya', 'Tamalanrea Indah'],
                'Tallo' => ['Rappokalling', 'Lakkang', 'Tallo', 'Kalukuang', 'La\'latang', 'Suangga'],
                'Ujung Tanah' => ['Pattingtaloang', 'Gusung', 'Camba Berua', 'Totaka', 'Cambaya'],
                'Wajo' => ['Melayu', 'Melayu Baru', 'Pattunuang', 'Malimongan', 'Butung'],
            ],

            // ── KOTA PAREPARE ─────────────────────────────────────
            '7372' => [
                'Bacukiki' => ['Lompoe', 'Galung Maloang', 'Cappa Galung', 'Kampung Baru'],
                'Bacukiki Barat' => ['Bumi Harapan', 'Sumpang Minangae', 'Bukit Harapan', 'Labukkang', 'Cappa Ujung'],
                'Ujung' => ['Ujung Baru', 'Ujung Lare', 'Lakessi', 'Wattang Soreang'],
                'Soreang' => ['Kampung Pisang', 'Wattang Bacukiki', 'Lumpue', 'Bukit Indah', 'Tiro Sompe'],
            ],

            // ── KOTA MANADO ───────────────────────────────────────
            '7171' => [
                'Wenang' => ['Pinaesaan', 'Mahakeret Barat', 'Mahakeret Timur', 'Tikala Ares', 'Wenang Selatan', 'Wenang Utara'],
                'Sario' => ['Sario', 'Sario Tumpaan', 'Sario Kotabaru', 'Sario Utara', 'Titiwungen Selatan', 'Titiwungen Utara'],
                'Malalayang' => ['Malalayang I', 'Malalayang I Timur', 'Malalayang II', 'Bahu', 'Kleak', 'Winangun I', 'Winangun II'],
                'Mapanget' => ['Mapanget Barat', 'Paniki I', 'Paniki II', 'Paniki Bawah', 'Pandu', 'Buha', 'Kairagi I'],
                'Singkil' => ['Singkil I', 'Singkil II', 'Wawonasa', 'Karame', 'Ternate Baru', 'Ternate Tanjung'],
                'Tikala' => ['Tikala Baru', 'Tikala Kumaraka', 'Taas', 'Ranomut', 'Paal II', 'Paal IV'],
                'Tuminting' => ['Tuminting', 'Islam', 'Maasing', 'Sindulang I', 'Sindulang II', 'Sumompo', 'Tumumpa I', 'Tumumpa II'],
                'Wanea' => ['Wanea', 'Teling Atas', 'Pakowa', 'Ranotana Weru', 'Bumi Nyiur', 'Tingkulu'],
                'Paal Dua' => ['Paal Dua', 'Dendengan Dalam', 'Kairagi Weru', 'Bengkol', 'Paniki I (Paal Dua)'],
                'Bunaken' => ['Bunaken', 'Manado Tua I', 'Manado Tua II', 'Alung Banua', 'Nain', 'Siladen'],
            ],

            // ── KOTA BITUNG ───────────────────────────────────────
            '7172' => [
                'Girian' => ['Girian Bawah', 'Girian Atas', 'Girian Indah', 'Girian Weru I', 'Girian Weru II'],
                'Maesa' => ['Maesa', 'Bitung Barat I', 'Bitung Barat II'],
                'Madidir' => ['Madidir Unet', 'Madidir Weru', 'Madidir Ure', 'Bitung Tengah', 'Tanjung Merah'],
                'Aertembaga' => ['Aertembaga I', 'Aertembaga II', 'Bitung Timur', 'Keker', 'Apela I', 'Apela II', 'Paceda'],
                'Lembeh Selatan' => ['Masa', 'Paudean', 'Pancuran', 'Batulubang', 'Binuang', 'Mawali'],
                'Lembeh Utara' => ['Pasigitan', 'Pintukota', 'Nusu', 'Tandurusa', 'Sagerat', 'Karondoran'],
                'Ranowangko' => ['Ranowangko', 'Rinondoran', 'Tewaan', 'Tolenru', 'Batuputih Atas', 'Batuputih Bawah'],
                'Matuari' => ['Matuari', 'Pateten I', 'Pateten II', 'Pateten III', 'Sagerat Atas'],
            ],

            // ── KOTA SEMARANG ─────────────────────────────────────
            '3374' => [
                'Semarang Tengah' => ['Kauman', 'Bangunharjo', 'Miroto', 'Pindrikan Lor', 'Pekunden', 'Sekayu', 'Jagalan'],
                'Semarang Utara' => ['Bandarharjo', 'Bulu Lor', 'Dadapsari', 'Panggung Lor', 'Plombokan', 'Tanjung Mas'],
                'Semarang Selatan' => ['Lamper Kidul', 'Lamper Lor', 'Mugassari', 'Pleburan', 'Randusari', 'Wonodri'],
                'Semarang Timur' => ['Bugangan', 'Karang Tempel', 'Kemijen', 'Mlatibaru', 'Rejomulyo', 'Sarirejo'],
                'Semarang Barat' => ['Bongsari', 'Bojongsalaman', 'Gisikdrono', 'Krobokan', 'Krapyak', 'Tawangmas'],
                'Gayamsari' => ['Gayamsari', 'Kaligawe', 'Tambakrejo', 'Siwalan', 'Pandean Lamper'],
                'Genuk' => ['Genuksari', 'Karangroto', 'Muktiharjo Lor', 'Terboyo Wetan', 'Terboyo Kulon'],
                'Banyumanik' => ['Banyumanik', 'Pedalangan', 'Srondol Kulon', 'Padangsari', 'Pudakpayung'],
                'Tembalang' => ['Tembalang', 'Kedungmundu', 'Rowosari', 'Sendangmulyo', 'Tandang', 'Mangunharjo'],
                'Candisari' => ['Candi', 'Kaliwiru', 'Karangrejo', 'Jatingaleh', 'Jomblang', 'Tegalsari'],
                'Gunungpati' => ['Gunungpati', 'Sumurrejo', 'Sadeng', 'Kalisegoro', 'Mangunsari'],
                'Mijen' => ['Mijen', 'Ngadirgo', 'Kedungpane', 'Pesantren', 'Wonoplumbon'],
            ],

            // ── KOTA BANDUNG ──────────────────────────────────────
            '3273' => [
                'Bandung Wetan' => ['Tamansari', 'Citarum', 'Cihapit', 'Sukaluyu'],
                'Coblong' => ['Lebak Gede', 'Sekeloa', 'Cipaganti', 'Sadang Serang', 'Dago', 'Lebak Siliwangi'],
                'Sukasari' => ['Gegerkalong', 'Sukarasa', 'Isola', 'Sarijadi'],
                'Sukajadi' => ['Sukajadi', 'Cipedes', 'Sukawarna', 'Pasteur'],
                'Cicendo' => ['Arjuna', 'Husein Sastranegara', 'Pajajaran', 'Pamoyanan', 'Pasirkaliki'],
                'Andir' => ['Ciroyom', 'Dunguscariang', 'Garuda', 'Kebon Jeruk', 'Campaka'],
                'Regol' => ['Ancol', 'Balong Gede', 'Cigereleng', 'Ciateul', 'Pungkur', 'Pasirluyu'],
                'Lengkong' => ['Cijagra', 'Burangrang', 'Turangga', 'Malabar', 'Cikawao'],
                'Batununggal' => ['Binong', 'Cibangkong', 'Gumuruh', 'Kacapiring', 'Kebon Waru', 'Maleer'],
                'Kiaracondong' => ['Babakan Surabaya', 'Cicaheum', 'Kebon Jayanti', 'Sukapura', 'Babakan Sari'],
                'Buah Batu' => ['Sekejati', 'Margasari', 'Cijawura', 'Jatisari'],
                'Rancasari' => ['Manjahlega', 'Cipamokolan', 'Derwati', 'Mekar Jaya'],
            ],
        ];

        foreach ($data as $kodeKota => $kecamatanList) {
            $kotaId = KotaKabupaten::where('kode', $kodeKota)->value('id');
            if (! $kotaId) {
                $this->command->warn("Kota dengan kode {$kodeKota} tidak ditemukan, dilewati.");

                continue;
            }

            foreach ($kecamatanList as $kecNama => $kelurahanList) {
                $kec = Kecamatan::create([
                    'kota_kabupaten_id' => $kotaId,
                    'nama' => $kecNama,
                ]);

                $rows = array_map(fn ($n) => ['kecamatan_id' => $kec->id, 'nama' => $n], $kelurahanList);
                Kelurahan::insert($rows);
            }
        }

        $this->command->info('KecamatanSeeder: '.Kecamatan::count().' kecamatan, '.Kelurahan::count().' kelurahan seeded.');
    }
}

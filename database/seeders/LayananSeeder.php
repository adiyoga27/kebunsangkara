<?php

namespace Database\Seeders;

use App\Enums\Locale;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Starter services: adjust the texts from the admin panel to what is really offered.
 * Services that already exist, also in the trash, are left untouched.
 */
class LayananSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach ($this->services() as $index => $service) {
            Service::withTrashed()->firstOrCreate(['name' => $service['name']], [
                'summary' => $service['summary'],
                'description' => $service['description'],
                'translations' => [Locale::English->value => $service['english']],
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }
    }

    /**
     * @return list<array{name: string, summary: string, description: string, english: array<string, string>}>
     */
    private function services(): array
    {
        return [
            [
                'name' => 'Edukasi Pertanian',
                'summary' => 'Belajar mengenal tanaman buah dan cara merawatnya langsung di kebun, untuk sekolah, komunitas, keluarga, maupun pemula.',
                'description' => <<<'MARKDOWN'
                    Kenali pertanian dengan melihat dan mencobanya sendiri. Materi kami sesuaikan dengan usia dan kebutuhan peserta.

                    ## Cocok untuk

                    - Kunjungan sekolah, dari TK hingga perguruan tinggi
                    - Komunitas dan kelompok tani
                    - Keluarga dan pemula yang ingin mulai berkebun

                    ## Materi yang bisa dipilih

                    - Mengenal tanaman durian, alpukat, dan manggis
                    - Menyiapkan media tanam dan menanam bibit
                    - Pengenalan perbanyakan bibit, seperti okulasi dan sambung pucuk
                    - Pemupukan, penyiraman, dan pemangkasan
                    - Mengenali hama dan penyakit tanaman
                    - Mengenal ciri buah matang dan cara panen, mengikuti musim

                    ## Cara memesan

                    Beri tahu kami jumlah dan usia peserta, tanggal yang diinginkan, serta materi yang diminati. Jadwal, durasi, dan biaya kami sepakati melalui WhatsApp.
                    MARKDOWN,
                'english' => [
                    'name' => 'Agricultural Education',
                    'summary' => 'Learn about fruit trees and how to care for them right on the farm, for schools, communities, families and beginners.',
                    'description' => <<<'MARKDOWN'
                        Discover farming by seeing it and trying it yourself. We adapt the topics to the age and needs of the group.

                        ## Who it is for

                        - School visits, from kindergarten to university
                        - Communities and farmer groups
                        - Families and beginners who want to start growing

                        ## Topics to choose from

                        - Getting to know durian, avocado and mangosteen trees
                        - Preparing growing media and planting seedlings
                        - An introduction to propagation, such as budding and top grafting
                        - Fertilizing, watering and pruning
                        - Recognizing plant pests and diseases
                        - Recognizing ripe fruit and how to harvest it, depending on the season

                        ## How to book

                        Tell us the number and age of participants, your preferred date and the topics you are interested in. We agree on the schedule, duration and cost via WhatsApp.
                        MARKDOWN,
                ],
            ],
            [
                'name' => 'Konsultasi & Pendampingan Kebun',
                'summary' => 'Teman diskusi untuk merencanakan, menanam, dan merawat kebun buah Anda, dari pemilihan bibit hingga panen.',
                'description' => <<<'MARKDOWN'
                    Baru membuka lahan atau ingin memperbaiki kebun yang sudah ada? Kami bantu menyusun langkah yang sesuai dengan kondisi kebun Anda.

                    ## Yang bisa dikonsultasikan

                    - Pengamatan kondisi lahan, air, dan lingkungan kebun
                    - Pemilihan jenis dan varietas bibit
                    - Perencanaan jarak tanam dan penyiapan lubang tanam
                    - Jadwal pemupukan, penyiraman, dan pemangkasan
                    - Pengenalan serta pengendalian hama dan penyakit
                    - Persiapan berbunga, berbuah, dan panen

                    ## Alur pendampingan

                    1. **Ceritakan kondisi kebun** melalui WhatsApp, sertakan foto dan lokasinya
                    2. **Diskusi awal** untuk memahami kebutuhan Anda
                    3. **Kunjungan kebun** bila diperlukan, dengan jadwal yang disepakati
                    4. **Rekomendasi dan pemantauan** sesuai kesepakatan

                    Hasil setiap kebun dipengaruhi cuaca, kondisi lahan, dan perawatan. Cakupan dan biaya layanan kami sampaikan setelah mengetahui kebutuhan Anda.
                    MARKDOWN,
                'english' => [
                    'name' => 'Farm Consulting & Assistance',
                    'summary' => 'A partner to help you plan, plant and care for your fruit farm, from choosing seedlings to harvest.',
                    'description' => <<<'MARKDOWN'
                        Starting on new land or improving an existing farm? We help you work out steps that suit the conditions of your farm.

                        ## What you can consult us about

                        - Observing the land, water and surroundings of the farm
                        - Choosing seedling types and varieties
                        - Planning plant spacing and preparing planting holes
                        - Fertilizing, watering and pruning schedules
                        - Recognizing and controlling pests and diseases
                        - Preparing for flowering, fruiting and harvest

                        ## How it works

                        1. **Tell us about your farm** via WhatsApp, with photos and its location
                        2. **First discussion** to understand your needs
                        3. **Farm visit** when needed, on an agreed date
                        4. **Recommendations and follow-up** as agreed

                        The results of every farm depend on weather, land conditions and care. We share the scope and cost once we know your needs.
                        MARKDOWN,
                ],
            ],
            [
                'name' => 'Acara',
                'summary' => 'Rencanakan acara bernuansa kebun untuk keluarga, komunitas, sekolah, atau kantor Anda.',
                'description' => <<<'MARKDOWN'
                    Ingin acara yang dekat dengan alam? Ceritakan rencana Anda, lalu kita susun acara yang sesuai.

                    ## Jenis acara

                    - Kumpul keluarga dan komunitas
                    - Gathering atau outing kantor
                    - Kegiatan sekolah di luar kelas
                    - Acara menanam bersama
                    - Workshop dan pelatihan berkebun
                    - Acara panen dan mencicipi buah, mengikuti musim

                    ## Yang perlu disiapkan

                    - Perkiraan jumlah peserta
                    - Tanggal dan durasi acara
                    - Kegiatan yang diinginkan
                    - Kebutuhan khusus, misalnya konsumsi atau peserta anak-anak

                    Ketersediaan tempat, fasilitas, dan biaya kami konfirmasi melalui WhatsApp sebelum acara dipastikan. Sebaiknya hubungi kami jauh hari agar persiapannya matang.
                    MARKDOWN,
                'english' => [
                    'name' => 'Events',
                    'summary' => 'Plan a farm-themed event for your family, community, school or office.',
                    'description' => <<<'MARKDOWN'
                        Looking for an event close to nature? Tell us your plans and we will put together an event that fits.

                        ## Types of events

                        - Family and community get-togethers
                        - Company gatherings or outings
                        - School activities outside the classroom
                        - Group tree planting
                        - Gardening workshops and training
                        - Harvest and fruit tasting events, depending on the season

                        ## What to tell us

                        - Estimated number of participants
                        - Date and duration
                        - Activities you would like
                        - Special needs, such as catering or children taking part

                        We confirm the venue, facilities and cost via WhatsApp before the event is final. Please contact us well in advance so everything can be prepared properly.
                        MARKDOWN,
                ],
            ],
        ];
    }
}

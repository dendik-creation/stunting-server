<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-8">
    <div class="">
        <h1 class="text-2xl font-bold mb-2">Laporan Data Keluarga {{ $data->nama_lengkap }}</h1>
        <span>Laporan berdasarkan data yang disimpan dan diolah oleh <i>Family Care Stunting</i></span>
        <div class="bg-white rounded mt-6">
            {{-- Identitas --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">A. Data Keluarga</h2>
                <ul class="w-full">
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">NIK</span>
                        <span>: {{ $data->nik }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">Nama Lengkap</span>
                        <span>: {{ $data->nama_lengkap }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">Desa</span>
                        <span>: {{ $data->desa }} - RT {{ $data->rt }} / RW {{ $data->rw }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">No Telepon</span>
                        <span>: {{ $data->no_telp }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">Alamat Lengkap</span>
                        <span>: {{ $data->alamat }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-44">Puskesmas Pengampu</span>
                        <span>: {{ $data->puskesmas->nama_puskesmas }}</span>
                    </li>
                </ul>
            </div>
            {{-- Data Anak Sakit --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">B. Data Anak Sakit</h2>
                <ul class="w-full">
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Nama lengkap</span>
                        <span>: {{ $anak_sakit->nama_anak }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Usia</span>
                        <span>: {{ $anak_sakit->usia }} Bulan</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Jenis kelamin</span>
                        <span>: {{ $anak_sakit->jenis_kelamin == "L" ? "Laki-laki" : "Perempuan" }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Berat badan</span>
                        <span>: {{ $anak_sakit->berat_badan }} Kg</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Tinggi badan</span>
                        <span>: {{ $anak_sakit->tinggi_badan }} cm</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Riwayat kelahiran anak</span>
                        <span>: {{ $anak_sakit->berat_lahir == "normal" ? "Normal (lebih dari 2,5 kg)" : "Rendah (kurang dari 2,5 kg)" }}</span>
                    </li>
                    <li class="flex justify-start items-start">
                        <span class="font-normal w-48">Penyakit penyerta anak</span>
                        : &nbsp; <ul class="list-decimal ms-4">
                            @foreach ($anak_sakit->penyakit_anak as $item)
                                @if ($item->penyakit->jenis_penyakit == "penyerta")
                                <li>{{ $item->penyakit->nama_penyakit }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Status Ibu bekerja</span>
                        <span>: {{ $anak_sakit->ibu_bekerja ? "Ya" : "Tidak" }}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Pendidikan terkahir Ibu</span>
                        <span>: {{ $anak_sakit->pendidikan_ibu}}</span>
                    </li>
                    <li class="flex justify-start items-center">
                        <span class="font-normal w-48">Status orang tua merokok</span>
                        <span>: {{ $anak_sakit->orang_tua_merokok ? "Ya" : "Tidak" }}</span>
                    </li>
                    <li class="flex justify-start items-start">
                        <span class="font-normal w-48">Penyakit komplikasi kehamilan Ibu</span>
                        : &nbsp; <ul class="list-decimal ms-4">
                            @foreach ($anak_sakit->penyakit_anak as $item)
                                @if ($item->penyakit->jenis_penyakit == "komplikasi")
                                <li>{{ $item->penyakit->nama_penyakit }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            {{-- Hasil Tes --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">C. Hasil Tes</h2>
                <div class="">Keluarga ini dinyatakan
                    <b>
                        {{ $data->is_free_stunting ? "berhasil" : "gagal" }}
                    </b>
                    dalam mengikuti tes yang disediakan sistem.</div>
                    <div class="mb-4">Berikut hasil tes yang diikuti :</div>
                    <div class="mb-3">
                        <h3 class="text-lg font-semibold">
                            Tes Pertama - {{ date_format(date_create($data->tingkat_kemandirian[0]->tanggal), 'd F Y') }}
                        </h3>
                        <ul>
                            <li>
                                <div>Tingkatan Kemandirian : Tingkat {{ $data->tingkat_kemandirian[0]->tingkatan }}</div>
                                <div>Kesehatan Lingkungan  : Nilai total {{ $data->kesehatan_lingkungan[0]->nilai_total }}</div>
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <h3 class="text-lg font-semibold">
                            Tes Kedua - {{ date_format(date_create($data->tingkat_kemandirian[1]->tanggal), 'd F Y') }}
                        </h3>
                        <ul>
                            <li>
                                <div>Tingkatan Kemandirian : Tingkat {{ $data->tingkat_kemandirian[1]->tingkatan }}</div>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = () => window.print();
    </script>
</body>
</html>

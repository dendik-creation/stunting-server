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
        <h1 class="text-2xl font-bold mb-4">Laporan Data Keluarga {{ isset($filters['puskesmas']) ? 'Puskemas ' . $filters['puskesmas'] : ""  }}</h1>
        <div class="mb-2">Tanggal cetak <b>{{ $print_at }}</b></div>
        @if ($filters != null)
        <div class="mb-1">Filter yang diterapkan : </div>
        <ul class="list-decimal ms-3.5">
            @if (isset($filters['is_free_stunting']))
                <li class="">Keluarga yang {{ $filters['is_free_stunting'] ? "lulus" : "gagal"  }} tes</li>
            @endif
            @if (isset($filters['is_test_done']))
                <li class="">Keluarga yang {{ $filters['is_test_done'] ? "telah selesai" : "sedang mengikuti"  }} tes</li>
            @endif
            @if (isset($filters['date_range']))
                <li class="">Periode {{ $filters['date_range']['start'] }} - {{ $filters['date_range']['end'] }}</li>
            @endif
        </ul>
        @endif
        <div class="mb-1">Status Tes Keluarga : </div>
        <ul class="list-disc ms-3.5">
            @if ($test_status['running'] != 0)
            <li><b>{{ $test_status['running'] }}</b> keluarga dalam proses pengerjaan tes</li>
            @endif
            @if ($test_status['failed'] != 0)
            <li><b>{{ $test_status['failed'] }}</b> keluarga gagal dalam mengikuti tes</li>
            @endif
            @if ($test_status['success'] != 0)
            <li><b>{{ $test_status['success'] }}</b> keluarga berhasil dalam mengikuti tes</li>
            @endif
        </ul>
        <div class="bg-white rounded my-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">NIK</th>
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">Nama Lengkap</th>
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">No Telepon</th>
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">Desa</th>
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">Alamat Lengkap</th>
                        @if (!isset($filters['puskesmas']))
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">Puskesmas</th>
                        @endif
                        <th class="py-2 px-4 border border-gray-900 bg-green-500 text-white">Hasil Tes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="even:bg-gray-100">
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->nik }}</td>
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->nama_lengkap }}</td>
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->no_telp }}</td>
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->desa }} - RT {{ $item->rt }} / RW {{ $item->rw }} </td>
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->alamat }}</td>
                            @if (!isset($filters['puskesmas']))
                            <td class="py-2 px-4 border border-gray-400 text-center">{{ $item->puskesmas->nama_puskesmas }}</td>
                            @endif
                            <td class="py-2 px-4 border border-gray-400 text-center">
                                @if ($item->is_test_done == 0)
                                    Tes berjalan
                                @elseif ($item->is_test_done == 1 && $item->is_free_stunting == 0)
                                    Tes Gagal
                                @elseif ($item->is_test_done == 1 && $item->is_free_stunting == 1)
                                    Tes Berhasil
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        window.onload = () => window.print();
    </script>
</body>
</html>

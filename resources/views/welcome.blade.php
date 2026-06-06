<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Apel - Sistem Informasi Kehadiran & Kekuatan Apel</title>
    
    <!-- Google Fonts: Roboto & Outfits for Material Design feel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Minimal for layout utilities) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'Roboto', 'sans-serif'],
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Material Design shadow levels */
        .elevation-0 { box-shadow: none; }
        .elevation-1 { box-shadow: 0px 2px 1px -1px rgba(0,0,0,0.2), 0px 1px 1px 0px rgba(0,0,0,0.14), 0px 1px 3px 0px rgba(0,0,0,0.12); }
        .elevation-2 { box-shadow: 0px 3px 1px -2px rgba(0,0,0,0.2), 0px 2px 2px 0px rgba(0,0,0,0.14), 0px 1px 5px 0px rgba(0,0,0,0.12); }
        .elevation-4 { box-shadow: 0px 2px 4px -1px rgba(0,0,0,0.2), 0px 4px 5px 0px rgba(0,0,0,0.14), 0px 1px 10px 0px rgba(0,0,0,0.12); }
        .elevation-8 { box-shadow: 0px 5px 5px -3px rgba(0,0,0,0.2), 0px 8px 10px 1px rgba(0,0,0,0.14), 0px 3px 14px 2px rgba(0,0,0,0.12); }
        
        /* Smooth transitions */
        .material-btn {
            transition: box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-between font-sans text-gray-800 antialiased selection:bg-primary-100">

    <!-- Header / Navbar -->
    <header class="w-full bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-primary-500/20">
                    E
                </div>
                <div>
                    <h1 class="text-lg font-extrabold tracking-tight text-gray-900 leading-none">E-Apel</h1>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Digital Absensi</span>
                </div>
            </div>
            
            <a href="/admin" class="material-btn elevation-1 hover:elevation-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm px-5 py-2 rounded-lg inline-flex items-center">
                Portal Admin
                <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Hero -->
    <main class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
            
            <!-- Hero Left: Text & Info -->
            <div class="md:col-span-7 space-y-6 text-center md:text-left">
                <!-- Badge Instansi -->
                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-primary-50 border border-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.683 0-5.302.235-7.848.682V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    {{ $setting->nama_instansi ?? 'Sistem Informasi E-Apel' }}
                </div>

                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    Sistem Rekapitulasi <span class="text-primary-600">Kehadiran & Kekuatan</span> Apel Digital
                </h2>

                <p class="text-gray-500 text-base max-w-lg mx-auto md:mx-0 font-light leading-relaxed">
                    Sistem modern dan minimalis untuk memantau kehadiran apel personel secara real-time. Dilengkapi fitur pemindaian surat izin berbasis AI OCR kamera, ekspor PDF standar fisik, dan dasbor analitik.
                </p>

                <!-- Instansi Info -->
                @if($setting)
                <div class="p-4 bg-white rounded-xl border border-gray-100 elevation-1 space-y-2 max-w-lg text-xs text-gray-500">
                    <div class="flex items-start space-x-2">
                        <span class="font-bold text-gray-700 min-w-[70px]">Alamat:</span>
                        <span>{{ $setting->alamat ?? '-' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-gray-700 min-w-[70px]">Kontak:</span>
                        <span>{{ $setting->kontak_person ?? '-' }}</span>
                    </div>
                </div>
                @endif

                <div class="pt-2 flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="/admin" class="material-btn elevation-2 hover:elevation-4 bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3.5 rounded-xl text-base inline-flex items-center">
                        Masuk Ke Portal E-Apel
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Hero Right: Material Card Grid -->
            <div class="md:col-span-5 grid grid-cols-1 gap-4">
                <!-- Card 1 -->
                <div class="p-6 bg-white rounded-2xl border border-gray-100 elevation-1 hover:elevation-2 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Presensi Sekali Klik</h3>
                    <p class="text-xs text-gray-400">Pencatatan status kehadiran praktis dan cepat langsung dari baris tabel absensi tanpa navigasi rumit.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-6 bg-white rounded-2xl border border-gray-100 elevation-1 hover:elevation-2 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Scan Surat Izin Kamera</h3>
                    <p class="text-xs text-gray-400">Pindai fisik surat keterangan sakit/izin menggunakan kamera browser untuk ekstraksi teks OCR instan.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-6 bg-white rounded-2xl border border-gray-100 elevation-1 hover:elevation-2 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Dasbor & Cetak Laporan PDF</h3>
                    <p class="text-xs text-gray-400">Pie Chart ringkasan kekuatan terbaru dan cetak PDF resmi format fisik menggunakan LibreOffice server.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-400">
        <p>&copy; {{ date('Y') }} {{ $setting->nama_instansi ?? 'E-Apel' }}. Hak Cipta Dilindungi Undang-Undang.</p>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Apel - Sistem Kehadiran Digital</title>
    <meta name="description" content="Sistem informasi kehadiran dan kekuatan apel digital untuk pencatatan presensi personel secara efisien.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --md-primary: #1a73e8;
            --md-on-primary: #ffffff;
            --md-surface: #ffffff;
            --md-on-surface: #1f1f1f;
            --md-on-surface-variant: #5f6368;
            --md-outline: #dadce0;
            --md-surface-container: #f8f9fa;
            --md-surface-container-high: #f1f3f4;
        }

        body {
            font-family: 'Inter', 'Google Sans', 'Roboto', system-ui, sans-serif;
            background: var(--md-surface);
            color: var(--md-on-surface);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* NAV */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--md-outline);
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--md-on-surface);
        }
        .nav-icon {
            width: 36px;
            height: 36px;
            background: var(--md-primary);
            color: var(--md-on-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }
        .nav-title {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: var(--md-primary);
            color: var(--md-on-primary);
            border: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: box-shadow 0.2s ease, background 0.2s ease;
        }
        .nav-btn:hover {
            background: #1765cc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.1);
        }

        /* HERO */
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }
        .hero-inner {
            max-width: 560px;
            text-align: center;
        }
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--md-surface-container-high);
            border: 1px solid var(--md-outline);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            color: var(--md-on-surface-variant);
            margin-bottom: 24px;
        }
        .hero-chip .material-symbols-outlined {
            font-size: 16px;
            color: var(--md-primary);
        }
        .hero h1 {
            font-size: 40px;
            font-weight: 700;
            letter-spacing: -0.025em;
            line-height: 1.15;
            color: var(--md-on-surface);
            margin-bottom: 16px;
        }
        .hero h1 span {
            color: var(--md-primary);
        }
        .hero p {
            font-size: 16px;
            color: var(--md-on-surface-variant);
            line-height: 1.6;
            max-width: 460px;
            margin: 0 auto 32px auto;
        }
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: var(--md-primary);
            color: var(--md-on-primary);
            border-radius: 24px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: box-shadow 0.2s ease, background 0.2s ease, transform 0.15s ease;
        }
        .hero-cta:hover {
            background: #1765cc;
            box-shadow: 0 2px 6px rgba(26,115,232,0.3);
            transform: translateY(-1px);
        }
        .hero-cta .material-symbols-outlined {
            font-size: 20px;
            transition: transform 0.2s ease;
        }
        .hero-cta:hover .material-symbols-outlined {
            transform: translateX(3px);
        }

        /* FEATURES */
        .features {
            display: flex;
            gap: 16px;
            justify-content: center;
            padding: 0 24px 48px 24px;
            flex-wrap: wrap;
            max-width: 800px;
            margin: 0 auto;
        }
        .feature-card {
            flex: 1;
            min-width: 200px;
            max-width: 240px;
            padding: 20px;
            border: 1px solid var(--md-outline);
            border-radius: 16px;
            text-align: center;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .feature-card:hover {
            border-color: transparent;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.05);
        }
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px auto;
        }
        .feature-icon .material-symbols-outlined {
            font-size: 22px;
        }
        .feature-icon.green  { background: #e6f4ea; color: #1e8e3e; }
        .feature-icon.blue   { background: #e8f0fe; color: #1a73e8; }
        .feature-icon.purple { background: #f3e8fd; color: #8430ce; }
        .feature-card h3 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--md-on-surface);
        }
        .feature-card p {
            font-size: 12px;
            color: var(--md-on-surface-variant);
            line-height: 1.5;
        }

        /* INFO */
        .info-bar {
            max-width: 560px;
            margin: 0 auto 40px auto;
            padding: 14px 20px;
            background: var(--md-surface-container);
            border-radius: 12px;
            border: 1px solid var(--md-outline);
            display: flex;
            gap: 24px;
            font-size: 12px;
            color: var(--md-on-surface-variant);
            flex-wrap: wrap;
            justify-content: center;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-item .material-symbols-outlined {
            font-size: 16px;
            color: var(--md-primary);
        }

        /* FOOTER */
        footer {
            padding: 20px 24px;
            border-top: 1px solid var(--md-outline);
            text-align: center;
            font-size: 12px;
            color: var(--md-on-surface-variant);
        }

        @media (max-width: 640px) {
            .hero h1 { font-size: 28px; }
            .hero p { font-size: 14px; }
            .features { flex-direction: column; align-items: center; }
            .feature-card { max-width: 100%; }
            .info-bar { flex-direction: column; gap: 8px; }
            nav { padding: 12px 16px; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="nav-brand">
            <div class="nav-icon">E</div>
            <span class="nav-title">E-Apel</span>
        </a>
        <a href="/admin" class="nav-btn">
            <span class="material-symbols-outlined" style="font-size:18px">login</span>
            Masuk
        </a>
    </nav>

    <main class="hero">
        <div class="hero-inner">
            <div class="hero-chip">
                <span class="material-symbols-outlined">apartment</span>
                {{ $setting->nama_instansi ?? 'Sistem Kehadiran Digital' }}
            </div>

            <h1>Kehadiran <span>Digital</span> yang Simpel & Efisien</h1>

            <p>Pencatatan presensi apel personel secara real-time dengan fitur OCR kamera, rekap otomatis, dan cetak laporan PDF.</p>

            @if($setting && ($setting->alamat || $setting->kontak_person))
            <div class="info-bar">
                @if($setting->alamat)
                <div class="info-item">
                    <span class="material-symbols-outlined">location_on</span>
                    {{ $setting->alamat }}
                </div>
                @endif
                @if($setting->kontak_person)
                <div class="info-item">
                    <span class="material-symbols-outlined">call</span>
                    {{ $setting->kontak_person }}
                </div>
                @endif
            </div>
            @endif

            <a href="/admin" class="hero-cta">
                Buka Portal E-Apel
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </main>

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon green">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <h3>Presensi Sekali Klik</h3>
            <p>Pencatatan status kehadiran cepat langsung dari tabel absensi.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon blue">
                <span class="material-symbols-outlined">document_scanner</span>
            </div>
            <h3>Scan Surat via Kamera</h3>
            <p>Pindai surat izin/sakit dengan OCR kamera untuk ekstraksi teks otomatis.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon purple">
                <span class="material-symbols-outlined">picture_as_pdf</span>
            </div>
            <h3>Cetak Laporan PDF</h3>
            <p>Ekspor rekap kekuatan apel menjadi dokumen PDF format resmi.</p>
        </div>
    </section>

    <footer>
        &copy; {{ date('Y') }} {{ $setting->nama_instansi ?? 'E-Apel' }}. Hak Cipta Dilindungi.
    </footer>

</body>
</html>

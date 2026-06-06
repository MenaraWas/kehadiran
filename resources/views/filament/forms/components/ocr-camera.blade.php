<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            streamActive: false,
            photoTaken: false,
            isScanning: false,
            progressText: '',
            stream: null,
            detectedText: '',
            capturedDataUrl: '',
            cameraError: '',
            tesseractReady: false,
            tesseractLoading: false,

            init() {
                this.loadTesseract();
            },

            loadTesseract() {
                if (window.Tesseract) {
                    this.tesseractReady = true;
                    return;
                }

                if (this.tesseractLoading) return;
                this.tesseractLoading = true;

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js';
                script.async = true;
                script.onload = () => {
                    this.tesseractReady = true;
                    this.tesseractLoading = false;
                };
                script.onerror = () => {
                    console.error('Failed to load Tesseract.js');
                    this.tesseractLoading = false;
                };
                document.head.appendChild(script);
            },

            isSecureContext() {
                return window.isSecureContext ||
                       location.protocol === 'https:' ||
                       location.hostname === 'localhost' ||
                       location.hostname === '127.0.0.1';
            },

            async startCamera() {
                this.cameraError = '';

                if (!this.isSecureContext()) {
                    this.cameraError = 'Kamera memerlukan koneksi HTTPS. Situs ini diakses melalui HTTP, sehingga kamera tidak bisa digunakan. Gunakan opsi \"Upload Foto Surat\" sebagai alternatif, atau akses melalui HTTPS.';
                    return;
                }

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    this.cameraError = 'Browser tidak mendukung akses kamera. Gunakan opsi \"Upload Foto Surat\" sebagai alternatif.';
                    return;
                }

                try {
                    // Try rear camera first (for mobile), fallback to any camera
                    let mediaStream = null;
                    try {
                        mediaStream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: { ideal: 'environment' },
                                width: { ideal: 1920, min: 640 },
                                height: { ideal: 1080, min: 480 }
                            },
                            audio: false
                        });
                    } catch (e) {
                        // Fallback: try without specific facing mode
                        mediaStream = await navigator.mediaDevices.getUserMedia({
                            video: true,
                            audio: false
                        });
                    }

                    this.stream = mediaStream;
                    this.streamActive = true;

                    await this.$nextTick();

                    const video = this.$refs.video;
                    if (video) {
                        video.srcObject = this.stream;
                        video.setAttribute('playsinline', '');
                        video.setAttribute('autoplay', '');
                        video.muted = true;

                        try {
                            await video.play();
                        } catch (playErr) {
                            console.warn('Auto-play prevented, user interaction may be needed:', playErr);
                        }
                    }
                } catch (err) {
                    console.error('Camera access failed:', err);
                    this.streamActive = false;

                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        this.cameraError = 'Izin kamera ditolak oleh browser. Aktifkan izin kamera di pengaturan browser, atau gunakan \"Upload Foto Surat\".';
                    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                        this.cameraError = 'Kamera tidak ditemukan di perangkat ini. Gunakan \"Upload Foto Surat\" sebagai alternatif.';
                    } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                        this.cameraError = 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain yang menggunakan kamera, atau gunakan \"Upload Foto Surat\".';
                    } else if (err.name === 'OverconstrainedError') {
                        this.cameraError = 'Kamera tidak mendukung resolusi yang diminta. Gunakan \"Upload Foto Surat\" sebagai alternatif.';
                    } else {
                        this.cameraError = 'Gagal mengakses kamera: ' + (err.message || err.name) + '. Gunakan \"Upload Foto Surat\" sebagai alternatif.';
                    }
                }
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
                const video = this.$refs.video;
                if (video) {
                    video.srcObject = null;
                }
                this.streamActive = false;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar (JPG, PNG, dll).');
                    return;
                }

                // Validate file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 10MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.capturedDataUrl = e.target.result;
                    this.photoTaken = true;
                    this.detectedText = '';
                };
                reader.onerror = () => {
                    alert('Gagal membaca file. Silakan coba lagi.');
                };
                reader.readAsDataURL(file);

                // Reset file input so same file can be selected again
                event.target.value = '';
            },

            capturePhoto() {
                const video = this.$refs.video;
                const canvas = this.$refs.canvas;

                if (!video || !canvas) {
                    this.cameraError = 'Elemen video/canvas tidak ditemukan. Coba tutup dan buka kembali.';
                    return;
                }

                if (video.readyState < 2 || video.videoWidth === 0 || video.videoHeight === 0) {
                    this.cameraError = 'Video belum siap. Tunggu beberapa detik dan coba lagi.';
                    return;
                }

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                this.capturedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
                this.stopCamera();
                this.photoTaken = true;
                this.detectedText = '';
            },

            resetAll() {
                this.stopCamera();
                this.photoTaken = false;
                this.detectedText = '';
                this.capturedDataUrl = '';
                this.isScanning = false;
                this.progressText = '';
                this.cameraError = '';
            },

            async startOcr() {
                if (!this.capturedDataUrl) {
                    alert('Tidak ada foto untuk dipindai.');
                    return;
                }

                if (!this.tesseractReady || !window.Tesseract) {
                    this.loadTesseract();
                    alert('Pustaka OCR sedang dimuat. Silakan tunggu beberapa detik dan coba lagi.');
                    return;
                }

                this.isScanning = true;
                this.progressText = 'Mempersiapkan...';

                try {
                    const result = await window.Tesseract.recognize(
                        this.capturedDataUrl,
                        'ind+eng',
                        {
                            logger: (m) => {
                                if (m.status === 'recognizing text') {
                                    this.progressText = Math.round(m.progress * 100) + '%';
                                } else if (m.status) {
                                    this.progressText = m.status;
                                }
                            }
                        }
                    );

                    const text = (result.data.text || '').trim();

                    if (!text) {
                        this.detectedText = '(Tidak ada teks terdeteksi. Pastikan foto jelas dan terbaca.)';
                        return;
                    }

                    this.detectedText = text;
                    this.analyzeText(text);
                } catch (err) {
                    console.error('OCR scan failed:', err);
                    alert('Gagal melakukan scan OCR: ' + (err.message || 'Error tidak diketahui'));
                } finally {
                    this.isScanning = false;
                }
            },

            analyzeText(text) {
                const lowerText = text.toLowerCase();
                let matchedStatus = '';

                if (/sakit|dokter|medis|kesehatan|opname|rumah\s*sakit|rs\b|diagnosis/i.test(text)) {
                    matchedStatus = 'Sakit';
                } else if (/izin|memohon|keperluan|halangan|pernikahan|keluarga|berhalangan/i.test(text)) {
                    matchedStatus = 'Izin';
                } else if (/tugas|perintah|dinas|spt\b|sppd|pelatihan|perjalanan/i.test(text)) {
                    matchedStatus = 'Dinas Luar';
                } else if (/cuti|bersalin|tahunan|melahirkan/i.test(text)) {
                    matchedStatus = 'Cuti Tahunan';
                }

                const cleanText = text.replace(/[\r\n]+/g, ' ').replace(/\s+/g, ' ').trim().substring(0, 100);
                const label = matchedStatus ? ('Surat ' + matchedStatus) : 'Surat Terdeteksi';
                const keteranganValue = 'Scan ' + label + ': ' + cleanText + '...';

                // Update Filament/Livewire form fields
                if (this.$wire) {
                    try {
                        if (matchedStatus) {
                            this.$wire.set('mountedTableActionData.status', matchedStatus);
                        }
                        this.$wire.set('mountedTableActionData.keterangan', keteranganValue);
                    } catch (wireErr) {
                        console.warn('Could not update Livewire state:', wireErr);
                        // Try alternative data path for Filament v3
                        try {
                            if (matchedStatus) {
                                this.$wire.set('data.status', matchedStatus);
                            }
                            this.$wire.set('data.keterangan', keteranganValue);
                        } catch (e) {
                            console.warn('Alternative wire path also failed:', e);
                        }
                    }
                }
            },

            destroy() {
                this.stopCamera();
            }
        }"
        x-on:livewire:navigating.window="stopCamera()"
        class="space-y-3"
    >
        {{-- Mode Selection: Camera or Upload --}}
        <div x-show="!photoTaken && !streamActive" x-transition class="flex flex-wrap gap-2">
            <button type="button"
                    @click="startCamera()"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm inline-flex items-center transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                </svg>
                Ambil Foto Kamera
            </button>
            <button type="button"
                    @click="$refs.fileInput.click()"
                    class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm inline-flex items-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Upload Foto Surat
            </button>
            {{-- capture="environment" opens native camera on mobile --}}
            <input type="file" x-ref="fileInput" class="hidden" accept="image/*" capture="environment" @change="handleFileUpload($event)" />
        </div>

        {{-- Camera Error --}}
        <div x-show="cameraError" x-transition x-cloak
             class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/30 rounded-lg text-sm text-red-700 dark:text-red-400 flex items-start gap-2">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <p class="font-bold">Kamera Tidak Tersedia</p>
                <p class="text-xs mt-0.5" x-text="cameraError"></p>
                <button type="button" @click="cameraError = ''" class="mt-1 text-xs underline hover:no-underline">Tutup</button>
            </div>
        </div>

        {{-- Live Video Stream - use x-show instead of x-if so video element stays in DOM --}}
        <div x-show="streamActive" x-transition x-cloak class="space-y-3">
            <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 dark:border-white/10 bg-black">
                <video x-ref="video"
                       class="w-full max-h-72 object-cover"
                       autoplay
                       playsinline
                       muted
                       webkit-playsinline></video>
                <div class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded font-bold animate-pulse">
                    KAMERA AKTIF
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button"
                        @click="capturePhoto()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm inline-flex items-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ambil Foto Surat
                </button>
                <button type="button"
                        @click="stopCamera()"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20 rounded-lg inline-flex items-center transition-colors">
                    Batal
                </button>
            </div>
        </div>

        {{-- Hidden canvas for capture - always in DOM --}}
        <canvas x-ref="canvas" class="hidden"></canvas>

        {{-- Photo Preview --}}
        <div x-show="photoTaken" x-transition x-cloak class="space-y-3">
            <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 dark:border-white/10 bg-gray-100 dark:bg-gray-800">
                <img :src="capturedDataUrl" class="max-w-full max-h-72 object-contain mx-auto" alt="Pratinjau Foto" />
                <div class="absolute top-2 left-2 bg-emerald-600 text-white text-xs px-2 py-1 rounded font-bold">
                    FOTO SURAT TERAMBIL
                </div>
            </div>

            {{-- Scanning Status --}}
            <div x-show="isScanning" x-transition x-cloak
                 class="p-3 bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 rounded-lg flex items-center space-x-3 text-sm text-blue-700 dark:text-blue-400">
                <svg class="animate-spin h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div class="flex-1">
                    <span class="font-bold">Menganalisis Surat Izin...</span>
                    <span x-text="progressText" class="ml-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-1.5 py-0.5 rounded font-mono"></span>
                </div>
            </div>

            {{-- Success Alert --}}
            <div x-show="detectedText && !isScanning" x-transition x-cloak
                 class="p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-lg text-xs text-emerald-700 dark:text-emerald-400 space-y-1">
                <div class="font-bold flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pindai Surat Berhasil!
                </div>
                <p class="font-mono break-words whitespace-pre-wrap" x-text="detectedText" style="max-height: 120px; overflow-y: auto;"></p>
            </div>

            {{-- Photo action buttons --}}
            <div x-show="!isScanning" class="flex flex-wrap gap-2">
                <button type="button"
                        @click="startOcr()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm inline-flex items-center transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Mulai Pindai Teks (OCR)
                </button>
                <button type="button"
                        @click="resetAll()"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20 rounded-lg inline-flex items-center transition-colors">
                    Foto Ulang / Ganti
                </button>
            </div>
        </div>

    </div>
</x-dynamic-component>

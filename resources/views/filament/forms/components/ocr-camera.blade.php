<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="ocrCamera" class="space-y-3">

        {{-- Mode Selection: Camera or Upload --}}
        <template x-if="!photoTaken && !streamActive">
            <div class="flex flex-wrap gap-2">
                <button type="button"
                        @click="startCamera()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm inline-flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    Ambil Foto Kamera
                </button>
                <button type="button"
                        @click="$refs.fileInput.click()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm inline-flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    Upload Foto Surat
                </button>
                <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="handleFileUpload($event)" />
            </div>
        </template>

        {{-- Camera Error --}}
        <template x-if="cameraError">
            <div class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/30 rounded-lg text-sm text-red-700 dark:text-red-400 flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div>
                    <p class="font-bold">Kamera Tidak Tersedia</p>
                    <p class="text-xs mt-0.5" x-text="cameraError"></p>
                    <button type="button" @click="cameraError = ''" class="mt-1 text-xs underline hover:no-underline">Tutup</button>
                </div>
            </div>
        </template>

        {{-- Live Video Stream --}}
        <template x-if="streamActive">
            <div class="space-y-3">
                <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 dark:border-white/10 bg-black">
                    <video x-ref="video" class="w-full max-h-64 object-cover" autoplay playsinline></video>
                    <div class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded font-bold animate-pulse">
                        KAMERA AKTIF
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button"
                            @click="capturePhoto()"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm inline-flex items-center transition-colors">
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
        </template>

        {{-- Hidden canvas for capture --}}
        <canvas x-ref="canvas" class="hidden"></canvas>

        {{-- Photo Preview --}}
        <template x-if="photoTaken">
            <div class="space-y-3">
                <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 dark:border-white/10 bg-gray-100 dark:bg-gray-800">
                    <img :src="capturedDataUrl" class="max-w-full max-h-64 object-contain mx-auto" alt="Pratinjau Foto" />
                    <div class="absolute top-2 left-2 bg-emerald-600 text-white text-xs px-2 py-1 rounded font-bold">
                        FOTO SURAT TERAMBIL
                    </div>
                </div>

                {{-- Scanning Status --}}
                <template x-if="isScanning">
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 rounded-lg flex items-center space-x-3 text-sm text-blue-700 dark:text-blue-400">
                        <svg class="animate-spin h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="flex-1">
                            <span class="font-bold">Menganalisis Surat Izin...</span>
                            <span x-text="progressText" class="ml-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-1.5 py-0.5 rounded font-mono"></span>
                        </div>
                    </div>
                </template>

                {{-- Success Alert --}}
                <template x-if="detectedText && !isScanning">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-lg text-xs text-emerald-700 dark:text-emerald-400 space-y-1">
                        <div class="font-bold flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pindai Surat Berhasil!
                        </div>
                        <p class="font-mono truncate" x-text="detectedText"></p>
                    </div>
                </template>

                {{-- Photo action buttons --}}
                <div class="flex flex-wrap gap-2">
                    <template x-if="!isScanning">
                        <button type="button"
                                @click="startOcr()"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm inline-flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Mulai Pindai Teks (OCR)
                        </button>
                    </template>
                    <template x-if="!isScanning">
                        <button type="button"
                                @click="resetAll()"
                                class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-white/10 dark:text-gray-200 dark:hover:bg-white/20 rounded-lg inline-flex items-center transition-colors">
                            Foto Ulang / Ganti
                        </button>
                    </template>
                </div>
            </div>
        </template>

    </div>

    @script
    <script>
        Alpine.data('ocrCamera', () => ({
            streamActive: false,
            photoTaken: false,
            isScanning: false,
            progressText: '',
            stream: null,
            detectedText: '',
            capturedDataUrl: '',
            cameraError: '',
            tesseractLoaded: false,

            init() {
                this.loadTesseract();
            },

            loadTesseract() {
                if (typeof window.Tesseract !== 'undefined') {
                    this.tesseractLoaded = true;
                    return;
                }
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js';
                s.onload = () => { this.tesseractLoaded = true; };
                document.head.appendChild(s);
            },

            async startCamera() {
                this.cameraError = '';

                // Check if getUserMedia is available
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    this.cameraError = 'Browser tidak mendukung akses kamera. Gunakan opsi "Upload Foto Surat" sebagai alternatif.';
                    return;
                }

                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        }
                    });

                    this.streamActive = true;
                    this.$nextTick(() => {
                        if (this.$refs.video) {
                            this.$refs.video.srcObject = this.stream;
                        }
                    });
                } catch (err) {
                    console.error('Gagal mengakses kamera:', err);
                    if (err.name === 'NotAllowedError') {
                        this.cameraError = 'Izin kamera ditolak oleh browser. Aktifkan izin kamera di pengaturan browser, atau gunakan "Upload Foto Surat".';
                    } else if (err.name === 'NotFoundError') {
                        this.cameraError = 'Kamera tidak ditemukan di perangkat ini. Gunakan "Upload Foto Surat" sebagai alternatif.';
                    } else {
                        this.cameraError = 'Gagal mengakses kamera: ' + err.message + '. Gunakan "Upload Foto Surat" sebagai alternatif.';
                    }
                }
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
                this.streamActive = false;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.capturedDataUrl = e.target.result;
                    this.photoTaken = true;
                    this.detectedText = '';
                };
                reader.readAsDataURL(file);

                // Reset file input so same file can be selected again
                event.target.value = '';
            },

            capturePhoto() {
                const video = this.$refs.video;
                const canvas = this.$refs.canvas;
                if (!video || !canvas) return;

                // Make sure video actually has frames
                if (video.videoWidth === 0 || video.videoHeight === 0) {
                    this.cameraError = 'Video belum siap. Tunggu beberapa detik dan coba lagi.';
                    return;
                }

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                this.capturedDataUrl = canvas.toDataURL('image/jpeg', 0.85);
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

                if (!this.tesseractLoaded || typeof window.Tesseract === 'undefined') {
                    alert('Pustaka OCR sedang dimuat. Silakan coba lagi dalam 3 detik.');
                    return;
                }

                this.isScanning = true;
                this.progressText = '0%';

                try {
                    const result = await window.Tesseract.recognize(
                        this.capturedDataUrl,
                        'ind+eng',
                        {
                            logger: m => {
                                if (m.status === 'recognizing text') {
                                    this.progressText = Math.round(m.progress * 100) + '%';
                                } else {
                                    this.progressText = m.status || 'Memuat model...';
                                }
                            }
                        }
                    );

                    const text = result.data.text;
                    this.detectedText = text;
                    this.analyzeText(text);
                } catch (err) {
                    console.error('Gagal melakukan scan OCR:', err);
                    alert('Gagal melakukan scan OCR: ' + err.message);
                } finally {
                    this.isScanning = false;
                }
            },

            analyzeText(text) {
                const lowerText = text.toLowerCase();
                let matchedStatus = '';

                if (lowerText.includes('sakit') || lowerText.includes('dokter') || lowerText.includes('medis') || lowerText.includes('kesehatan') || lowerText.includes('opname')) {
                    matchedStatus = 'Sakit';
                } else if (lowerText.includes('izin') || lowerText.includes('memohon') || lowerText.includes('keperluan') || lowerText.includes('halangan') || lowerText.includes('pernikahan') || lowerText.includes('keluarga')) {
                    matchedStatus = 'Izin';
                } else if (lowerText.includes('tugas') || lowerText.includes('perintah') || lowerText.includes('dinas') || lowerText.includes('spt') || lowerText.includes('sppd') || lowerText.includes('pelatihan')) {
                    matchedStatus = 'Dinas Luar';
                } else if (lowerText.includes('cuti') || lowerText.includes('bersalin') || lowerText.includes('tahunan') || lowerText.includes('melahirkan')) {
                    matchedStatus = 'Cuti Tahunan';
                }

                const cleanText = text.replace(/[\r\n]+/g, ' ').trim().substring(0, 100);
                const label = matchedStatus ? ('Surat ' + matchedStatus) : 'Surat Terdeteksi';
                const keteranganValue = 'Scan ' + label + ': ' + cleanText + '...';

                if (this.$wire) {
                    if (matchedStatus) {
                        this.$wire.set('mountedTableActionData.status', matchedStatus);
                    }
                    this.$wire.set('mountedTableActionData.keterangan', keteranganValue);
                }
            },

            destroy() {
                this.stopCamera();
            }
        }));
    </script>
    @endscript
</x-dynamic-component>

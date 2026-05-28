<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Kamera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #5d87ff; }
        .camera-container {
            position: relative;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            border: 4px solid #fff;
        }
        video {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transform: scaleX(-1); /* Mirror effect */
        }
        canvas { display: none; }
    </style>
</head>
<body class="flex justify-center min-h-screen items-center p-4">

    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl overflow-hidden">
        <div class="bg-blue-400 p-4 text-center text-white font-semibold">
            <span id="current-date">Kamis, 22 Januari 2025 | 08:31:10</span>
        </div>

        <div class="p-6 flex flex-col items-center">
            <h2 class="text-3xl font-bold text-gray-800">Absen</h2>
            <p class="text-gray-500 text-center text-sm mb-6 mt-2">
                Pastikan pencahayaan dapat menampilkan wajah anda dengan jelas
            </p>

            <div class="camera-container mb-6 shadow-lg">
                <video id="video" autoplay playsinline></video>
                
                <button id="capture-btn" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white w-12 h-12 rounded-full flex items-center justify-center shadow-md active:scale-95 transition-transform">
                    <i class="fa-solid fa-camera text-gray-800 text-xl"></i>
                </button>
            </div>

            <canvas id="canvas"></canvas>
            <img id="photo-preview" class="hidden rounded-2xl w-full mb-6 border-4 border-white shadow-lg">

            <div class="bg-green-100 border border-green-200 rounded-full py-2 px-6 flex items-start gap-2 shadow-sm">
                <i class="fa-solid fa-location-dot text-green-600 mt-1"></i>
                <div class="text-[10px] leading-tight text-gray-700">
                    <span class="font-bold block">Lokasi</span>
                    <span id="location-text">Mencari lokasi...</span>
                </div>
            </div>

            <button id="submit-absensi" class="hidden mt-6 w-full bg-blue-500 text-white font-bold py-3 rounded-2xl shadow-lg active:scale-95 transition-transform">
                Kirim Absensi
            </button>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const photoPreview = document.getElementById('photo-preview');
        const submitBtn = document.getElementById('submit-absensi');
        const locationText = document.getElementById('location-text');

        // 1. Akses Kamera
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
                video.srcObject = stream;
            } catch (err) {
                alert("Gagal mengakses kamera: " + err.message);
            }
        }

        // 2. Ambil Foto
        captureBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            
            // Mirror flip for saving
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const data = canvas.toDataURL('image/png');
            photoPreview.src = data;
            
            // UI Toggle
            video.classList.add('hidden');
            captureBtn.classList.add('hidden');
            photoPreview.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
            
            // Stop stream to save battery
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        });

        // 3. Ambil Lokasi & Reverse Geocoding (Nominatim)
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                        const data = await response.json();
                        locationText.innerText = data.display_name;
                    } catch (err) {
                        locationText.innerText = `${lat}, ${lon}`;
                    }
                }, () => {
                    locationText.innerText = "Gagal mendapatkan lokasi. Pastikan GPS aktif.";
                });
            }
        }

        // 4. Jam Realtime
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID');
            document.getElementById('current-date').innerText = `${dateStr} | ${timeStr}`;
        }

        // Jalankan saat load
        window.onload = () => {
            startCamera();
            getLocation();
            setInterval(updateClock, 1000);
        };

        // 5. Kirim data ke Laravel
        submitBtn.addEventListener('click', () => {
            const imageData = photoPreview.src;
            const location = locationText.innerText;

            // Gunakan fetch atau form submission untuk mengirim imageData (Base64) ke Controller
            console.log("Mengirim Absensi...");
            alert("Absensi berhasil dikirim!");
            window.location.href = "/dashboard";
        });
    </script>
</body>
</html>
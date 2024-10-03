<?php layout('app'); ?>

<?php section('title'); ?>
    Attendance Scanner
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-2  fs-1">
                <span id="success-message" class="text-success" hidden>
                    Attendance captured successfully
                </span>
                <span id="error-message" class="text-danger" hidden>
                    Error capturing attendance
                </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title text-center">
                        <h3>Attendance</h3>
                    </div>

                    <div id="videoWrapper">
                        <video id="video" width="100%" height="auto" autoplay></video>
                    </div>
                    
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endsection(); ?>

<?php section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.3.1/dist/jsQR.js"></script>
<script>
    const video = document.getElementById("video");
    const canvasElement = document.getElementById("canvas");
    const canvas = canvasElement.getContext("2d");
    const errorMessage = document.getElementById("error-message");
    const videoWrapper = document.getElementById("videoWrapper");

    const successMessage = document.getElementById("success-message");

    let videoStream = null;

    // Check if navigator.mediaDevices is available
    if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
        videoWrapper.style.display = 'none';
        errorMessage.style.display = 'block';
        console.error("Media devices API is not available.");
    } else {
        // Function to start video stream from the camera
        function startVideo(deviceId = null) {
            const constraints = deviceId ? { video: { deviceId: { exact: deviceId } } } : { video: { facingMode: "environment" } };

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    video.srcObject = stream;
                    videoStream = stream; // Store the stream to stop it later
                    video.setAttribute("playsinline", true); // Ensures video plays inline on mobile devices
                    video.play();
                    requestAnimationFrame(tick);
                });
        }

        // Function to scan video stream for QR codes
        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    
                    submitQrCode(code.data);
                
                    setTimeout(() => {
                    
                        initCameraSelection();
                    }, 5000);
                }
            }
            requestAnimationFrame(tick);
        }

        function stopVideo() {
            if (videoStream) {
                let tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
                videoStream = null;
            }
        }

        function initCameraSelection() {
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    if (videoDevices.length > 1) {
                        const externalCamera = videoDevices[1];
                        startVideo(externalCamera.deviceId);
                    } else if (videoDevices.length === 1) {
                        startVideo(videoDevices[0].deviceId);
                    } 
                })
                .catch(function(err) {
                    console.error("Error listing devices: " + err);
                });
        }

        function submitQrCode(qrCodeData) {
            const formData = new FormData();
            formData.append('qrCodeData', qrCodeData);

            fetch('/attendance', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.hidden = false;
                    setTimeout(() => {
                        successMessage.hidden = true;
                    }, 5000);
                } else {
                    errorMessage.hidden = false;
                    setTimeout(() => {
                        errorMessage.hidden = true;
                    }, 5000);
                }
            })
            .catch(error => {
                errorMessage.hidden = false;
                setTimeout(() => {
                    errorMessage.hidden = true;
                }, 5000);
            });
        }

        // Start the camera selection and QR code detection process
        initCameraSelection();
    }
</script>
<?php endsection(); ?>

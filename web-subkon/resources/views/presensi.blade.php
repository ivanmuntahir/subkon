<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Proyek</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style type="text/css">
        #my_camera, #results {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container mx-auto">
   <form method="POST" action="{{ route('presensi.capture') }}" id="presensiForm">
    @csrf
    <div class="bg-white p-6 rounded-lg mt-3 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="order-1 md:order-1">
                <h1 class="text-2xl font-bold mb-2">Presensi Proyek</h1>
                <div class="bg-gray-100 p-4 rounded-lg mt-3 shadow-lg">
                    <div class="mx-auto" id="my_camera"></div>
                    <br />
                    <div class="text-center">
                        <button type="button" class="btn btn-sm btn-primary" onclick="take_snapshot()">Ambil Foto</button>
                        <input type="hidden" name="image" class="image-tag">
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="switch_camera()">Ganti Kamera</button>
                    </div>
                </div>
            </div>
            <div class="order-2 md:order-2">
                <h1 class="text-2xl font-bold mb-2">Hasil Foto</h1>
                <div class="bg-gray-100 p-4 rounded-lg mt-3 shadow-lg">
                    <div class="p-4" id="results"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <br />
            <button type="submit" class="btn btn-success">Kirim Presensi</button>
        </div>      
    </div>
</div>

<!-- Success Notification -->
<div id="successNotification" class="fixed top-5 left-1/2 transform -translate-x-1/2 p-4 bg-green-500 text-white rounded-lg hidden">
    <p>Presensi berhasil dikirim!</p>
</div>

</form>

<script language="JavaScript">
    let isUsingFrontCamera = true;

    // Initialize the webcam
    function initialize_camera() {
        const width = window.innerWidth * 0.8; // 80% of window width
        const height = window.innerHeight * 0.5; // 50% of window height

        Webcam.set({
            width: 300,
            height: 200,
            image_format: 'jpeg',
            jpeg_quality: 90,
            constraints: {
                facingMode: isUsingFrontCamera ? "user" : "environment"
            }
        });

        Webcam.attach('#my_camera');
    }

    // Call initialize_camera on page load
    initialize_camera();

    // Capture a snapshot
    function take_snapshot() {
        Webcam.snap(function (data_uri) {
            document.querySelector(".image-tag").value = data_uri;
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '" class="rounded">';
        });
    }

    // Switch between front and back cameras
    function switch_camera() {
        isUsingFrontCamera = !isUsingFrontCamera;
        Webcam.reset();
        initialize_camera();
    }

    // Show success notification and redirect after form submission
    $('#presensiForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        
        // Show success notification
        document.getElementById('successNotification').classList.remove('hidden');
        
        // Redirect after 3 seconds (or change to your desired time)
        setTimeout(function() {
            // Redirect to another page (replace URL with the desired target)
            window.location.href = "{{ url('sandana/projects') }}"; // Replace with the desired route
        }, 3000); // Delay the redirect by 3 seconds
    });
</script>

</body>
</html>

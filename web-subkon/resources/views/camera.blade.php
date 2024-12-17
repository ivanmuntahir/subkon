<!DOCTYPE html>
<html>
<head>
    <title>Presensi Proyek</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style type="text/css">
        #results { padding:20px; border:1px solid; width: 296px; height: 234px;}
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Silahkan Lakukan Presensi</h1>

    <form method="POST" action="{{ route('presensi.capture') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
            	<input type="button" class="btn btn-sm btn-primary" id="open_camera" value ="Open Camera"><br/>
                <div id="my_camera" class="d-none"></div>
                <br/>
                <input type=button value="Foto" onClick="take_snapshot()">
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6">
                <div id="results"></div>
            </div>
            <div class="col-md-12 text-center">
                <br/>
                <button class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>

<script language="JavaScript">
    // $("#open_camera").click(function() {
    //     $("#my_camera").removeClass('d-none');
    //     $("#take_snap").removeClass('d-none');

        
    // });
    Webcam.set({
            width: 250,
            height: 190,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
    Webcam.attach('#my_camera');

    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>
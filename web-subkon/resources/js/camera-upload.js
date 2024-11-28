document.addEventListener("DOMContentLoaded", function () {
    Webcam.set({
        width: 490,
        height: 350,
        image_format: "jpeg",
        jpeg_quality: 90,
        constraints: {
            facingMode: "environment", // Use "user" for front camera
        },
    });

    Webcam.attach("#my_camera");

    window.take_snapshot = function () {
        Webcam.snap(function (data_uri) {
            document.querySelector(".image-tag").value = data_uri;
            document.getElementById("results").innerHTML =
                '<img src="' + data_uri + '"/>';

            // Send data to the server
            fetch("/upload-photo", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ image: data_uri }),
            })
                .then((response) => response.json())
                .then((data) => {
                    alert("Photo saved successfully!");
                })
                .catch((error) => console.error("Error:", error));
        });
    };
});

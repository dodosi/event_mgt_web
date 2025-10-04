<?php
include '../db.php';
include '../header.php';
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4">📱 Scan QR Code to Check-In</h2>

    <div id="qr-reader" style="width:100%; max-width:400px; margin:auto;"></div>

    <div id="scan-result" class="mt-4" style="display:none;">
        <h4>Visitor Info</h4>
        <p><strong>Name:</strong> <span id="visitor-name"></span></p>
        <p><strong>Email:</strong> <span id="visitor-email"></span></p>
        <p><strong>Visitor Type:</strong> <span id="visitor-type"></span></p>
        <p><strong>Event:</strong> <span id="event-name"></span></p>
        <p id="checkin-msg"></p>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    console.log("Scanned:", decodedText);

    $.post('attendance_ajax.php', { ticket_code: decodedText }, function(response){
        let res = JSON.parse(response);
        $('#scan-result').show();
        $('#visitor-name').text(res.visitor_name);
        $('#visitor-email').text(res.visitor_email);
        $('#visitor-type').text(res.visitor_type);
        $('#event-name').text(res.event_name);
        $('#checkin-msg').html(res.message);
    });
}

function onScanError(errorMessage) {
    console.warn("QR Scan Error:", errorMessage);
}

// Initialize scanner
if (Html5Qrcode.getCameras) {
    Html5Qrcode.getCameras().then(devices => {
        if(devices && devices.length){
            let cameraId = devices[0].id;
            const html5QrcodeScanner = new Html5Qrcode("qr-reader");
            html5QrcodeScanner.start(
                cameraId, 
                { fps: 10, qrbox: 250 },
                onScanSuccess,
                onScanError
            ).catch(err => console.error("Unable to start camera:", err));
        } else {
            document.getElementById('qr-reader').innerHTML = "<p class='text-danger'>No camera found.</p>";
        }
    }).catch(err => {
        console.error("Error getting cameras:", err);
        document.getElementById('qr-reader').innerHTML = "<p class='text-danger'>Cannot access camera. Check permissions.</p>";
    });
} else {
    document.getElementById('qr-reader').innerHTML = "<p class='text-danger'>Your browser does not support camera access.</p>";
}
</script>

<?php include '../footer.php'; ?>

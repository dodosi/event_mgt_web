<?php
session_start();
include '../db.php';
include '../nav.php';

$message = '';

// Handle check-in
if(isset($_GET['checkin_id'])){
    $subscription_id = intval($_GET['checkin_id']);

    // Fetch subscription + visitor
    $visitor_query = $conn->query("SELECT s.subscription_id, v.visitor_id, e.event_id 
                                   FROM subscriptions s 
                                   JOIN visitors v ON s.visitor_id=v.visitor_id
                                   JOIN events e ON s.event_id=e.event_id
                                   WHERE s.subscription_id=$subscription_id");
    if($visitor_query && $visitor_query->num_rows > 0){
        $visitor = $visitor_query->fetch_assoc();
        // Check if already checked in
        $exists = $conn->query("SELECT * FROM attendance WHERE subscription_id={$visitor['subscription_id']} AND event_id={$visitor['event_id']}");
        if($exists->num_rows == 0){
            $conn->query("INSERT INTO attendance (subscription_id, visitor_id, event_id, method) VALUES ({$visitor['subscription_id']}, {$visitor['visitor_id']}, {$visitor['event_id']}, 'Manual')");
            $message = "<div class='alert alert-success'>✅ Checked in successfully!</div>";
        } else {
            $message = "<div class='alert alert-info'>ℹ️ Visitor already checked in.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Visitor not found.</div>";
    }
}

// Fetch all subscriptions
$subs = $conn->query("
    SELECT s.subscription_id, s.ticket_code, s.registered_at, v.first_name, v.last_name, v.email, v.visitor_type, v.status, e.event_name
    FROM subscriptions s
    JOIN visitors v ON s.visitor_id=v.visitor_id
    JOIN events e ON s.event_id=e.event_id
    ORDER BY s.registered_at DESC
");
?>
<br/><br/><br/>
<div class="container mt-5">
    <h2 class="text-primary mb-4">📝 Manual Check-In</h2>
    <?= $message ?>
    
    <table class="table table-striped table-bordered" id="checkinTable">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Visitor Name</th>
                <th>Email</th>
                <th>Visitor Type</th>
                <th>Event</th>
                <th>Ticket</th>
                <th>Registered</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $subs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['subscription_id'] ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['visitor_type']) ?></td>
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= htmlspecialchars($row['ticket_code']) ?></td>
                <td><?= date("d M Y H:i", strtotime($row['registered_at'])) ?></td>
                <td>
                    <?php
                        $att = $conn->query("SELECT * FROM attendance WHERE subscription_id={$row['subscription_id']} AND event_id=(SELECT event_id FROM subscriptions WHERE subscription_id={$row['subscription_id']})");
                        if($att->num_rows > 0){
                            echo "<span class='badge bg-success'>Checked In</span>";
                        } else {
                            echo "<span class='badge bg-secondary'>Pending</span>";
                        }
                    ?>
                </td>
                <td>
                    <?php if($att->num_rows === 0): ?>
                    <a href="?checkin_id=<?= $row['subscription_id'] ?>" class="btn btn-sm btn-primary">Check In</a>
                    <?php else: ?>
                    <button class="btn btn-sm btn-success" disabled>Checked In</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Include DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

 <script>
$(document).ready(function(){
    // Generate timestamp for filename
    const timestamp = new Date().toISOString().replace(/[:.-]/g, '_'); // e.g., 2025-10-15T09_45_30_000Z
    const baseFilename = 'EventCheckin_' + timestamp;

    $('#checkinTable').DataTable({
        pageLength: 10,
        lengthMenu: [5,10,25,50,100],
        order:[[6,'desc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy Table',
                title: 'Event Check-In Data',
                messageTop: 'List of all check-ins with details',
                messageBottom: 'Generated by EventEase System',
                filename: baseFilename
            },
            {
                extend: 'csv',
                text: 'Export CSV',
                title: 'Event Check-In Data',
                messageTop: 'List of all check-ins with details',
                messageBottom: 'Generated by EventEase System',
                filename: baseFilename
            },
            {
                extend: 'excel',
                text: 'Export Excel',
                title: 'Event Check-In Data',
                messageTop: 'List of all check-ins with details',
                messageBottom: 'Generated by EventEase System',
                filename: baseFilename
            },
            {
                extend: 'pdf',
                text: 'Export PDF',
                title: 'Event Check-In Data',
                messageTop: 'List of all check-ins with details',
                messageBottom: 'Generated by EventEase System',
                filename: baseFilename,
                customize: function (doc) {
                    doc.styles.title = { alignment: 'center', fontSize: 14 };
                    doc.styles.tableHeader.alignment = 'center';
                }
            },
            {
                extend: 'print',
                text: 'Print Table',
                title: 'Event Check-In Data',
                messageTop: 'List of all check-ins with details',
                messageBottom: 'Generated by EventEase System',
                filename: baseFilename
            }
        ]
    });
});
</script>



<?php include '../footer.php'; ?>

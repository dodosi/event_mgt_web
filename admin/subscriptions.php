<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
include '../header.php';

// Fetch subscriptions with visitor info
$sql = "SELECT 
            s.subscription_id,
            s.ticket_code,
            s.registered_at,
            v.visitor_id,
            v.first_name,
            v.middle_name,
            v.last_name,
            v.email,
            v.visitor_type,
            v.status,
            e.event_id,
            e.event_name
        FROM subscriptions s
        JOIN visitors v ON s.visitor_id = v.visitor_id
        JOIN events e ON s.event_id = e.event_id
        ORDER BY s.registered_at DESC";

$result = $conn->query($sql);
if(!$result) die("Query failed: " . $conn->error);
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4">🎫 Subscriptions</h2>
    <table class="table table-striped table-bordered" id="subscriptionsTable">
        <thead class="table-info">
            <tr>
                <th>ID</th>
                <th>Visitor Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Status</th>
                <th>Event</th>
                <th>Ticket</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['subscription_id'] ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name']?$row['middle_name'].' ':'') . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['visitor_type'] ?></td>
                <td>
                    <span class="badge <?= $row['status']=='Approved'?'bg-success':($row['status']=='Cancelled'?'bg-danger':'bg-warning text-dark') ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= $row['ticket_code'] ?></td>
                <td><?= date("d M Y H:i", strtotime($row['registered_at'])) ?></td>
                <td>
                    <a href="change_status.php?id=<?= $row['visitor_id'] ?>&action=approve" class="btn btn-sm btn-success">Approve</a>
                    <a href="change_status.php?id=<?= $row['visitor_id'] ?>&action=cancel" class="btn btn-sm btn-danger">Cancel</a>
                    <a href="edit_visitor_type.php?id=<?= $row['visitor_id'] ?>" class="btn btn-sm btn-primary">Edit Type</a>
                    <a href="../events/view.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-info">View Event</a>
                    <a href="../events/view_qr.php?visitor_id=<?= $row['visitor_id'] ?>" target="_blank" class="btn btn-sm btn-dark">View QR</a>
               </td>

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function(){
    $('#subscriptionsTable').DataTable({
        pageLength: 1, // default items per page
        lengthMenu: [5,10,25,50,100], // user-selectable
        order:[[7,"desc"]], // order by registered date
        dom: 'Bfrtip', // buttons on top
        buttons: [
            {
                extend: 'pdfHtml5',
                title: 'Subscriptions Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0,1,2,3,4,5,6,7] } // exclude Actions
            },
            {
                extend: 'excelHtml5',
                title: 'Subscriptions Report',
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            }
        ]
    });
});
</script>

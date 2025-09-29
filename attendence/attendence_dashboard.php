<?php
include '../db.php';
include '../header.php';

$attendance = $conn->query("
    SELECT a.*, v.first_name, v.last_name, v.visitor_type, e.event_name
    FROM attendance a
    JOIN visitors v ON a.visitor_id=v.visitor_id
    JOIN events e ON a.event_id=e.event_id
    ORDER BY a.check_in_time DESC
");
?>

<div class="container mt-5">
    <h2 class="text-primary mb-4">📋 Attendance Dashboard</h2>
    <table class="table table-bordered table-striped" id="attendanceTable">
        <thead class="table-info">
            <tr>
                <th>#</th>
                <th>Visitor Name</th>
                <th>Email</th>
                <th>Visitor Type</th>
                <th>Event</th>
                <th>Method</th>
                <th>Check-In Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row=$attendance->fetch_assoc()): ?>
            <tr>
                <td><?= $row['attendance_id'] ?></td>
                <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['visitor_type'] ?></td>
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= $row['method'] ?></td>
                <td><?= date("d M Y H:i", strtotime($row['check_in_time'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
    $('#attendanceTable').DataTable({
        pageLength: 10,
        lengthMenu: [5,10,25,50,100],
        order:[[6,'desc']]
    });
});
</script>

<?php include '../footer.php'; ?>

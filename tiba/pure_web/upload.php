<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <form method="POST" action="api/upload.php" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload" class="btn btn-success">
        <input type="submit" value="Upload" name="submit" class="btn btn-success">
        </form>
</div>
</body>
</html>

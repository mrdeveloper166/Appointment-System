<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// Update status
if(isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $appointment_id);
    $stmt->execute();
}

// Get statistics
$where_clause = "";
if(isset($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $where_clause = " WHERE appointment_date BETWEEN '$start_date' AND '$end_date'";
}

$total_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments" . $where_clause)->fetch_assoc()['total'];
$total_visited = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Visited'" . 
    ($where_clause ? " AND appointment_date BETWEEN '$start_date' AND '$end_date'" : ""))->fetch_assoc()['total'];
$total_non_visited = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Non-Visited'" . 
    ($where_clause ? " AND appointment_date BETWEEN '$start_date' AND '$end_date'" : ""))->fetch_assoc()['total'];

// Get all appointments
$appointments = $conn->query("SELECT * FROM appointments" . $where_clause . " ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .status-visited { 
            color: white !important; 
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-non-visited { 
            color: white !important; 
            background-color: #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .bg-purple { background-color: #6f42c1; }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .stat-card {
                margin-bottom: 15px;
            }
            .stat-card h2 {
                font-size: 1.5rem;
            }
            .stat-card h4 {
                font-size: 1.1rem;
            }
            
            /* Make table responsive */
            #appointmentsTable {
                display: block;
                width: 100%;
                overflow-x: auto;
            }
            
            .form-select {
                width: 100% !important;
                margin-top: 5px;
            }
        }
        
        /* Table improvements */
        .table {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom select styling */
        .status-select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: #fff;
            width: auto;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .date-filter {
            display: flex;
            gap: 15px;
            align-items: end;
        }
        @media (max-width: 768px) {
            .date-filter {
                flex-direction: column;
                gap: 10px;
            }
            .date-filter .form-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Admin Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-purple">
                    <h4>Total Appointments</h4>
                    <h2><?php echo $total_appointments; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-success">
                    <h4>Visited</h4>
                    <h2><?php echo $total_visited; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-danger">
                    <h4>Non-Visited</h4>
                    <h2><?php echo $total_non_visited; ?></h2>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="filter-section">
            <form method="POST" class="date-filter">
                <div class="form-group">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="dashboard.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Appointments Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Appointments List</h5>
                <div class="table-responsive">
                    <table id="appointmentsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $appointments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['patient_name']; ?></td>
                                <td><?php echo $row['select_service']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo $row['appointment_time']; ?></td>
                                <td><?php echo $row['contact_no']; ?></td>
                                <td>
                                    <span class="status-<?php echo strtolower($row['status']); ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                                            <option value="Visited" <?php echo $row['status'] == 'Visited' ? 'selected' : ''; ?>>Visited</option>
                                            <option value="Non-Visited" <?php echo $row['status'] == 'Non-Visited' ? 'selected' : ''; ?>>Non-Visited</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td>
                                    <a href="view_appointment.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#appointmentsTable').DataTable({
                order: [[3, 'desc']],
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "Search appointments:"
                }
            });

            // Date validation
            $('#start_date, #end_date').on('change', function() {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if(start_date && end_date) {
                    if(start_date > end_date) {
                        alert('End date should be greater than start date');
                        $('#end_date').val('');
                    }
                }
            });

            // Add current filter dates to the table info
            <?php if(isset($_POST['start_date']) && isset($_POST['end_date'])): ?>
            var filterInfo = $('<div class="mt-2">').html(
                '<strong>Filtered Date Range:</strong> ' +
                '<?php echo date("d/m/Y", strtotime($_POST["start_date"])); ?> to ' +
                '<?php echo date("d/m/Y", strtotime($_POST["end_date"])); ?>'
            );
            $('.dataTables_info').append(filterInfo);
            <?php endif; ?>
        });
    </script>

    <!-- Optional: Add this to show active filters -->
    <?php if(isset($_POST['start_date']) && isset($_POST['end_date'])): ?>
    <div class="alert alert-info" role="alert">
        Showing results from 
        <?php echo date("d/m/Y", strtotime($_POST['start_date'])); ?> 
        to 
        <?php echo date("d/m/Y", strtotime($_POST['end_date'])); ?>
    </div>
    <?php endif; ?>
</body>
</html>
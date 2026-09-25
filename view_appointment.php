<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

if(!isset($_GET['id'])) {
    die("Appointment ID not provided");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if(!$appointment) {
    die("Appointment not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details - <?php echo $appointment['patient_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .print-only {
                display: block;
            }
            .card {
                border: none !important;
            }
        }
        .appointment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .appointment-details {
            margin-bottom: 20px;
        }
        .detail-row {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .status-visited {
            color: white;
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .status-non-visited {
            color: white;
            background-color: #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
        }
        @media print {
            body {
                font-size: 12pt;
            }
            .container {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="appointment-header">
                    <h2 class="mb-3">Appointment Details</h2>
                    <p class="text-muted">Generated on: <?php echo date('d-m-Y H:i:s'); ?></p>
                </div>

                <div class="appointment-details">
                    <div class="row detail-row">
                        <div class="col-md-4 label">Patient Name</div>
                        <div class="col-md-8"><?php echo $appointment['patient_name']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Age</div>
                        <div class="col-md-8"><?php echo $appointment['age']; ?> years</div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Gender</div>
                        <div class="col-md-8"><?php echo $appointment['gender']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Contact Number</div>
                        <div class="col-md-8"><?php echo $appointment['contact_no']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Email ID</div>
                        <div class="col-md-8"><?php echo $appointment['email_id']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Service</div>
                        <div class="col-md-8"><?php echo $appointment['select_service']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Description</div>
                        <div class="col-md-8"><?php echo nl2br($appointment['description']); ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Appointment Date</div>
                        <div class="col-md-8"><?php echo date('d-m-Y', strtotime($appointment['appointment_date'])); ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Appointment Time</div>
                        <div class="col-md-8"><?php echo $appointment['appointment_time']; ?></div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Status</div>
                        <div class="col-md-8">
                            <span class="status-<?php echo strtolower($appointment['status']); ?>">
                                <?php echo $appointment['status']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="row detail-row">
                        <div class="col-md-4 label">Remarks</div>
                        <div class="col-md-8"><?php echo nl2br($appointment['remark']); ?></div>
                    </div>
                </div>

                <div class="text-center mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                    <button onclick="window.close()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
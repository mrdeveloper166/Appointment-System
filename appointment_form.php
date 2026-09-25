<?php
include 'db_connect.php';

function checkSlotAvailability($conn, $date, $time) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ? AND appointment_time = ?");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] == 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = false;
    $error_message = "";
    
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    
    if (!checkSlotAvailability($conn, $appointment_date, $appointment_time)) {
        $error = true;
        $error_message = "This slot is already booked for " . date('d-m-Y', strtotime($appointment_date)) . " at " . $appointment_time . ". Please choose another date or time.";
    }

    if (!$error) {
        $patient_name = $_POST['patient_name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $contact_no = $_POST['contact_no'];
        $email_id = $_POST['email_id'];
        $select_service = $_POST['select_service'];
        $description = $_POST['description'];
        $remark = $_POST['remark'];

        $sql = "INSERT INTO appointments (patient_name, age, gender, contact_no, email_id, select_service, description, appointment_date, appointment_time, remark) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssssss", 
            $patient_name,    // s - string
            $age,            // i - integer
            $gender,         // s - string
            $contact_no,     // s - string
            $email_id,       // s - string
            $select_service, // s - string
            $description,    // s - string
            $appointment_date, // s - string
            $appointment_time, // s - string
            $remark          // s - string
        );
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Appointment booked successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Slot Not Available',
                text: '$error_message'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .swal2-popup {
            font-size: 1rem;
        }
        .time-slot-booked {
            color: red;
            font-size: 0.875rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Book an Appointment</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_name" class="form-label required">Patient Name</label>
                        <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="age" class="form-label required">Age</label>
                        <input type="number" class="form-control" id="age" name="age" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label required">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_no" class="form-label required">Contact Number</label>
                        <input type="tel" class="form-control" id="contact_no" name="contact_no" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email_id" class="form-label required">Email ID</label>
                    <input type="email" class="form-control" id="email_id" name="email_id" required>
                </div>

                <div class="mb-3">
                    <label for="select_service" class="form-label required">Select Service</label>
                    <select class="form-select" id="select_service" name="select_service" required>
                        <option value="">---Select Service---</option>
                        <option value="ABDOMINAL ULTRASONOGRAPHY">ABDOMINAL ULTRASONOGRAPHY</option>
                        <option value="COLOUR DOPPLER SONOGRAPHY">COLOUR DOPPLER SONOGRAPHY</option>
                        <option value="CAROTID & PERIPHERAL VESSELS">CAROTID & PERIPHERAL VESSELS</option>
                        <option value="SMALL PART SONOGRAPHY">SMALL PART SONOGRAPHY</option>
                        <option value="THYROID">THYROID</option>
                        <option value="SCROTAL">SCROTAL</option>
                        <option value="BREAST">BREAST</option>
                        <option value="CRANIAL">CRANIAL</option>
                        <option value="EYE (B-SCAN)">EYE (B-SCAN)</option>
                        <option value="MUSCULOSKELEATAL">MUSCULOSKELEATAL</option>
                        <option value="ELASTOGRAPHY">ELASTOGRAPHY</option>
                        <option value="LIVER">LIVER</option>
                        <option value="BREAST">BREAST</option>
                        <option value="OBSTETRICS">OBSTETRICS</option>
                        <option value="ROUTINE">ROUTINE</option>
                        <option value="ANOMALY SCAN (LEVEL -II)">ANOMALY SCAN (LEVEL -II)</option>
                        <option value="3D/4D ULTRASOUND">3D/4D ULTRASOUND</option>
                        <option value="TRANSVAGINAL SONOGRAPHY">TRANSVAGINAL SONOGRAPHY</option>
                        <option value="TRANSRECTAL SONOGRAPHY">TRANSRECTAL SONOGRAPHY</option>
                        <option value="INTERVENTIONAL PROCEDURES">INTERVENTIONAL PROCEDURES</option>
                        <option value="BIOPSY">BIOPSY</option>
                        <option value="LIVER">LIVER</option>
                        <option value="RENAL">RENAL</option>
                        <option value="PROSTATE">PROSTATE</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label required">Appointment Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label required">Appointment Time</label>
                        <select class="form-select" id="appointment_time" name="appointment_time" required>
                            <option value="">Select Time</option>
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="11:00 AM">11:00 AM</option>
                            <option value="12:00 PM">12:00 PM</option>
                            <option value="01:00 PM">01:00 PM</option>
                            <option value="02:00 PM">02:00 PM</option>
                            <option value="03:00 PM">03:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                            <option value="05:00 PM">05:00 PM</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control" id="remark" name="remark" rows="2"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('appointment_date');
            const timeInput = document.getElementById('appointment_time');
            const form = document.querySelector('form');
            
            async function checkSlotAvailability() {
                const date = dateInput.value;
                const time = timeInput.value;
                
                if (date && time) {
                    try {
                        const formData = new FormData();
                        formData.append('date', date);
                        formData.append('time', time);
                        
                        const response = await fetch('check_slot.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (!data.available) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Slot Not Available',
                                text: data.message || 'This slot is already booked. Please select a different date or time.',
                                confirmButtonColor: '#3085d6'
                            });
                            timeInput.value = ''; // Reset time selection
                            return false;
                        }
                        return true;
                    } catch (error) {
                        console.error('Error checking slot availability:', error);
                        return false;
                    }
                }
                return true;
            }
            
            dateInput.addEventListener('change', checkSlotAvailability);
            timeInput.addEventListener('change', checkSlotAvailability);
            
            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                
                const isAvailable = await checkSlotAvailability();
                
                if (isAvailable) {
                    const isValid = true; // Add your other validations here
                    
                    if (isValid) {
                        this.submit();
                    }
                }
            });
        });



        // Add this JavaScript code in your existing script section
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for appointment (tomorrow)
    const dateInput = document.getElementById('appointment_date');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Format tomorrow's date as YYYY-MM-DD
    const minDate = tomorrow.toISOString().split('T')[0];
    
    // Set the minimum date attribute
    dateInput.setAttribute('min', minDate);
    
    // Add event listener to prevent past date selection
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time part for proper comparison
        
        if(selectedDate <= today) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date',
                text: 'Please select a future date for your appointment. You cannot book for today or past dates.'
            });
            this.value = ''; // Clear the invalid date
        }
    });
});
    </script>
</body>
</html>

<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "bpsms_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
require '../libs/PHPMailer/src/Exception.php';

// Proses AJAX request untuk update status dan kirim email

if (isset($_POST['mark_sent'])) {
    $id = intval($_POST['id']);

    // Ambil email pelanggan
    $query = "SELECT cl.email, CONCAT(cl.firstname, ' ', cl.lastname) AS client_name
              FROM client_maintenance cm
              JOIN client_list cl ON cm.client_id = cl.id
              WHERE cm.id = $id";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $email = $row['email'];
        $client_name = $row['client_name'];

        // Update status di database
        $update_query = "UPDATE client_maintenance SET notification_status = 'Sent' WHERE id = $id";
        if ($conn->query($update_query)) {
            // Kirim email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'aditya.122140235@student.itera.ac.id'; // Ganti dengan email Anda
                $mail->Password = 'ewoa zuha mkql onix'; // Gunakan App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your_email@gmail.com', 'Your Company Name');
                $mail->addAddress($email, $client_name);

                $mail->isHTML(true);
                $mail->Subject = 'Reminder: Maintenance Schedule';
                $mail->Body = "<h3>Halo, $client_name</h3>
                               <p>Jadwal maintenance Anda telah dikonfirmasi.</p>
                               <p>Terima kasih telah mempercayai layanan kami!</p>";

                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'Email sent and status updated!']);
                exit();
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
            exit();
        }
    }
    exit();
}
?>

<style>
    table td, table th {
        padding: 3px !important;
    }
</style>
<?php 
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d", strtotime("-30 days"));
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Client Maintenance Schedule</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-3">
                    <label for="date_start">Date Start</label>
                    <input type="date" class="form-control form-control-sm" name="date_start" value="<?php echo date("Y-m-d", strtotime($date_start)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="date_end">Date End</label>
                    <input type="date" class="form-control form-control-sm" name="date_end" value="<?php echo date("Y-m-d", strtotime($date_end)) ?>">
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-success btn-sm" type="button" id="printBTN"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </form>
        <hr>
            <div id="printable">
                <div>
                    <h4 class="text-center m-0"><?php echo $_settings->info('name') ?></h4>
                    <h3 class="text-center m-0"><b>Client Maintenance Schedule</b></h3>
                    <p class="text-center m-0">Date Between <?php echo $date_start ?> and <?php echo $date_end ?></p>
                    <hr>
                </div>
                <table class="table table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
        <tr>
            <th>#</th>
            <th>Client Name</th>
            <th>Vehicle</th>
            <th>Last Service</th>
            <th>Next Service</th>
            <th>Notification</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $qry = $conn->query("
            SELECT 
                cm.id AS id,
                cm.client_id,   
                CONCAT(cl.firstname, ' ', cl.lastname) AS client_name,
                cm.vehicle,
                cm.next_service,
                cm.notification_status,
                cm.last_service
            FROM 
                client_maintenance cm
            JOIN 
                client_list cl ON cm.client_id = cl.id
            ORDER BY 
                cm.next_service ASC;
        ");
        
        while ($row = $qry->fetch_assoc()):
        ?>
        <tr>
            <td class="text-center"><?php echo $i++; ?></td>
            <td><?php echo ucwords($row['client_name']); ?></td>
            <td><?php echo $row['vehicle']; ?></td>
            <td><?php echo $row['last_service']; ?></td>
            <td><?php echo $row['next_service']; ?></td>
            <td class="text-center">
                <?php if ($row['notification_status'] == 'Sent'): ?>
                    <span class="badge badge-success rounded-pill px-3">Sent</span>
                <?php else: ?>
                    <span class="badge badge-warning rounded-pill px-3">Pending</span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if ($row['notification_status'] == 'Pending'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="mark_sent" class="btn btn-sm btn-primary">Send</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled>Sent</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>

                    <?php if ($qry->num_rows <= 0): ?>
                    <tr>
                        <td class="text-center" colspan="6">No Data Found...</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<noscript>
    <style>
        .m-0 { margin: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .table { border-collapse: collapse; width: 100%; }
        .table tr, .table td, .table th { border: 1px solid gray; }
    </style>
</noscript>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    $(function () {
        $('#filter-form').submit(function (e) {
            e.preventDefault();
            location.href = "./?page=client_maintenance&date_start=" + $('[name="date_start"]').val() + "&date_end=" + $('[name="date_end"]').val();
        });

        $('#printBTN').click(function () {
            var rep = $('#printable').clone();
            var ns = $('noscript').clone().html();
            start_loader();
            rep.prepend(ns);
            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write(rep.html());
            nw.document.close();
            nw.print();
            setTimeout(function () {
                nw.close();
                end_loader();
            }, 500);
        });
    });
    
    $('.send-btn').click(function () {
        var button = $(this); // Tombol yang diklik
        var id = button.data('id'); // ID data

        // AJAX request
        $.ajax({
            url: '', // File yang sama (index.php)
            type: 'POST',
            data: { id: id }, // Kirim ID ke server
            dataType: 'json',
            beforeSend: function () {
                button.prop('disabled', true).text('Processing...'); // Ubah tombol saat proses
            },
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message); // Tampilkan pesan sukses
                    location.reload(); // Reload halaman setelah sukses
                } else {
                    alert(response.message); // Tampilkan error
                    button.prop('disabled', false).text('Send'); // Kembalikan tombol
                }
            },
            error: function () {
                alert('An error occurred while processing your request.');
                button.prop('disabled', false).text('Send');
            }
        });
    });
});
</script>

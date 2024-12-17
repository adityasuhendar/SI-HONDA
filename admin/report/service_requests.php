<style>
    table td, table th {
        padding: 3px !important;
    }
</style>
<?php 
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d", strtotime("-7 days"));
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Vehicle Service Requests Report</h5>
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
                <h3 class="text-center m-0"><b>Service Requests Report</b></h3>
                <p class="text-center m-0">Date Between <?php echo $date_start ?> and <?php echo $date_end ?></p>
                <hr>
            </div>
            <table class="table table-bordered">
                <colgroup>
                    <col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
						<th>Date Requested</th>
						<th>Client Name</th>
						<th>Vehicle Type</th>
						<th>Vehicle Variant</th>
						<th>Current Kilometer</th>
						<th>Service Date</th>
						<th>Service Time</th>
						<th>Keluhan</th>
						<th>Service Type</th>
						<th>Service</th>
						<th>Status</th>
                        <th>Adress</th>
                        <th>Mechanic</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT s.*,concat(c.lastname,', ', c.firstname,' ',c.middlename) as fullname from service_requests s inner join client_list c on s.client_id = c.id order by unix_timestamp(s.date_created) desc");
                    while ($row = $qry->fetch_assoc()):
                        $sids = $conn->query("SELECT meta_value FROM request_meta where request_id = '{$row['id']}' and meta_field = 'service_id'")->fetch_assoc()['meta_value'];
                        $services = $conn->query("SELECT * FROM service_list where id in ({$sids}) ");
                        $mechanic = $conn->query("SELECT * FROM mechanics_list where id = '{$row['mechanic_id']}'")->fetch_assoc();
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo ucwords($row['fullname']) ?></td>
                        <td><?php echo $row['vehicle_type'] ?></td>
                        <td><?php echo $row['vehicle_variant'] ?></td>
                        <td><?php echo $row['current_kilometer'] ?></td>
                        <td><?php echo $row['tanggal_service'] ?></td>
                        <td><?php echo $row['service_time'] ?></td>
                        <td><?php echo $row['keluhan'] ?></td>
                        <td><?php echo $row['service_type'] ?></td>
                        <td>
                            <p class="m-0 truncate-3">
                                <?php
                                $s = 0;
                                while ($srow = $services->fetch_assoc()) {
                                    $s++;
                                    if ($s != 1)
                                        echo ", ";
                                    echo $srow['service'];
                                }
                                ?>
                            </p>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] == 1): ?>
                                <span class="badge badge-primary rounded-pill px-3">Confirmed</span>
                            <?php elseif ($row['status'] == 2): ?>
                                <span class="badge badge-warning rounded-pill px-3">On-progress</span>
                            <?php elseif ($row['status'] == 3): ?>
                                <span class="badge badge-success rounded-pill px-3">Done</span>
                            <?php elseif ($row['status'] == 4): ?>
                                <span class="badge badge-danger rounded-pill px-3">Cancelled</span>
                            <?php else: ?>
                                <span class="badge badge-secondary rounded-pill px-3">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['address'] != null ? $row['address'] : 'N/A' ?></td>
                        <td><?php echo $row['mechanic_id'] != null ? $mechanic['name'] : 'N/A' ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($qry->num_rows <= 0): ?>
                    <tr>
                        <td class="text-center" colspan="9">No Data...</td>
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
<script>
    $(function () {
        $('#filter-form').submit(function (e) {
            e.preventDefault();
            location.href = "./?page=report/service_requests&date_start=" + $('[name="date_start"]').val() + "&date_end=" + $('[name="date_end"]').val();
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
</script>

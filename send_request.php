<?php 
require_once('config.php');
?>
<style>
#uni_modal .modal-footer {
    display: none;
}
span.select2-selection.select2-selection--single,
span.select2-selection.select2-selection--multiple {
    padding: 0.25rem 0.5rem;
    min-height: calc(1.5em + 0.5rem + 2px);
    height: auto !important;
    max-height: calc(3.5em + 0.5rem + 2px);
    font-size: 0.875rem;
    border-radius: 0;
}
.select2-container {
    z-index: 9999; /* Pastikan dropdown muncul di atas elemen lain */
}
.select2-container {
z-index: 1551; /* Pastikan lebih tinggi dari modal */
}

.select2-dropdown {
    z-index: 1551; /* Sesuaikan dengan modal */
    position: absolute !important;
}

#uni_modal {
    z-index: 1550; /* Modal utama */
}
</style>
<div class="container-fluid">
<form action="" id="request_form">
    <input type="hidden" name="id">
    <div class="col-12">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="vehicle_type" class="control-label">Tipe Kendaraan (contoh: Matic)</label>
                    <input type="text" name="vehicle_type" id="vehicle_type" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_variant" class="control-label">Varian Kendaraan (Contoh: BeAT)</label>
                    <input type="text" name="vehicle_variant" id="vehicle_name" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="current_kilometer" class="control-label">Kilometer Saat Ini</label>
                    <div class="input-group">
                        <input type="number" name="current_kilometer" id="current_kilometer" class="form-control form-control-sm rounded-0" placeholder="Contoh: 15000" required>
                        <span class="input-group-text">km</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal_service" class="control-label">Tanggal Service</label>
                    <input type="date" name="tanggal_service" id="service_date" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="service_time" class="control-label">Waktu Service</label>
                    <input type="time" name="service_time" id="service_time" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="service_id" class="control-label">Services</label>
                    <select name="service_id[]" id="service_id" class="form-select form-select-sm select2 rounded-0" multiple required>
                        <option disabled></option>
                        <?php 
                        $service = $conn->query("SELECT * FROM `service_list` where status = 1 and delete_flag = 0 order by `service` asc");
                        while($row = $service->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['service'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keluhan" class="control-label">Keluhan</label>
                    <input type="text" name="keluhan" id="keluhan" class="form-control form-control-sm rounded-0" required>
                </div>
                <div class="form-group">
                    <label for="service_type" class="control-label">Jenis Layanan</label>
                    <select name="service_type" id="service_type" class="form-select form-select-sm select2 rounded-0" required>
                        <option>Bengkel</option>
                        <option>Pickup</option>
                    </select>
                </div>
                <div class="form-group" id="pickup_address_container" style="display: none;">
                    <label for="address" class="control-label">Pick up Address</label>
                    <textarea rows="3" name="address" id="address" class="form-control form-control-sm rounded-0" style="resize: none"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-end mx-2">
        <div class="col-auto">
            <button class="btn btn-primary btn-sm rounded-0">Submit Request</button>
            <button class="btn btn-dark btn-sm rounded-0" type="button" data-dismiss="modal">Close</button>
        </div>
    </div>
</form>
</div>
<script>
$(document).ready(function () {
    // Inisialisasi Select2 dengan dropdown parent
    $('.select2').select2({
        placeholder: "Please Select Here",
        dropdownParent: $('#uni_modal'),
        debug: true // Aktifkan untuk memeriksa kesalahan
    });


    // Tangani perubahan pada jenis layanan
    $('#service_type').change(function () {
        var selectedValue = $(this).val(); // Ambil nilai yang dipilih
        if (selectedValue === 'Pickup') {
            $('#pickup_address_container').show(); // Tampilkan kolom alamat
            $('#pickup_address').prop('required', true); // Jadikan required
        } else {
            $('#pickup_address_container').hide(); // Sembunyikan kolom alamat
            $('#pickup_address').prop('required', false); // Hilangkan required
        }
    });

    // Tangani submit form
    $('#request_form').submit(function (e) {
        e.preventDefault();
        start_loader();
        $.ajax({
            url: 'classes/Master.php?f=save_request',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function (resp) {
                end_loader();
                if (resp.status === 'success') {
                    location.href = "./?p=my_services";
                } else if (!!resp.msg) {
                    alert_toast(resp.msg, 'error');
                } else {
                    alert_toast("An error occurred", 'error');
                }
            }
        });
    });
});
</script>

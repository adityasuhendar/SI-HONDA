<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en">
 <?php require_once('inc/header.php') ?>
<body class="">
  <script>
    start_loader()
  </script>
  <style>
    /* Menetapkan tampilan halaman secara keseluruhan */
    html, body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background-image: url('<?= validate_image($_settings->info('cover')) ?>');
      background-repeat: no-repeat;
      background-size: 200vh;
    }

    /* Flexbox untuk menempatkan form di tengah halaman */
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    /* Pengaturan card agar tampil lebih rapi dan terpusat */
    .card-container {
      width: 100%;
      max-width: 480px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      text-align: center;
      padding: 20px;
      background-color: #007bff;
      color: #fff;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    .card-body {
      padding: 30px;
    }

    /* Mengatur jarak antara form group */
    .form-group {
      margin-bottom: 1.5rem;
    }

    /* Membuat input field responsif */
    .form-control {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ddd;
      width: 100%;
    }

    .form-control:focus {
      border-color: #007bff;
      outline: none;
    }

    /* Styling untuk tombol */
    .btn-block {
      margin-top: 1.5rem;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 12px 0;
      font-size: 16px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    /* Menambahkan padding untuk kolom address */
    textarea {
      padding: 10px;
      font-size: 16px;
      width: 100%;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    /* Menyesuaikan gaya untuk kolom input password */
    .input-group {
      position: relative;
    }

    .input-group-append {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
    }

    .pass_type {
      cursor: pointer;
      color: #007bff;
    }

    /* Responsif untuk tampilan lebih kecil */
    @media (max-width: 576px) {
      .container {
        padding: 10px;
      }

      .card-container {
        padding: 10px;
      }
    }

  </style>

<div class="container">
  <div class="card card-outline card-primary card-container">
    <div class="card-header">
      <a href="./" class="text-decoration-none text-white"><b>Daftar Akun</b></a>
    </div>
    <div class="card-body">
      <form id="register-frm" action="" method="post">
        <input type="hidden" name="id">
        
        <!-- Nama Lengkap -->
        <div class="row">
          <div class="form-group col-md-6">
            <input type="text" name="firstname" id="firstname" placeholder="First Name" autofocus class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <input type="text" name="middlename" id="middlename" placeholder="Middle Name (optional)" class="form-control">
          </div>
        </div>
        
        <!-- Nama Belakang dan Gender -->
        <div class="row">
          <div class="form-group col-md-6">
            <input type="text" name="lastname" id="lastname" placeholder="Last Name" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <select name="gender" id="gender" class="form-control" required>
              <option>Laki-laki</option>
              <option>Perempuan</option>
            </select>
          </div>
        </div>
        
        <!-- Kontak dan Alamat -->
        <div class="row">
          <div class="form-group col-md-6">
            <input type="text" name="contact" id="contact" placeholder="Contact #" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <textarea name="address" id="address" rows="3" placeholder="Address" class="form-control"></textarea>
          </div>
        </div>
        
        <!-- Email -->
        <div class="form-group">
          <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
        </div>
        
        <!-- Password -->
        <div class="row">
          <div class="form-group col-md-6">
            <div class="input-group">
              <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
              <div class="input-group-append">
                <span class="input-group-text pass_type" data-type="password"><i class="fa fa-eye-slash"></i></span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-6">
            <div class="input-group">
              <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control" required>
              <div class="input-group-append">
                <span class="input-group-text pass_type" data-type="password"><i class="fa fa-eye-slash"></i></span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tombol Daftar -->
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
          </div>
        </div>
        
        <!-- Link Login -->
        <div class="row text-center">
          <div class="col-12">
            <a href="<?php echo base_url.'login.php' ?>">Sudah punya akun?</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url ?>plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    end_loader();
    $('.pass_type').click(function(){
      var type = $(this).attr('data-type')
      if(type == 'password'){
        $(this).attr('data-type','text')
        $(this).closest('.input-group').find('input').attr('type',"text")
        $(this).find('i').removeClass("fa-eye-slash").addClass("fa-eye")
      } else {
        $(this).attr('data-type','password')
        $(this).closest('.input-group').find('input').attr('type',"password")
        $(this).find('i').removeClass("fa-eye").addClass("fa-eye-slash")
      }
    })
    $('#register-frm').submit(function(e){
      e.preventDefault()
      var _this = $(this)
      $('.err-msg').remove();
      var el = $('<div>').hide()
      if($('#password').val() != $('#cpassword').val()){
        el.addClass('alert alert-danger err-msg').text('Password does not match.');
        _this.prepend(el)
        el.show('slow')
        return false;
      }
      start_loader();
      $.ajax({
        url:_base_url_+"classes/Users.php?f=save_client",
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json',
        error: err => {
          console.log(err)
          alert_toast("An error occurred",'error');
          end_loader();
        },
        success: function(resp){
          if(typeof resp =='object' && resp.status == 'success'){
            location.href = "./login.php";
          } else if(resp.status == 'failed' && !!resp.msg){   
            el.addClass("alert alert-danger err-msg").text(resp.msg)
            _this.prepend(el)
            el.show('slow')
          } else {
            alert_toast("An error occurred",'error');
            end_loader();
          }
        }
      })
    })
  })
</script>
</body>
</html>

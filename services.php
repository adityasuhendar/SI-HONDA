 <!-- Header-->
 <header class="bg-dark py-5" id="main-header">
    <div class="container h-50 d-flex align-items-end justify-content-center w-100">
        <div class="text-center text-white w-100">
            <h1 class="display-4 fw-bolder"><?php echo $_settings->info('name') ?></h1>
            <!-- <p class="lead fw-normal text-white-50 mb-0">We will take care of your vehicle</p> -->
            <div class="col-auto mt-2">
                <button class="btn btn-primary btn-lg rounded-0" id="send_request" type="button">Daftar Service</button>
            </div>
        </div>
    </div>
</header>
<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center">Service Kami</h3>
                <hr class="bg-primary opacity-100">
                <div class="form-group">
                <div class="input-group mb-3">
                    <input type="search" id="search" class="form-control" placeholder="Search Service Here" aria-label="Search Service Here" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary" id="basic-addon2"><i class="fa fa-search"></i></span>
                    </div>
                </div>
                </div>
                <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-xl-2" id="service_list">
                    <?php 
                    $services = $conn->query("SELECT * FROM `service_list` where status = 1 and delete_flag = 0 order by `service`");
                    while($row= $services->fetch_assoc()):
                        $row['description'] = strip_tags(html_entity_decode(stripslashes($row['description'])));
                    ?>
                    <a class="col item text-decoration-none text-dark view_service" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                        <div class="callout callout-primary border-primary rounded-0">
                            <dl>
                                <dt><?php echo $row['service'] ?></dt>
                                <dd class="truncate-3 text-muted lh-1"><small><?php echo $row['description'] ?></small></dd>
                            </dl>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <div id="noResult" style="display:none" class="text-center"><b>No Result</b></div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#search').on('input',function(){
            var _search = $(this).val().toLowerCase().trim()
            $('#service_list .item').each(function(){
                var _text = $(this).text().toLowerCase().trim()
                    _text = _text.replace(/\s+/g,' ')
                    console.log(_text)
                if((_text).includes(_search) == true){
                    $(this).toggle(true)
                }else{
                    $(this).toggle(false)
                }
            })
            if( $('#service_list .item:visible').length > 0){
                $('#noResult').hide('slow')
            }else{
                $('#noResult').show('slow')
            }
        })
        $('#service_list .item').hover(function(){
            $(this).find('.callout').addClass('shadow')
        })
        $('#service_list .view_service').click(function(){
            uni_modal("Service Details","view_service.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('#send_request').click(function(){
            if("<?= $_settings->userdata('id') > 0 && $_settings->userdata('login_type') == 2 ?>" == 1)
            uni_modal("Fill the Service Request Form","send_request.php",'mid-large');
            else
            alert_toast(" Please Login First.","warning");
        })

    })
    $(document).scroll(function() { 
        $('#topNavBar').removeClass('bg-transparent navbar-dark bg-primary')
        if($(window).scrollTop() === 0) {
           $('#topNavBar').addClass('navbar-dark bg-transparent')
        }else{
           $('#topNavBar').addClass('navbar-dark bg-primary')
        }
    });
    $(function(){
        $(document).trigger('scroll')
    })
</script>
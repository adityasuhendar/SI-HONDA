    <!-- Header-->
    <header class="bg-dark py-5" id="main-header">
        <div class="container h-100 d-flex align-items-end justify-content-center w-100">
            <div class="text-center text-white w-100">
                <!-- <h1 class="display-4 fw-bolder mx-5">About Us</h1> -->
            </div>
        </div>
    </header>     
    <section class="py-5">
        <div class="container">
            <div class="card rounded-0">
                <div class="card-body">
                    <?php include "about.html" ?>
                </div>
            </div>
        </div>
    </section>

    <script>
    $(document).ready(function() {
        $('#topNavBar').addClass('bg-transparent navbar-dark');
    });
    </script>
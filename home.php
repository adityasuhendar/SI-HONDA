<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT BINTANG MOTOR GISTING</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #main-header {
        background-image: url('<?php echo $_settings->info("cover"); ?>'); /* Gambar dari database */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh; /* Tinggi sesuai layar penuh */
        display: flex; /* Gunakan Flexbox */
        align-items: center; /* Vertikal: Tengah */
        justify-content: center; /* Horizontal: Tengah */
        text-align: center; /* Pastikan teks berada di tengah */
        color: white; /* Warna teks */
}

    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-dark py-5" id="main-header">
        <div class="container h-1000 d-flex align-items-end justify-content-center w-100">
            <div class="text-center text-white w-100">
                <h1 class="display-4 fw-bolder mx-6"><?php echo $_settings->info('name') ?></h1>
                <div class="col-auto mt-4">
                <a class="btn btn-transparent btn-lg" href="./?p=products">Show More</a>

                    <style>
                        .btn-transparent {
                            background-color: transparent; /* Membuat latar belakang tombol transparan */
                            color: white; /* Warna teks abu-abu */
                            border: 2px solid white; /* Border dengan warna abu-abu */
                            border-radius: 100px; /* Membuat sudut tombol menjadi rounded */
                            padding: 10px 20px; /* Padding agar tombol lebih besar */
                        }

                        .btn-transparent:hover {
                            background-color: #6c757d; /* Mengubah latar belakang menjadi abu-abu saat hover */
                            color: white; /* Mengubah warna teks menjadi putih saat hover */
                        }

                        </style>
                </div>
            </div>
        </div>
    </header>
    <!-- Section -->
    <!-- <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row row-cols-sm-1 row-cols-md-2 row-cols-xl-4">
                <?php 
                    $products = $conn->query("SELECT p.*,b.name as brand, c.category FROM `product_list` p inner join brand_list b on p.brand_id = b.id inner join `categories` c on p.category_id = c.id where p.delete_flag = 0 and p.status = 1 order by RAND() limit 4");
                    while($row= $products->fetch_assoc()):
                ?>
                    <a class="col px-1 py-2 text-decoration-none text-dark product-item" href ="./?p=products/view_product&id=<?= $row['id'] ?>">
                        <div class="card rounded-0 shadow">
                            <div class="product-img-holder overflow-hidden position-relative">
                                <img src="<?= validate_image($row['image_path']) ?>" alt="Product Image" class="img-top"/>
                                <span class="position-absolute price-tag rounded-pill bg-gradient-primary text-light px-3">
                                    <i class="fa fa-tags"></i> <b><?= number_format($row['price'],2) ?></b>
                                </span>
                            </div>
                            <div class="card-body border-top">
                                <h4 class="card-title my-0"><b><?= $row['name'] ?></b></h4><br>
                                <small class="text-muted"><?= $row['brand'] ?></small><br>
                                <small class="text-muted"><?= $row['category'] ?></small>
                                <p class="m-0 truncate-5"><?= strip_tags(html_entity_decode($row['description'])) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</body> -->
</html>

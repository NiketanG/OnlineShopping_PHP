<!doctype html>
<html lang="en">

<head>
    <title>Online Shopping Website</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="assets/css/material-kit.min.css" rel="stylesheet"/>

</head>

<body>
<?php
include 'navigation.php';
?>
<!-- Carousel Card -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class=""></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li>
    </ol>
    <div class="carousel-inner">

        <div class="carousel-item">
            <div class="page-header header-filter"
                 style="background-image: url('./assets/img/laptop_slideshow.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h1 class="title">The best from Microsoft</h1>
                            <h4>For a life less ordinary, Go beyond the traditional with Surface Laptop 2. Available Now.</h4>
                            <br>
                            <div class="buttons">
                                <a href="view_product.php?prod_id=2" class="btn btn-danger btn-lg">
                                    <i class="material-icons">shopping_cart</i> Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="page-header header-filter"
                 style="background-image: url('./assets/img/headphones_slideshow.jpg'); transform: scaleX(-1)">
                <div class="container" style="transform: scaleX(-1)">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h1 class="title">Premium Wireless Headphones</h1>
                            <h4>A new standard for on-the-go sound. Next level comfort and sound-quality to match
                                with long-lasting battery life. </h4>
                            <br>
                            <div class="buttons">
                                <a href="view_product.php?prod_id=1" class="btn btn-danger btn-lg">
                                    <i class="material-icons">shopping_cart</i> Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item active">
            <div class="page-header header-filter"
                 style="background-image: url('./assets/img/camera_slideshow.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 ml-auto mr-auto text-center">
                            <h1 class="title">Crystal Clear Sharpness</h1>
                            <h4>Pro features in a to-go package. Sony Mark II - Available Now </h4>
                            <br>
                            <div class="buttons">
                                <a href="view_product.php?prod_id=19" class="btn btn-danger btn-lg">
                                    <i class="material-icons">shopping_cart</i> Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="page-header header-filter"
                 style="background-image: url('./assets/img/powerbank_slideshow2.jpg')">
                <div class="container" >
                    <div class="row">
                        <div class="col-md-7 ml-auto text-right">
                            <h1 class="title">Get Recharged</h1>
                            <h4>10,000 mAh Powerbanks, On 20% sale. Exclusively for you.
                            </h4>
                            <br>
                            <div class="buttons">
                                <a href="view_product.php?prod_id=20" class="btn btn-danger btn-lg">
                                    <i class="material-icons">shopping_cart</i> Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <i class="material-icons">keyboard_arrow_left</i>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <i class="material-icons">keyboard_arrow_right</i>
        <span class="sr-only">Next</span>
    </a>
</div>

<br><br><br>

<!-- End Carousel Card -->
<div class="" style="background-color: white">
    <div class="section recommended-products">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto text-center">
                    <h2 class="title">Exclusive Offers</h2>
                    <h4 class="category text-muted">FOR YOU</h4>

                </div>
            </div>
            <div class="row">
                <?php
                $conn = new mysqli("localhost", "root", "", "OnlineShopping");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $stmt = 'SELECT * from offers';
                $result = $conn->query($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $prod_id = $row['prod_id'];
                        $old_price = $row['old_price'];
                        $prod_result = $conn->query('SELECT * from Products where prod_id = "' . $prod_id . '"');
                        while ($prod_rows = mysqli_fetch_assoc($prod_result)) {
                            $prod_title = $prod_rows['prod_title'];
                            $prod_image = $prod_rows['prod_images'];
                            $prod_brand = $prod_rows['prod_brand'];
                            $prod_category = $prod_rows['prod_category'];
                            $prod_price = $prod_rows['prod_price'];
                            echo '
                            <div class="col-lg-5 col-md-5 ml-auto">
                                <div class="card" style="cursor: pointer" onclick=location.href="view_product.php?prod_id=' . $prod_id . '">
                                    <img class="card-img-top" src="assets/img/Products/' . $prod_image . '/Product_Main.jpg" alt="">
                                    <div class="card-body">
                                        <h4 class="card-title">' . $prod_title . '</h4>
                                        <div class="pricing row">
                                            <h3 class="card-title ml-auto mr-auto" style="display: inline;">
                                                <sup>$</sup>' . $prod_price . '
                                            </h3>
                                            <h3 class="card-title  ml-auto mr-auto" style="display: inline;">
                                                <sup>$</sup><del>' . $old_price . '</del>
                                            </h3>
                                        </div>
                                        <p class="card-text"><small class="text-muted">Free shipping and returns</small></p>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    }
                }

                ?>
            </div>
        </div>
        <br><br>
        <hr>
        <div class="section recommended-products">
            <div class="container">
                <div class="row">
                    <h2 class="section-title col-md-8 col-lg-9">Products</h2>
                    <button style="height: 40px" class="col-lg-2 col-md-3 btn btn-rose btn-round float-right" onclick=location.href="products.php">View All&#xA0;</button>
                </div>
                <div class="row">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "OnlineShopping");
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $stmt = 'SELECT * from Products';
                    $result = $conn->query($stmt);
                    $prod_count = 0;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result) and $prod_count < 6) {
                            $prod_id = $row['prod_id'];
                            $prod_desc = $row['prod_desc'];
                            $prod_title = $row['prod_title'];
                            $prod_image = $row['prod_images'];
                            $prod_brand = $row['prod_brand'];
                            $prod_category = $row['prod_category'];
                            $prod_price = $row['prod_price'];
                            $prod_stock = $row['prod_stock'];
                            $prod_count++;
                            echo '
                                <div class="col-md-4">
                                    <div class="card card-product card-plain">
                                        <div class="card-header card-header-image">
                                            <a href="view_product.php?prod_id=' . $prod_id . '">
                                                <img src="assets/img/Products/' . $prod_image . '/Product_Main.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="card-body text-center">
                                            <h4 class="card-title">
                                                <a href="view_product.php?prod_id=' . $prod_id . '">' . $prod_title . '</a>
                                            </h4>
                                        </div>
                                        <div class="card-footer ">
                                            <div class="price-container ">
                                                <!--<span class="price price-old"> $1,430 </span>-->
                                                <span class="price price-new">&nbsp;&nbsp; $' . $prod_price . '</span>
                                            </div>
                                            <div class=" ml-auto">
                                                <button type="button" onclick=onclick=addToCart(' . $prod_id . ',' . $_SESSION['id'] . ') rel="tooltip" title="" class="btn btn-just-icon btn-link" data-original-title="Add to cart">
                                                    <i class="material-icons">shopping_cart</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        ';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php'
?>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/moment.min.js"></script>
<script src="assets/js/plugins/jasny-bootstrap.min.js" type="text/javascript"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
</body>

</html>
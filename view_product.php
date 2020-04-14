<?php
$conn = new mysqli("localhost", "root", "", "OnlineShopping");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$prod_id = $_GET['prod_id'];
$stmt = 'SELECT * from Products where prod_id = "' . $prod_id . '"';
$result = $conn->query($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <?php
    $title_row = mysqli_fetch_assoc($conn->query('SELECT prod_title from Products where prod_id = "' . $_GET['prod_id'] . '"'));
    $prod_title = $title_row['prod_title'];
    echo '<title>
            ' . $prod_title . '
        </title>';
    ?>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="assets/css/material-kit.css?v=2.2.0" rel="stylesheet"/>
    <script>
        function addToCart(prodid, userid) {
            if (userid == 0) {
                window.location.href = 'login.php?next=view_product?prod_id=' + prodid
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'view_product.php',
                    data: {
                        action: 'addToCart',
                        user_id: userid,
                        prod_id: prodid
                    },
                    success: function (data) {
                        $(".modal_addtocart").modal('show');
                        $(".modal_addtocart").on('hidden.bs.modal', function (e) {
                            location.reload();
                        });
                    }
                });
            }
        }
    </script>
</head>

<body class="product-page sidebar-collapse">
<?php
include 'navigation.php';
?>
<?php
$cart_count = mysqli_fetch_assoc($conn->query('SELECT count(*) as cart_count from cart where user_id = "' . $_SESSION['id'] . '"'));

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'addToCart') {
        $prod_count = mysqli_fetch_assoc($conn->query("SELECT count(*) as count from cart where prod_id = '" . $_POST['prod_id'] . "' and user_id = '" . $_SESSION['id'] . "'"));
        if ($prod_count['count'] == 0) {
            if (mysqli_query($conn, "INSERT INTO cart (prod_id, user_id, quantity) VALUES ('" . $_POST["prod_id"] . "','" . $_SESSION["id"] . "',1)")) {
                echo 'Added';
                exit;
            } else {
                echo mysqli_error($conn);
                exit;
            }
        } else {
            $quantity = mysqli_fetch_assoc($conn->query("SELECT quantity from cart where prod_id = '" . $_POST['prod_id'] . "' and user_id = '" . $_SESSION['id'] . "'"));
            mysqli_query($conn, "UPDATE cart set quantity = '" . ((int)$quantity['quantity'] + 1) . "' where user_id = '" . $_SESSION['id'] . "' and prod_id = '" . $prod_id . "'");
            echo 'Quantity Updated';
            exit;
        }
    } else {
        ;
    }
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $prod_title = $row['prod_title'];
        $prod_desc = $row['prod_desc'];
        $prod_image = $row['prod_images'];
        $prod_brand = $row['prod_brand'];
        $prod_category = $row['prod_category'];
        $prod_price = $row['prod_price'];

        echo '
                <div class="page-header header-filter" data-parallax="true" filter-color="" style="background-image: url(\'assets/img/Products/' . $prod_image . '/Product_5.jpg\');">
                    <div class="container">
                        <div class="row title-row">
                            <div class="col-md-4 ml-auto">
                                <button class="btn btn-white float-right" onclick=location.href="cart.php">
                                <i class="material-icons">shopping_cart</i> ' . $cart_count['cart_count'] . ' Items</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
<div class="section">
    <div class="container">
    <div class="modal modal_addtocart" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Added to Cart</h5>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
        <div class="main main-raised main-product">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="product-page1">
                            <img src="assets/img/Products/' . $prod_image . '/Product_1.jpg">
                        </div>
                        <div class="tab-pane" id="product-page2">
                            <img src="assets/img/Products/' . $prod_image . '/Product_5.jpg">
                        </div>
                        <div class="tab-pane" id="product-page3">
                            <img src="assets/img/Products/' . $prod_image . '/Product_3.jpg">
                        </div>
                        <div class="tab-pane" id="product-page4">
                            <img src="assets/img/Products/' . $prod_image . '/Product_4.jpg">
                        </div>
                        <div class="tab-pane" id="product-page5">
                            <img src="assets/img/Products/' . $prod_image . '/Product_2.jpg">
                        </div>
                        <div class="tab-pane" id="product-page6">
                            <img src="assets/img/Products/' . $prod_image . '/Product_Main.jpg">
                        </div>

                    </div>
                    <ul class="nav flexi-nav" data-tabs="tabs" id="flexiselDemo1">
                        <li class="nav-item">
                            <a href="#product-page1" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_1.jpg">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-page2" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_5.jpg">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-page3" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_3.jpg">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-page4" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_4.jpg">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-page5" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_2.jpg">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#product-page6" class="nav-link" data-toggle="tab">
                                <img src="assets/img/Products/' . $prod_image . '/Product_Main.jpg">
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-6 col-sm-6">
                    <h2 class="title">' . $prod_title . '</h2>
                    <h3 class="main-price">$' . $prod_price . '</h3>
                    <div id="accordion" role="tablist">
                        <div class="card card-collapse">
                            <div class="card-header" role="tab" id="headingOne">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="true"
                                       aria-controls="collapseOne">
                                        Description
                                        <i class="material-icons">keyboard_arrow_down</i>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <p>' . $prod_desc . '</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
            <br><br>
            <div class="row pull-right">

                <button class="btn btn-rose btn-round" onclick=addToCart(' . $prod_id . ',' . $_SESSION['id'] . ')>Add to Cart &#xA0;<i
                            class="material-icons">shopping_cart</i></button>
                            ';
    }
}
?>

</div>
</div>
</div>
</div>
<div class="features text-center">
    <div class="row">
        <div class="col-md-4">
            <div class="info">
                <div class="icon icon-info">
                    <i class="material-icons">local_shipping</i>
                </div>
                <h4 class="info-title">Fast </h4>
                <p>2 Day Delivery available for Free, on select products and select cities at no extra cost.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info">
                <div class="icon icon-success">
                    <i class="material-icons">verified_user</i>
                </div>
                <h4 class="info-title">Refundable</h4>
                <p>Don't like it? Return it. 10 Days Return and Refund policy, on All Prdocuts.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info">
                <div class="icon icon-rose">
                    <i class="material-icons">favorite</i>
                </div>
                <h4 class="info-title">Trusted</h4>
                <p>6 Quality Checks. You get exactly what you ordered. </p>
            </div>
        </div>
    </div>
</div>
<div class="related-products">
    <h3 class="title text-center">You may also be interested in:</h3>
    <div class="row">
        <?php
        $rel_stmt = "SELECT * FROM Products where prod_id != 0";

        if (strpos($prod_category, ',') != true) {
            $rel_stmt = $rel_stmt . " and prod_category like '%{$prod_category}%'";
        } else {
            $categories = explode(',', $prod_category);
            $rel_stmt = $rel_stmt . " and (prod_category like ";
            $ret = "";
            foreach ($categories as $key => $value) {
                $ret .= "'%{$value}%'";
                if (next($categories) == true) {
                    $ret .= " or prod_category like ";
                } else {
                    $ret .= ")";
                }
            }
            $rel_stmt = $rel_stmt . $ret;
        }
        $related_prods = $conn->query($rel_stmt);
        if (mysqli_num_rows($related_prods) > 0) {
            while ($related_prods_rows = mysqli_fetch_assoc($related_prods)) {
                if ($related_prods_rows['prod_id'] != $_GET['prod_id']) {
                    echo '
                    <div class="col-md-' . (12 / (mysqli_num_rows($related_prods) - 1)) . '">
                        <div class="card card-product">
                            <div class="card-header card-header-image">
                                <a href="view_product.php?prod_id=' . $related_prods_rows['prod_id'] . '">
                                    <img class="img" src="assets/img/Products/' . $related_prods_rows['prod_images'] . '/Product_Main.jpg" alt="">
                                </a>
                            </div>
                            <div class="card-body">
                                <h6 class="card-category text-rose">Trending</h6>
                                <h4 class="card-title">
                                    <a href="view_product.php?prod_id=' . $related_prods_rows['prod_id'] . '">' . $related_prods_rows['prod_title'] . '</a>
                                </h4>
                            </div>
                            <div class="card-footer justify-content-between">
                                <div class="price">
                                    <h4>$' . $related_prods_rows["prod_price"] . '</h4>
                                </div>
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
</div>
</div>
<?php
include 'footer.php';
?>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/moment.min.js"></script>
<!--	Plugin for Small Gallery in Product Page -->
<script src="assets/js/plugins/jquery.flexisel.js" type="text/javascript"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $("#flexiselDemo1").flexisel({
            visibleItems: 4,
            itemsToScroll: 1,
            animationSpeed: 400,
            enableResponsiveBreakpoints: true,
            responsiveBreakpoints: {
                portrait: {
                    changePoint: 480,
                    visibleItems: 3
                },
                landscape: {
                    changePoint: 640,
                    visibleItems: 3
                },
                tablet: {
                    changePoint: 768,
                    visibleItems: 3
                }
            }
        });
    });
</script>
</body>

</html>
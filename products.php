<?php
$conn = new mysqli("localhost", "root", "", "OnlineShopping");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$priceMin = mysqli_fetch_assoc($conn->query('SELECT MIN(prod_price) as MinPrice from Products'))['MinPrice'];
$priceMax = mysqli_fetch_assoc($conn->query('SELECT MAX(prod_price) as MaxPrice from Products'))['MaxPrice'];;
$stmt = 'SELECT * from Products where prod_id != 0';
if (isset($_GET['category'])) {
    if (strpos($_GET['category'], ',') != true) {
        $stmt = $stmt . " and prod_category like '%{$_GET['category']}%'";
    } else {
        $categories = explode(',', $_GET['category']);
        $stmt = $stmt . " and (prod_category like ";
        $ret = "";
        foreach ($categories as $key => $value) {
            $ret .= "'%{$value}%'";
            if (next($categories) == true) {
                $ret .= " or prod_category like ";
            } else {
                $ret .= ")";
            }
        }
        $stmt = $stmt . $ret;
    }
}
if (isset($_GET['brand'])) {
    if (strpos($_GET['brand'], ',') != true) {
        $stmt = $stmt . " and prod_brand like '%{$_GET['brand']}%'";
    } else {
        $brands = explode(',', $_GET['brand']);
        $brands_str = implode("','", $brands);
        $stmt = $stmt . " and prod_brand IN ('{$brands_str}')";
    }

}
if (isset($_GET['sort'])) {
    $stmt = $stmt . ' order by prod_price';
}
if (isset($_GET['range'])) {
    $range = explode(',', $_GET['range']);
    $stmt = $stmt . " and prod_price between {$range[0]} AND {$range[1]}";
}

$result = $conn->query($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        Products
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="assets/css/material-kit.css?v=2.2.0" rel="stylesheet"/>
</head>

<body class="ecommerce-page sidebar-collapse">
<?php
include 'navigation.php';
?>
<div class="page-header header-filter header-small" data-parallax="true"
     style="background-image: url('assets/img/examples/clark-street-merc.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <div class="brand">
                    <h1 class="title">Products</h1>
                    <h4>Wide Range of Exclusive Products</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main main-raised">
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-2">
                            <p class="text-muted">Brand: </p>
                        </div>
                        <div class="col-md-3">
                            <select class="selectpicker" id="brandPicker" multiple data-style="select-with-transition"
                                    title="Select Brand"
                                    data-size="7">
                                <option disabled>Choose Brand</option>
                                <?php
                                $get_brands = $conn->query("SELECT distinct(prod_brand) from Products");
                                if (mysqli_num_rows($get_brands)) {
                                    while ($brands = mysqli_fetch_assoc($get_brands)) {
                                        echo '
                            <option value="' . strtolower($brands['prod_brand']) . '">' . $brands['prod_brand'] . '</option>
                            ';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted">Category: </p>
                        </div>
                        <div class="col-md-3">
                            <select class="selectpicker align-middle" id="categoryPicker" multiple
                                    data-style="select-with-transition"
                                    title="Select Category"
                                    data-size="7">
                                <option disabled>Choose Category</option>
                                <?php
                                $prod_categories = "";
                                $get_category = $conn->query("SELECT distinct(prod_category) from Products");
                                if (mysqli_num_rows($get_category)) {
                                    while ($categories = mysqli_fetch_assoc($get_category)) {

                                        $prod_categories = $prod_categories . ", " . $categories['prod_category'];
                                    }
                                    $categories_arr = explode(', ', $prod_categories);
                                }
                                array_shift($categories_arr);
                                $categories_arr = array_unique($categories_arr);
                                foreach ($categories_arr as &$cat) {
                                    echo '
                            <option value="' . strtolower($cat) . '">' . $cat . '</option>
                            ';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="ml-auto col-md-1.5">
                    <div class="row">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="sortPrice" class="form-check-input" type="checkbox" value="">
                                Sort by Price
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <br>
                    <p class="text-muted">Price Range: </p>
                    <div class="row">
                        <div class="col-md-6">
                            <label id="price-left">$<?php echo $priceMin; ?></label>
                        </div>
                        <div class="col-md-6 float-right text-right">
                            <label id="price-right">$<?php echo $priceMax; ?></label>
                        </div>
                    </div>
                    <div id="slider_Pricerange" class="slider slider-rose"></div>
                </div>
                <div class="col-md-12 text-center">
                    <button class="btn btn-rose btn-round" onclick='filter()'>Apply Filters</button>
                    <button class="btn btn-primary btn-round btn-link" onclick='clearFilter()'>Clear Filters
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>

            <?php
            $products_title = 'All Products';
            if (isset($_GET['category'])) {

                $products_title = $products_title . " in " . strtoupper($_GET['category']);
            }
            if (isset($_GET['brand'])) {
                $products_title = $products_title . " from " . strtoupper($_GET['brand']);
            }
            if (isset($_GET['range'])) {
                $products_title = $products_title . " between $" . explode(',', $_GET['range'])[0] . " and $" . explode(',', $_GET['range'])[1];
            }
            echo '<h2 class="section-title">' . $products_title . '</h2>';
            ?>

            <div class="row">
                <?php
                $result = $conn->query($stmt);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $prod_id = $row['prod_id'];
                        $prod_desc = $row['prod_desc'];
                        $prod_title = $row['prod_title'];
                        $prod_image = $row['prod_images'];
                        $prod_brand = $row['prod_brand'];
                        $prod_category = $row['prod_category'];
                        $prod_price = $row['prod_price'];
                        $prod_stock = $row['prod_stock'];
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
    </div><!-- section -->
</div>
<?php
include 'footer.php'
?>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/moment.min.js"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="assets/js/plugins/bootstrap-selectpicker.js" type="text/javascript"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
<script>
    var slider = document.getElementById("slider_Pricerange");
    var brands = [];
    var categories = [];
    var min_value = <?php echo $priceMin; ?>;
    var max_value = <?php echo $priceMax; ?>;
    var category = "<?php
        if (isset($_GET['category'])) {
            echo $_GET['category'];
        } else {
            echo 'null';
        }
        ?>";
    var brand = "<?php
        if (isset($_GET['brand'])) {
            echo $_GET['brand'];
        } else {
            echo 'null';
        }
        ?>";
    var range = "<?php
        if (isset($_GET['range'])) {
            echo $_GET['range'];
        } else {
            echo 'null';
        }
        ?>";
    function addToCart(prodid, userid) {
        if (userid == 0){
            window.location.href='login.php?next=products'
        } else {
            $.ajax({
                type: 'POST',
                data: {
                    action: 'addToCart',
                    user_id: userid,
                    prod_id: prodid
                },
                success: function (data) {
                    alert(data);
                    location.reload();
                }
            });
        }
    }

    $(document).ready(function () {
        if (brand != "null") {
            if (brand.split(',').length > 1) {
                $("#brandPicker").selectpicker('val', brand.split(','));
            } else {
                $("#brandPicker").selectpicker('val', brand);
            }
        }

        if (category != "null") {
            if (category.split(',').length > 1) {
                $("#categoryPicker").selectpicker('val', category.split(','));
            } else {
                $("#categoryPicker").selectpicker('val', category);
            }
        }

        noUiSlider.create(slider, {
            start: [min_value, max_value],
            connect: true,
            range: {
                min: min_value,
                max: max_value
            }
        });

        if (range != "null") {
            slider.noUiSlider.set(range.split(','));

        }

        let limitFieldMin = document.getElementById('price-left');
        let limitFieldMax = document.getElementById('price-right');

        slider.noUiSlider.on('update', function (values, handle) {
            if (handle) {
                limitFieldMax.innerHTML = "$" + Math.round(values[handle]);
            } else {
                limitFieldMin.innerHTML = "$" + Math.round(values[handle]);
            }
        });
    });

    function clearFilter() {
        window.location.href = 'products.php';
    }

    function filter() {
        var min = parseInt(slider.noUiSlider.get()[0]);
        var max = parseInt(slider.noUiSlider.get()[1]);
        brands = $("#brandPicker").selectpicker('val');
        categories = $("#categoryPicker").selectpicker('val');
        var location_params = "";
        if (brands.length > 0) {
            if (location_params == "") {
                location_params += "?brand=" + brands.join(',');
            } else {
                location_params += "&brand=" + brands.join(',');
            }
        }
        if (categories.length > 0) {
            if (location_params == "") {
                location_params += "?category=" + categories.join(',');
            } else {
                location_params += "&category=" + categories.join(',');
            }
        }
        if (min != min_value || max != max_value) {
            if (location_params == "") {
                location_params += "?range=" + min + "," + max;
            } else {
                location_params += "&range=" + min + "," + max;
            }
        }
        if ($(".form-check-input").is(":checked")){
            if (location_params == "") {
                location_params += "?sort=price";
            } else {
                location_params += "&sort=price";
            }
        }
        var location = 'products.php' + location_params;
        window.location.href = location;
    }
</script>
</body>

</html>

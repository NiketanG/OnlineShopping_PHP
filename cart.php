<!doctype html>
<html lang="en">
<head>
    <title>Cart</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="assets/css/material-kit.min.css?v=2.2.0" rel="stylesheet"/>
<script src="assets/js/core/jquery.min.js" type="text/javascript"></script>

</head>

<body class="shopping-cart sidebar-collapse">
<?php
include 'navigation.php';
$conn = new mysqli("localhost", "root", "", "OnlineShopping");
?>
<?php
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = 'SELECT * from cart';
$result = $conn->query($stmt);
$total = 0;

if (isset($_POST['action'])) {
    if (strcmp($_POST['action'],'delete') == 0) {
        $delete_query = $conn->query('DELETE FROM cart where user_id = "' . $_SESSION['id'] . '" and prod_id = "' . $_POST['prod_id'] . '"');
        echo "Deleted";
        header("Refresh:0");
        exit;
    } else {
        ;
    }
}

?>
<div class="modal modal_deleted" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deleted from Cart</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="page-header header-filter header-small" data-parallax="true"
     style="background-image: url('assets/img/bg3.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <div class="brand text-center">
                    <h1>Cart</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main main-raised">

    <div class="container">
        <div class="card card-plain">

            <div class="card-body">
                <br>

                <h3 class="card-title text-center">Shopping Cart</h3>
                <br>
                <div class="table-responsive">
                    <table class="table table-shopping">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th>Product</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                            <tbody>
                            <?php

                            if (mysqli_num_rows($result) > 0){
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $prod_id = $row['prod_id'];
                                    $user_id = $row['user_id'];
                                    $quantity = $row['quantity'];
                                    $products = $conn->query("SELECT * FROM Products where prod_id = ". $prod_id .";");
                                    if (mysqli_num_rows($products) > 0) {
                                        while ($prod_row = mysqli_fetch_assoc($products)) {
                                            $prod_title = $prod_row['prod_title'];
                                            $prod_price = $prod_row['prod_price'];
                                            $prod_brand = $prod_row['prod_brand'];
                                            $prod_category = $prod_row['prod_category'];
                                            $prod_images = $prod_row['prod_images'];
                                            $amount = $prod_price*$quantity;
                                            $total = $total+$amount;
                                            echo '
                <tr>
                                    <td>
                                        <div class="img-container">
                                            <img src="assets/img/Products/'.urldecode($prod_images).'/Product_Main.jpg" alt="...">
                                        </div>
                                    </td>
                                    <td class="td-name">
                                        <a href="#jacket">'.$prod_title.'</a>
                                        <br>
                                        <small>by '.$prod_brand.'</small>
                                    </td>
                                    <td class="td-number text-right">
                                        <small>$</small>'.$prod_price.'
                                    </td>
                                    <td class="td-number">
                '.$quantity.'

                                    </td>
                                    <td class="td-number">
                                        <small>$</small>'. $amount.'
                                    </td>
                                    <td class="td-actions">
                                        <button type="button" rel="tooltip" data-placement="left" title="" class="btn btn-link"
                                                data-original-title="Remove item">
                                            <i class="material-icons" onclick="removeFromCart('.$prod_id.','.$_SESSION['id'].')">close</i>
                                        </button>
                                    </td>
                                </tr>
                ';
                                        }
                                    }

                                }
                            }
                            echo '
                            <tr>
                                    <td class="td-total">
                                        Total
                                    </td>
                                    <td colspan="1" class="td-price">
                                        <small>$</small>'.$total.'
                                    </td>
                                    <td colspan="2" class="text-right">
                                        <button type="button" onclick=location.href="place_order.php" class="btn btn-info btn-round">Complete Purchase <i
                                                    class="material-icons">keyboard_arrow_right</i></button>
                                    </td>
                                </tr>'
                            ?>

                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'footer.php'
?>

<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/moment.min.js"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
<script>
    function removeFromCart(prodid, userid) {
        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: {
                action: 'delete',
                user_id: userid,
                prod_id: prodid
            },
            success: function (data) {
                $(".modal_deleted").modal('show');
                $(".modal_deleted").on('hidden.bs.modal', function (e) {
                    location.reload();
                });
            }
        });
    }
</script>
</body>
</html>
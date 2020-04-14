<?php
session_start();
$conn = new mysqli("localhost", "root", "", "OnlineShopping");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Form submitted
if (isset($_POST['submit'])) {

    $stmt = $conn->prepare("INSERT INTO Products (prod_title, prod_brand, prod_category, prod_images, prod_price, prod_desc, prod_stock) VALUES (?, ?, ?, ?, ?, ?, 100)");
    $stmt->bind_param("ssssis", $_POST['prod_name'], $_POST['prod_brand'], $_POST['prod_category'], $_POST['prod_image'], $_POST['prod_price'], $_POST['prod_desc']);

    $stmt->execute();
    mkdir("../assets/img/Products/" . $_POST['prod_image']);
    header('Refresh: 0');
    $stmt->close();

    //Display confirmation page

}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'deleteProduct') {
        $delete_query = $conn->query('DELETE FROM Products where prod_id = "' . $_POST['prod_id'] . '"');
        echo "Deleted";
        header("Refresh:0");
        exit;
    } else {
        exit;
    }
}

$conn->close();
?>
<!doctype html>
<html lang="en">

<head>
    <title>Add Products - Admin Panel : Online Shopping Website</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="../assets/css/material-kit.min.css" rel="stylesheet"/>

</head>

<body>
<?php
session_start();
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'logout') {
        unset($_SESSION['id']);
        echo 'Logged Out';
        exit;
    } else {
        echo 'Failed';
        exit;
    }
}
?>
<nav class="navbar navbar-color-on-scroll navbar-transparent fixed-top navbar-expand-lg" color-on-scroll="100"
     id="sectionsNav">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-translate">
            <a class="navbar-brand" href="/index.php">Online Shopping</a>
            <button type="button" class="ml-auto navbar-toggler" data-toggle="collapse"
                    data-target="#navigation-example3">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navigation-example3">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="/index.php" class="nav-link">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./products.php" class="nav-link">
                        Products
                    </a>
                </li>

                <?php
                if (isset($_SESSION['id']) and $_SESSION['id'] != 0) {
                    echo '
                            <li class="nav-item">
                                <a href="/cart.php" class="nav-link">
                                    Cart
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/orders.php" class="nav-link">
                                    Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:logout()" class="nav-link">
                                    Logout
                                </a>
                            </li>
                            ';
                } else {
                    echo '
                        <li class="nav-item">
                            <a href="/login.php" class="nav-link">
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/Signup.php" class="nav-link">
                                Sign Up
                            </a>
                        </li>
                        ';
                }
                ?>

            </ul>
        </div>
    </div>
</nav>


<div class="page-header header-filter header-small" data-parallax="true"
     style="background-image: url('../assets/img/examples/card-project5.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <div class="brand">
                    <h1 class="title">Products</h1>
                    <h4>Add, Remove or Edit Products</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main main-raised">
    <div class="section">
        <div class="container">
            <div class="row">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "OnlineShopping");
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $stmt = 'SELECT * from Products';
                    $result = $conn->query($stmt);
                    echo '<h4> No. of Products : ' . mysqli_num_rows($result) . '</h4>';
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $prod_id = $row['prod_id'];
                            $prod_title = $row['prod_title'];
                            $prod_image = $row['prod_images'];
                            $prod_brand = $row['prod_brand'];
                            $prod_category = $row['prod_category'];
                            $prod_price = $row['prod_price'];
                            $prod_stock = $row['prod_stock'];

                            echo '
                                <tr>
                                    <td class="text-center">' . $prod_id . '</td>
                                    <td>' . $prod_title . '</td>
                                    <td>' . $prod_brand . '</td>
                                    <td>' . $prod_category . '</td>
                                    <td>' . $prod_image . '</td>
                                    <td class="text-right">$' . $prod_price . '</td>
                                    <td class="td-actions text-right">
                                        <button type="button" rel="tooltip" class="btn btn-success">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button type="button" rel="tooltip" class="btn btn-danger" onclick=deleteProduct(' . $prod_id . ')>
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                                ';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="section">
                <h2 class="section-title">Add a product</h2>
                <form class="form" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="form-row">
                        <div class="col-md-12 form-group bmd-form-group">
                            <div class="input-group">
                                <input oninput="setImageText(this)" required name="prod_name" type="text"
                                       class="form-control"
                                       placeholder="Product Name...">
                            </div>
                        </div>
                    </div>
                    <div class="form-row justify-content-between">
                        <div class="col-md-4 form-group bmd-form-group">
                            <div class="input-group">
                                <input required name="prod_brand" type="text" class="form-control"
                                       placeholder="Product Brand...">
                            </div>
                        </div>
                        <div class="col-md-4 form-group bmd-form-group">
                            <div class="input-group">
                                <input required name="prod_category" type="text" class="form-control"
                                       placeholder="Product Category...">
                            </div>
                        </div>
                        <div class="form-group bmd-form-group">
                            <div class="input-group">
                                <input required name="prod_price" pattern="[0-9]{1,}" type="text" class="form-control"
                                       placeholder="Product Price...">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 form-group bmd-form-group">
                            <div class="input-group">
                                <textarea class="form-control" rows="5" required name="prod_desc" type="text"
                                          placeholder="Product Description..."></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 form-group bmd-form-group">
                            <div class="input-group">
                                <input required name="prod_image" id="prod_image" type="text" class="form-control"
                                       placeholder="Product Image...">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="submit" name="submit" value="Add" class="btn btn-primary btn-round">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php'
?>
<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/moment.min.js"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
<script>
    function logout() {
        $.ajax({
            type: 'POST',
            data: {
                action: 'logout',
            },
            success: function (data) {
                alert(data);
                location.reload();
            }
        });
    }

    function setImageText(element) {
        $("#prod_image").val(element.value);
    }

    function deleteProduct(prodid) {
        $.ajax({
            type: 'POST',
            data: {
                action: 'deleteProduct',
                prod_id: prodid
            },
            success: function (data) {
                alert(data);
                location.reload();
            }
        });
    }
</script>
</body>

</html>
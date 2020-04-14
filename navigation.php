<?php
session_start();
if (!isset($_SESSION['id'])){
    $_SESSION['id'] = 0;
}
if (isset($_POST['action'])) {
    if (strcmp($_POST['action'],'logout') == 0) {
        unset($_SESSION['id']);
        echo 'Logged Out';
        exit;
    } else {
        ;
    }
}
?>
<nav class="navbar navbar-color-on-scroll navbar-transparent fixed-top navbar-expand-lg" color-on-scroll="100"
     id="sectionsNav">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-translate">
            <a class="navbar-brand" href="index.php">Online Shopping</a>
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
                    <a href="index.php" class="nav-link">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link">
                        Products
                    </a>
                </li>

                <?php
                    if (isset($_SESSION['id']) and $_SESSION['id'] != 0){
                            echo '
                            <li class="nav-item">
                                <a href="cart.php" class="nav-link">
                                    Cart
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="orders.php" class="nav-link">
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
                            <a href="login.php" class="nav-link">
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Signup.php" class="nav-link">
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

<div class="modal modal_logout" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logged Out</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick=location.href="login.php">Log In</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function logout(){
        $.ajax({
            type: 'POST',
            url: 'navigation.php',
            data: {
                action: 'logout',
            },
            success: function (data) {
                $(".modal_logout").modal('show');
                $(".modal_logout").on('hidden.bs.modal', function (e) {
                    location.reload();
                });
            }
        });
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="assets/css/material-kit.min.css?v=2.2.0" rel="stylesheet" />
</head>

<body class="login-page sidebar-collapse">
<?php
include 'navigation.php';
?>
<?php
    if (isset($_SESSION['id']) and $_SESSION['id'] != 0){
        if (isset($_POST['next'])){
            header("Location: {$_POST['next']}.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
    if(isset($_POST['submit'])) {
        $conn = new mysqli("localhost", "root", "", "OnlineShopping");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //No errors, process
        //Process your form

        $email = $_POST['email'];
        $stmt = 'SELECT * from Users where user_email = "'.$email.'"';

        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $result = $conn->query($stmt);
        $row = $result->fetch_assoc();

        if (strcmp($_POST['password'], $row['password']) == 0) {
            //$_SESSION['flash'] = 'Logged In Successfully';
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['user_id'];
            $flash['success'] = 'Logged In Successfully. Redirecting.';
            if (isset($_POST['next'])){
                echo "<meta http-equiv=\"refresh\" content=\"1;url={$_POST['next']}\"/>";
            } else {
                echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\"/>";
            }

        } else {
            $flash['error'] = 'Failed to Log in. Check your username and password.';
        }

        $conn->close();
    }
      ?>

    <div class="page-header header-filter"
        style="background-image: url('assets/img/bg7.jpg'); background-size: cover; background-position: top center;">

        <div class="container">
            <?php
            if (isset($flash['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role = "alert" >'.
                    $flash["error"].'<button type = "button" class="close" data-dismiss = "alert" aria-label = "Close" >
                    <span aria-hidden = "true" >&times;</span >
                </button >
            </div >';
            } elseif (isset($flash['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role = "alert" >'.
                    $flash["success"].'<button type = "button" class="close" data-dismiss = "alert" aria-label = "Close" >
                    <span aria-hidden = "true" >&times;</span >
                </button >
            </div >';
            }
            ?>
            <br><br>

            <div class="row" >
                <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                    <form id="login_form" class="form" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                        <div class="card card-login card-hidden">
                            <div class="card-header card-header-primary text-center">
                                <h4 class="card-title">Login</h4>
                            </div>
                            <div class="card-body ">
                                <span class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">email</i>
                                            </span>
                                        </div>
                                        <input name="email" type="email" class="form-control" placeholder="Email..." required>
                                    </div>
                                </span>
                                <span class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                        </div>
                                        <input name="password" type="password" class="form-control" placeholder="Password..." required>
                                    </div>
                                </span>
                            </div>
                            <p></p>
                            <div class="card-footer  flex-column"><?php
                                if (isset($_GET['next'])) {
                                    echo '<input  type="hidden" name="next" value="'.htmlspecialchars($_GET['next']).'">';
                                }
                                ?>
                            <input type="submit" name="submit" value="Let's Go" class="btn btn-rose btn-round"></input>
                            
                            <a href="Signup.php" class="btn btn-rose btn-link btn-lg">Don't have an account ?<div class="ripple-container"></div></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        include 'footer.php'
        ?>
    </div>
    
    <script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
    <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/material-kit.js?v=2.2.0" type="text/javascript"></script>
</body>

</html>
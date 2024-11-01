<?php
session_start();


if(@$_SESSION['loggedin'] === true){
    header("Location: index.php");
}

if(isset($_POST['submit'])){
    $pass = $_POST['pass'];
    if ($pass === 'manee') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Manee';
        $_SESSION['upload_dir'] = 'uploads_manee'; // Directory for 'manee'
        header("Location: list.php");
    } elseif ($pass === 'nikas') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Nikas';
        $_SESSION['upload_dir'] = 'uploads_nikas'; // Directory for 'nikas'
        header("Location: list.php");
    } elseif ($pass === 'sajan') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Sajan';
        $_SESSION['upload_dir'] = 'uploads_sajan'; // Directory for 'sajan'
        header("Location: list.php");
    } elseif ($pass === 'bct123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Manee';
        $_SESSION['upload_dir'] = 'uploads'; // Directory for default people
        header("Location: list.php");
    }elseif ($pass === 'sushma') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Sushma';
        $_SESSION['upload_dir'] = 'uploads_sushma'; // Directory for default people
        header("Location: list.php");
    }elseif ($pass === 'abiral') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Manee';
        $_SESSION['upload_dir'] = 'uploads_abiral'; // Directory for default people
        header("Location: list.php");
    }else {
        echo "<script>alert('Wrong Password');</script>";
    }
}
?>

<html>

<head>
    <title>Log-in</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.png">

</head>

<body>
    <style>
        @media (max-width: 576px) {
            .out-box {
                width: 100% !important;
                height: 100vh !important;
                border-radius: 0 !important;
            }
            .picture-box {
                width: 100% !important;
                border-top-right-radius: 0 !important;
                border-top-left-radius: 0 !important;
                border-bottom-right-radius: 10px !important;
                border-bottom-left-radius: 10px !important;

            }
            .spacer {
                height: 9rem !important;
            }
        }
    </style>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div style="height: 20rem; width: 23rem; border-radius: 10px;" class="bg-white rounded-lg shadow-lg overflow-hidden out-box">
            <div class="bg-gradient p-4 text-white picture-box"
                style="border-top-right-radius: 10px;border-top-left-radius: 10px ; background-color: #2234ae;background-image: linear-gradient(315deg, #2234ae 0%, #000C66 74%);">
                <div class="spacer"></div>
                <h2 class="mt-3 display-5 font-weight-bold">Hey!</h2>
            </div>
            <div class="p-4">
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label for="pass" class="form-label text-muted">Password</label>
                        <input type="password" id="pass" name="pass" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="d-flex align-items-center justify-content-between" >
                        <input type="submit" name="submit"
                            class="d-flex align-items-center justify-content-center rounded-circle "
                            style="width: 48px; height: 48px;  background-color: #2234ae;background-image: linear-gradient(315deg, #2234ae 0%, #000C66 74%); color: white; border: white; " value="→">
                          
                        </input>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<!-- <form action="#" method="POST">
    <input type="password" name="pass" id="">
    <input type="submit" name="submit" value="login">
</form> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
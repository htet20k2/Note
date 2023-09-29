<?php
    session_start();
    require_once('db.php');
    if(isset($_POST['loginBtn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $db->prepare("SELECT * FROM users WHERE email='$email' AND password='$password'" );
        $stmt->execute();
        $user=$stmt-> fetchObject();

        $_SESSION['user']=$user;
        header('location:index.php');
    }

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
<div class="container">
        <form class="form-group p-5" method="post">
            <label  class=" mb-2">Email address</label>
            <input type="email" name="email" class="form-control mb-2">

            <label class=" mb-2">Password</label>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password">

            <button type="submit" name="loginBtn" class="btn btn-primary mb-2">Login</button>
        </form>
    </div>
</body>
</html>
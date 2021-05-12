<?php

$showError = "false";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    include '_dbconnect.php';
    $user_email = $_POST['signUpEmail'];
    $password = $_POST['signUpPassword'];
    $cpassword = $_POST['signUpcPassword'];

    //check whether this email exits or not
    $existSql = "SELECT * FROM users WHERE user_email = '$user_email'";
    $result = mysqli_query($con, $existSql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0){
        $showError = "exist";
    }else{
        if($password == $cpassword){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`user_email`, `user_pass`, `timestamp`) VALUES ('$user_email', '$hash', current_timestamp())";
            $result = mysqli_query($con, $sql);
            if($result){
                $showAlert = true;
                header("Location: /Forums/index.php?signupsuccess=true");
                exit();
            }

        }else{
            $showError = "misMatch";
        }
    }
    header("Location: /Forums/index.php?signupsuccess=false&error=$showError");


}

?>
<?php
require('config.php');
$login_url = $client->createAuthUrl();

if (isset($_GET['code'])):

    session_start();
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if(isset($token['error'])){
        header('Location: login.php');
        exit;
    }
    $_SESSION['token'] = $token;

    /* -- Inserting the user data into the database -- */

    # Fetching the user data from the google account
    $client->setAccessToken($token);
    $google_oauth = new Google_Service_Oauth2($client);
    $user_info = $google_oauth->userinfo->get();

    $google_id = trim($user_info['id']);
    $f_name = trim($user_info['given_name']);
    $l_name = trim($user_info['family_name']);
    $email = trim($user_info['email']);
    $gender = trim($user_info['gender']);
    $local = trim($user_info['local']);
    $picture = trim($user_info['picture']);


    # Checking whether the email already exists in our database.
    $check_email = $connection->prepare("SELECT `email` FROM api_db.user WHERE `email`=?");
    $check_email->bindParam(1, $email);
    $check_email->execute();

    if($check_email->rowCount() === 0){
        # Inserting the new user into the database
        $query_template = "INSERT INTO api_db.user (`oauth_uid`, `name`, `surname`,`email`) VALUES (?,?,?,?)";
        $insert_stmt = $connection->prepare($query_template);
        $insert_stmt->bindParam(1, $google_id, PDO::PARAM_STR);
        $insert_stmt->bindParam(2, $f_name, PDO::PARAM_STR);
        $insert_stmt->bindParam(3, $l_name, PDO::PARAM_STR);
        $insert_stmt->bindParam(4, $email, PDO::PARAM_STR);

        if(!$insert_stmt->execute()){
            echo "Failed to insert user.";
            exit;
        }
    }

    header('Location: home.php');
    exit;

endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .btn-container {
            text-align: center;
        }

        .login-btn {
            all: unset;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #4285f4;
            color: white;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .login-btn:hover {
            background-color: white;
            color: #4285f4;
            border: 2px solid #4285f4;
        }

        .login-btn img {
            width: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
<div class="header">
    Login with Google Account
</div>
<div class="btn-container">
    <button class="login-btn" onclick="window.location.href='<?= $login_url ?>'">
        <img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login with Google
    </button>
</div>
</body>

</html>

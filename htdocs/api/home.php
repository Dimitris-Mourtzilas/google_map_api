<?php
session_start();
if(!isset($_SESSION['token'])){
    header('Location: login.php');
    exit;
}
require('config.php');

$client->setAccessToken($_SESSION['token']);

if($client->isAccessTokenExpired()){
    header('Location: logout.php');
    exit;
}
$google_oauth = new Google_Service_Oauth2($client);
$user_info = $google_oauth->userinfo->get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="map.js"></script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIx-up4s0-rGDKYK2zYipysUthpU8cRHk&libraries=places&callback=initMap">

    </script>

    <script>

        $(document).ready(function() {
            loadAddresses();
            // Function to load addresses via AJAX
            function loadAddresses() {
                $.ajax({
                    url: 'get_addresses.php',
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        // Populate table with new address rows
                        $('#address-list').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to load addresses');
                    }
                });
            }

            $('#save-address-button').click(function() {
                var avenue = $('#place-input').val();
                $.ajax({
                    url: 'add_address.php',
                    type: 'POST',
                    data: {
                        avenue: avenue,
                    },
                    success: function () {
                        loadAddresses();
                    },
                    error: function () {
                        alert("Could not add address")
                    }
                })
            });




        })

    </script>
</head>
    <style>
        body{
            padding: 50px;
        }
        ul{
            list-style: none;
        }
        li{
            margin-bottom: 5px;
        }
        #map{
            width:100%;
            height:400px;
        }

        #input-container {
            text-align: center;
            margin-top: 20px;
        }

        #place-input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            width: 250px;
        }

        #save-address-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        #save-address-button:hover {
            background-color: #45a049;
        }


    </style>
<body>

<ul>
    <li><img src="<?=$user_info['picture'];?>" style="border: 1px groove black; border-radius:5px;"></li>
    <li><strong>Full Name:</strong> <?=$user_info['givenName'];?> <?=$user_info['familyName'];?></li>
    <li><strong>Email:</strong> <?=$user_info['email'];?></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
<div id='map'></div>
<div id="input-container">
    <input type="text" id="place-input" placeholder="Enter address">
    <button type="button" id="save-address-button">Add address</button>
</div>

<table class="table">
    <thead>
    <tr>
        <th scope="col">
            ID
        </th>
        <th scope="col" style="text-align: center;">Avenue</th>
    </tr>
    </thead>
    <tbody id="address-list">
    </tbody>
</table>


</body>

</html>

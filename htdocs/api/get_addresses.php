<?php
require 'db.php';
$addressQuery = $connection->query("SELECT * FROM api_db.address");
if($addressQuery->rowCount()===0){
    echo "<h4 style='text-align:left;'>No address listed</h4>";
}
else {
    $result = $addressQuery->fetchAll(PDO::FETCH_ASSOC);

    echo "<tr>";
    foreach ($result as $address) {
        echo "<td style='text-align: center;' id='address-id'>{$address['id']}</td>";
        echo "<td style='text-align: center;' id='address-avenue'>{$address['avenue']}</td></tr>";
    }
}


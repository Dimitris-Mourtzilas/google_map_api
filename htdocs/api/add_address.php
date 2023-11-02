<?php
require 'db.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['avenue'])) {
    try {
        $stmt = $connection->prepare("insert into api_db.address(avenue)values(?)");
        $stmt->bindParam(1, $_POST['avenue']);
        $stmt->execute();

    }catch (PDOException $e) {echo $e->getMessage();}
}
<?php

include_once('../../functions/userFuction.php');


$result = userRegistration($_POST['userName'], $_POST['userEmail'], $_POST['userPass'], $_POST['userPhone'], $_POST['userNic']);



echo($result);

?>
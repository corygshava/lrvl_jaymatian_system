<?php
    if(isset($_GET['inframe'])){
        if($_GET['inframe'] !== "yes"){
            echo "invalid render method";
            exit();
        }
    } else {
        echo "<b>invalid render method</b>";
        exit();
    }
?>

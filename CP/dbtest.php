<?php
    $m = new MongoClient();
    echo "mongo connect";
    $db = $m->test;
    echo "db connect";
?>
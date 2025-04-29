<?php
$db = new SQLite3('beats.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}
?>

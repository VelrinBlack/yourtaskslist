<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$conn = substr($url["path"], 1);
$db = new mysqli($server, $username, $password, $conn);

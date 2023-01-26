<?php
try {
    $database = array(
        "host" => $_ENV['PHP_HOST'],
        "name" => $_ENV['PHP_DB_NAME'],
        "user" => $_ENV['PHP_USER'],
        "password" => $_ENV['PHP_PASSWORD']
    );
    $pdo = new PDO(
        'mysql:dbname=' . $database['name'] . ';host=' . $database['host'],
        $database['user'],
        $database['password']
    );
    $sql = file_get_contents("Config/camagru.sql");
    preg_match_all("/CREATE TABLE `(.*?)`/", $sql, $matches);
    $tables = $matches[1];
    $count = 0;
    foreach ($tables as $table) {
        $check_table = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($check_table->rowCount() != 1) {
            $count++;
        }
    }
    if ($count > 0) {
        $pdo->exec($sql);
    }
} catch (PDOException $e) {
    die("Error occurred:" . $e->getMessage());
}
?>
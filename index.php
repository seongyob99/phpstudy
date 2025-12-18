<!-- TODO 목록 화면 -->
<?php 
require 'db.php';

$todos = $pdo->query("SELECT * FROM todos ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>TODO List</h2>
    
</body>
</html>

<?php
require_once 'core/dbConfig.php';

if (isset($_GET['album_id'])) {
    $album_id = intval($_GET['album_id']);
    

    $stmt = $pdo->prepare("DELETE FROM albums WHERE album_id = :album_id");
    $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header("Location: index.php?message=Album deleted successfully");
    } else {
        header("Location: index.php?message=Failed to delete album");
    }
    exit();
}
?>

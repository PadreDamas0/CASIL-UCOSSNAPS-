<?php
require_once 'core/dbConfig.php';


if (isset($_GET['album_id'])) {
    $albumId = $_GET['album_id'];
    
  
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = :album_id");
    $stmt->execute(['album_id' => $albumId]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$album) {
        die('Album not found');
    }
    

    $albumName = $album['album_name'] ?? '';
    $releaseDate = $album['release_date'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['updateAlbumBtn'])) {
        $albumName = $_POST['albumName'];
        $releaseDate = $_POST['releaseDate'];

        $updateStmt = $pdo->prepare("UPDATE albums SET album_name = :album_name, release_date = :release_date WHERE album_id = :album_id");
        $updateStmt->execute([
            'album_name' => $albumName,
            'release_date' => $releaseDate,
            'album_id' => $albumId
        ]);
        

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
</head>
<body>
    <h2>Edit Album</h2>
    <form action="edit_album.php?album_id=<?php echo $albumId; ?>" method="POST">
        <p>
            <label for="albumName">Album Name:</label>
            <input type="text" name="albumName" value="<?php echo htmlspecialchars($albumName); ?>" required>
        </p>
        <p>
            <label for="releaseDate">Release Date:</label>
            <input type="date" name="releaseDate" value="<?php echo htmlspecialchars($releaseDate); ?>" required>
        </p>
        <button type="submit" name="updateAlbumBtn">Update Album</button>
    </form>
</body>
</html>

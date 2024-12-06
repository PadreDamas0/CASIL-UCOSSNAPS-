<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="insertPhotoForm" style="display: flex; justify-content: center;">
		<form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
			<p>
				<label for="#">Description</label>
				<input type="text" name="photoDescription">
			</p>
			<p>
				<label for="#">Photo Upload</label>
				<input type="file" name="image">
				<input type="submit" name="insertPhotoBtn" style="margin-top: 10px;">
			</p>
		</form>
	</div>

	<div class="createAlbumForm" style="display: flex; justify-content: center; margin-top: 20px;">
    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="albumName">Create Album</label>
            <input type="text" name="albumName" placeholder="Album Name">
            <button type="submit" name="createAlbumBtn">Create</button>
        </p>
    </form>
</div>

<?php 
$userAlbums = getAlbumsByUser($pdo, $_SESSION['user_id']);
if (!empty($userAlbums)) {
    foreach ($userAlbums as $album) { ?>
        <div class="album">
            <h3><?php echo $album['name']; ?></h3>
            <form action="core/handleForms.php" method="POST" style="display:inline;">
                <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
                <input type="text" name="albumName" value="<?php echo $album['name']; ?>">
                <button type="submit" name="updateAlbumBtn">Update</button>
                <button type="submit" name="deleteAlbumBtn" onclick="return confirm('Delete this album?');">Delete</button>
            </form>
        </div>
<?php } 
} ?>

<div class="insertPhotoForm" style="display: flex; justify-content: center;">
    <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <p>
            <label for="photoDescription">Description</label>
            <input type="text" name="photoDescription">
        </p>
        <p>
            <label for="album">Select Album</label>
            <select name="album_id">
                <option value="">No Album</option>
                <?php foreach ($userAlbums as $album) { ?>
                    <option value="<?php echo $album['album_id']; ?>"><?php echo $album['name']; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="image">Photo Upload</label>
            <input type="file" name="image">
            <button type="submit" name="insertPhotoBtn">Upload</button>
        </p>
    </form>
</div>

<table>
    <tr>
        <th>Album Name</th>
        <th>Release Date</th>
        <th>Actions</th>
    </tr>
    <?php
    $stmt = $pdo->query("SELECT * FROM albums");
    while ($album = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($album['name']) . "</td>";
		echo "<td>" . htmlspecialchars($album['created_at']) . "</td>";

        echo "<td>
                <a href='edit_album.php?album_id=" . $album['album_id'] . "'>Edit</a> | 
                <a href='delete_album.php?album_id=" . $album['album_id'] . "' onclick='return confirm(\"Are you sure you want to delete this album?\");'>Delete</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>


	<?php $getAllPhotos = getAllPhotos($pdo); ?>
	<?php foreach ($getAllPhotos as $row) { ?>

	<div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%;">

			<img src="images/<?php echo $row['photo_name']; ?>" alt="" style="width: 100%;">

			<div class="photoDescription" style="padding:25px;">
				<a href="profile.php?username=<?php echo $row['username']; ?>"><h2><?php echo $row['username']; ?></h2></a>
				<p><i><?php echo $row['date_added']; ?></i></p>
				<h4><?php echo $row['description']; ?></h4>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
					<a href="editphoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Edit </a>
					<br>
					<br>
					<a href="deletephoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Delete</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
</body>
</html>

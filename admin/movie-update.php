<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OZKANMOVIE | DASHBOARD</title>
    <link rel="stylesheet" href="../admin/adminStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-secondary">

<div class="header bg-dark">
    <h1>OZKANMOVIE Dashboard</h1>
</div>

<div class="sidebar bg-dark">
    <ul>
        <li><a href="admin/dashboard.php"><i class="bi bi-plus-square"></i> Kategori Ekle</a></li>
        <li><a href="dashboard.php"><i class="bi bi-film"></i> Film Ekle</a></li>
        <li><a href="dashboard.php"><i class="bi bi-arrow-clockwise"></i> Film & Kategori Düzenle</a></li>
        <li><a href="#section4"><i class="bi bi-arrow-clockwise"></i> Film Düzenle</a></li>
    </ul>
</div>

<div class="content">
<?php
include("../settings/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['movieId'])) {
    $movieId = $_POST['movieId'];
    $movieName = $_POST['movieName'];
    $movieDesc = $_POST['movieDesc'];
    $movieRelease = $_POST['movieRelease'];
    $movieCategoryID = $_POST['movieCategoryID'];

    $query = $db->prepare("SELECT banner_url, background_url FROM movies WHERE id = ?");
    $query->execute([$movieId]);
    $currentUrls = $query->fetch(PDO::FETCH_ASSOC);

    $bannerUrl = $currentUrls['banner_url'];
    $backgroundUrl = $currentUrls['background_url'];

    if (!empty($_FILES["movieBanner"]["name"])) {
        $bannerFile = $_FILES["movieBanner"];
        $targetDir = "../images/movieBanner/";
        $bannerFilename = uniqid() . '_' . $bannerFile["name"];

        if (move_uploaded_file($bannerFile["tmp_name"], $targetDir . $bannerFilename)) {
            $bannerUrl = str_replace('../', '', $targetDir) . $bannerFilename;
        } else {
            echo '<p class="alert alert-danger">Afiş dosyası yükleme hatası.</p>';
            exit;
        }
    }

    if (!empty($_FILES["movieBackground"]["name"])) {
        $backgroundFile = $_FILES["movieBackground"];
        $backgroundDir = "../images/movieBackground/";
        $backgroundFilename = uniqid() . '_' . $backgroundFile["name"];

        if (move_uploaded_file($backgroundFile["tmp_name"], $backgroundDir . $backgroundFilename)) {
            $backgroundUrl = str_replace('../', '', $backgroundDir) . $backgroundFilename;
        } else {
            echo '<p class="alert alert-danger">Arkaplan dosyası yükleme hatası.</p>';
            exit;
        }
    }

    $updateQuery = $db->prepare("UPDATE movies SET title=?, description=?, category_id=?, release_date=?, banner_url=?, background_url=? WHERE id=?");
    $result = $updateQuery->execute([$movieName, $movieDesc, $movieCategoryID, $movieRelease, $bannerUrl, $backgroundUrl, $movieId]);

    if ($result) {
        echo '<p class="alert alert-success">Film başarıyla güncellendi.</p>';
        header("Refresh: 5; URL=../admin/dashboard.php");
        exit;
    } else {
        echo '<p class="alert alert-danger">Film güncellenirken hata oluştu.</p>';
    }
}
?>





<?php
if (isset($_GET['updateMovieId'])) {
    $updateMovieId = $_GET['updateMovieId'];
    $query = $db->prepare("SELECT * FROM movies WHERE id = ?");
    $query->execute([$updateMovieId]);
    $movie = $query->fetch(PDO::FETCH_ASSOC);

    if ($movie) {
        echo '<form class="w-50 m-auto" action="" method="post" enctype="multipart/form-data">';
        echo '<input type="hidden" name="movieId" value="' . $movie['id'] . '">';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Film Adı</strong></label>';
        echo '<input class="form-control" type="text" name="movieName" value="' . $movie['title'] . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Film Açıklaması</strong></label>';
        echo '<textarea name="movieDesc" cols="30" rows="5" class="form-control">' . $movie['description'] . '</textarea>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Çıkış Tarihi</strong></label>';
        echo '<input class="form-control" type="date" name="movieRelease" value="' . $movie['release_date'] . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Film Türü</strong></label>';
        echo '<select name="movieCategoryID" class="form-select">';

        $categoryQuery = $db->query("SELECT id, name FROM categories");
        $categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $category) {
            $selected = ($category['id'] == $movie['category_id']) ? 'selected' : '';
            echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Banner</strong></label>';
        echo '<input class="form-control" type="file" name="movieBanner">';
        echo '<img src="../' . $movie['banner_url'] . '" alt="Banner" style="max-width: 100px;">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label class="form-label" for=""><strong>Arkaplan</strong></label>';
        echo '<input class="form-control" type="file" name="movieBackground">';
        echo '<img src="../' . $movie['background_url'] . '" alt="Arkaplan" style="max-width: 100px;">';
        echo '</div>';
        echo '<div class="buttons">';
        echo '<input type="submit" value="Güncelle" class="btn btn-primary mt-2 w-100">';
        echo '</div>';
        echo '</form>';
    } else {
        echo '<p class="alert alert-danger">Seçilen film bulunamadı.</p>';
    }
} else {
    echo '<p class="alert alert-warning">Lütfen güncellenecek filmi seçin.</p>';
}
?>



</div>

<script src="../admin/adminJs.js"></script>
</body>
</html>

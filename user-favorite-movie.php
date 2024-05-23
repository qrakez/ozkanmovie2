<?php
session_start();
if(!isset($_SESSION["user"])){
    echo '<p class="alert alert-danger">Lütfen Önce Giriş Yapınız.</p>';
    header("refresh:2,url = login.php");
    exit;
}
include 'settings/db.php';
include 'func.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OzkanMovie | Kategoriler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="bg-dark">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">OZKANMOVIE</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Ana Sayfa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="movie.php">Filmler</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user-favorite-movie.php">Favori Filmlerim</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item">   
                            <a class="nav-link" href="#"><i class="bi bi-person-circle"></i> <?=@$_SESSION['user']?></a>
                        </li>
                        <li class="nav-item">   
                            <a class="nav-link" href="logout.php"><i class="bi bi-person-fill-dash"></i> Çıkış Yap</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="action-categorie">
            <h4 style="border-bottom: 2px solid #fff; padding: 10px; color: #fff;">Favori Filmlerim</h4>
            <div class="row ms-2">
            <?php
                $userId = $_SESSION["user_id"];

                try {
                    $dbst = $db->prepare("SELECT m.title, m.description, m.self_link, m.banner_url
                                        FROM userhistory uh
                                        INNER JOIN movies m ON uh.movie_id = m.id
                                        WHERE uh.user_id = :user_id AND uh.favorite = '1'");
                    
                    $dbst->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    
                    $dbst->execute();
                    
                    $rows = $dbst->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($dbst->rowCount() > 0) {
                        foreach ($rows as $row) {
                            echo '
                            <div class="col-md-6 col-lg-4 text-white">
                                <div class="series-item" style="height:475px;">
                                    <img src="' . $row["banner_url"] . '" alt="' . $row["title"] . '" class="img-fluid d-block mx-auto">
                                    <div class="series-detail">
                                        <p class="card-series-header">' . $row["title"] . '</p>
                                        <p class="card-series-detail">' . $row["description"] . '</p>
                                        <a href="watch-movie.php?q=' . $row["self_link"] . '" class="card-watch-btn">İzle</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo "Henüz favori film eklememişsiniz.";
                    }
                } catch (PDOException $e) {
                    die("Hata: " . $e->getMessage());
                }
            ?>

</div>         
<div class="watched-categorie">
            <h4 style="border-bottom: 2px solid #fff; padding: 10px; color: #fff;">İzlediğim Filmlerim</h4>
            <div class="row ms-2">
            <?php
                $userId = $_SESSION["user_id"];

                try {
                    $dbst = $db->prepare("SELECT m.title, m.description, m.self_link, m.banner_url
                                        FROM userhistory uh
                                        INNER JOIN movies m ON uh.movie_id = m.id
                                        WHERE uh.user_id = :user_id AND uh.watched_at = '1'");
                    
                    $dbst->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    
                    $dbst->execute();
                    
                    $rows = $dbst->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($dbst->rowCount() > 0) {
                        foreach ($rows as $row) {
                            echo '
                            <div class="col-md-6 col-lg-4 text-white">
                                <div class="series-item" style="height:475px;">
                                    <img src="' . $row["banner_url"] . '" alt="' . $row["title"] . '" class="img-fluid d-block mx-auto">
                                    <div class="series-detail">
                                        <p class="card-series-header">' . $row["title"] . '</p>
                                        <p class="card-series-detail">' . $row["description"] . '</p>
                                        <a href="watch-movie.php?q=' . $row["self_link"] . '" class="card-watch-btn">İzle</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo "Henüz favori film eklememişsiniz.";
                    }
                } catch (PDOException $e) {
                    die("Hata: " . $e->getMessage());
                }
            ?>

</div>         
    </div>
</body>
</html>
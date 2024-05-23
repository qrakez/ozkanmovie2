<?php
include 'partials/header.php';

if(isset($_GET["q"])) {
    $q = $_GET["q"];
    
    $data = $db->prepare("SELECT * FROM movies WHERE self_link=?");
    $data->execute([$q]);
    $_data = $data->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Arama parametresi bulunamadı.";
    exit;
}
?>

<div class="slider" style="position: relative;">
    <video id="background-video" muted loop controls style="width: 100%; height: 70vh; object-fit: cover;">
    <source src="<?php echo $_data['video_url']; ?>" type="video/mp4">
        Tarayıcınız video etiketini desteklemiyor.
    </video>
    <div id="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 60vh; display: flex; align-items: center; justify-content: center; flex-direction: column; background: rgba(0, 0, 0, 0.5); color: white;">
        <h1 class="w-75 text-center" ><?php echo $_data['title']; ?></h1>
        <p class="w-75 text-center"><?php echo $_data['description']; ?></p>
        <p class="w-75 text-center">Yapım Tarihi: <?php echo $_data['release_date']; ?></p>
        <button id="play-button" style="padding: 10px 20px; background-color: red; border: none; border-radius: 5px; color: white; font-size: 16px; cursor: pointer;">Play</button>
    </div>
</div>

<script>
document.getElementById('play-button').addEventListener('click', function() {
    var video = document.getElementById('background-video');
    video.play();
    document.getElementById('video-overlay').style.display = 'none';
});
</script>





    <div class="row  text-white bg-dark">
       <div class="col-lg-8 px-5 series-show">
        <h5 class="pt-3 pb-2" style="padding-bottom: 50px; border-bottom: 2px solid white; font-size: 500; text-align: left;">Filmler</h1>
            <img src="img/slider/text-left-lines.png" alt="">
            <div class="row series mb-3">
                <?php
                    $dataList = $db->prepare("SELECT * FROM movies ORDER BY id DESC LIMIT 6");
                    $dataList -> execute();
                    $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

                    foreach($dataList as $row){
                        echo 
                        '
                        <div class="col-md-6 col-lg-4">
                        <div class="series-item" style="height:475px;">
                            <img src="'.$row["banner_url"].'" alt="'.$row["title"].'" class="img-fluid d-block mx-auto">
                            <div class="series-detail">
                                <p class="card-series-header">"'.$row["title"].'"</p>
                                <p class="card-series-detail">
                                    "'.$row["description"].'"
                                </p>
                                <a href="watch-movie.php?q='.$row["self_link"].'" class="card-watch-btn">İzle</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                ?>
        </div>
       </div>
       <div class="col-lg-4">
        <div class="row-details mt-5">
            <div class="col-md-6 col-lg-12">
                <h3>Kategoriler</h3>
                <div class="category-box">
                <?php

                    $dataList = $db -> prepare("SELECT * FROM categories ORDER BY id DESC LIMIT 10");
                    $dataList -> execute();
                    $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

                    foreach($dataList as $row){
                        echo 
                        '
                        <div class="category"> <a style="text-decoration:none; color:#fff;" href="category.php?q='.$row["self_link"].'">'.$row["name"].'</a></div>
                        ';
                        
                    }
                ?>
                </div>
                <h3 class="mt-3">En Son Yüklenen</h3>
                <div class="category-box">
                    <div class="col-lg-8">
                    <?php
                        $dataList = $db -> prepare("SELECT * FROM movies ORDER BY id DESC LIMIT 1");
                        $dataList -> execute();
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

                        foreach($dataList as $row){
                            echo 
                            '
                            <div class="series-item">
                                <img src="'.$row["banner_url"].'" alt="'.$row["title"].'" class="img-fluid d-block mx-auto">
                                <div class="series-detail">
                                    <p class="card-series-header">"'.$row["title"].'"</p>
                                    <p class="card-series-detail">
                                        "'.$row["description"].'"
                                    </p>
                                    <a href="watch-movie.php?q='.$row["self_link"].'" class="card-watch-btn">İzle</a>
                                    </div>
                                </div>
                            ';
                            
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>
    
</div>
<?php include('partials/footer.php'); ?>
</body>
</html>
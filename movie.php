<?php include('partials/header.php'); ?>
        <?php
                        $dataList = $db -> prepare("SELECT * FROM movies ORDER BY id DESC LIMIT 1");
                        $dataList -> execute();
                        $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

                        foreach($dataList as $row){
                            echo 
                            '
                            <div class="slider" style="background-image: url(' . $row["background_url"] . '); background-repeat: no-repeat;">
                            <div class="row py-5 slider-content">
                                <div class="col-md-6 d-flex justify-content-center d-md-block">
                                    <img class="slider-image w-50 rounded ms-5" src="' . $row['banner_url'] . '" alt="' . $row['title'] . '">
                                </div>
                                <div class="col-md-6 m-auto d-none d-md-block">
                                    <div class="slider-text-wrapper me-5 text-white">
                                        <h1 style="padding-bottom: 50px; border-bottom: 2px solid white; font-size: 500; text-align: center;">En Yeni Dizimiz</h1>
                                        <h1 class="series-header">' . $row['title'] . '</h1>
                                        <p class="series-paragraph">' . $row['description'] . '</p>
                                        <button class="float-end btn btn-danger rounded-pill fs-6 p-2 px-3" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-play-circle-fill"></i> İzle</button>
                                     
                                    </div>
                                </div>
                            </div>
                        </div>';
                        }
                    ?>
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
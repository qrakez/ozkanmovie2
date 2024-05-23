<?php
session_start();
include 'settings/db.php';

if(isset($_POST['userId'], $_POST['movieId'], $_POST['actionType'])) {
    $userId = $_POST['userId'];
    $movieId = $_POST['movieId'];
    $actionType = $_POST['actionType']; 

    try {
        $sessionUserId = $_SESSION["user_id"];

        $dbst_check = $db->prepare("SELECT uh.id, uh.watched_at, uh.favorite 
        FROM userhistory uh
        INNER JOIN users u ON uh.user_id = u.id
        WHERE uh.user_id = ? AND u.id = ? AND uh.movie_id = ?");
        
        $dbst_check->execute([$userId, $sessionUserId, $movieId]);
        $existingRecord = $dbst_check->fetch(PDO::FETCH_ASSOC);

        if ($existingRecord) {
            if ($actionType === 'watched' && $existingRecord['watched_at'] != '1') {
                $dbst_update = $db->prepare("UPDATE userhistory SET watched_at = '1' WHERE id = ?");
                $dbst_update->execute([$existingRecord['id']]);
            } elseif ($actionType === 'favorite' && $existingRecord['favorite'] != '1') {
                $dbst_update = $db->prepare("UPDATE userhistory SET favorite = '1' WHERE id = ?");
                $dbst_update->execute([$existingRecord['id']]);
            }
            echo "İşlem başarıyla güncellendi";
        } else {
            if ($actionType === 'watched') {
                $dbst_insert = $db->prepare("INSERT INTO userhistory (user_id, movie_id, watched_at) VALUES (?, ?, '1')");
                $dbst_insert->execute([$userId, $movieId]);
            } elseif ($actionType === 'favorite') {
                $dbst_insert = $db->prepare("INSERT INTO userhistory (user_id, movie_id, favorite) VALUES (?, ?, '1')");
                $dbst_insert->execute([$userId, $movieId]);
            }
            echo "İşlem başarıyla eklendi";
        }
    } catch (PDOException $e) {
        die("Hata: " . $e->getMessage());
    }
} else {
    echo "Lütfen tüm gerekli alanları doldurun.";
}
?>

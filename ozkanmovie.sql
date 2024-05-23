-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 May 2024, 18:11:07
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ozkanmovie`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `self_link` varchar(599) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `self_link`) VALUES
(12, 'Macera', 'Macera, baştan geçen ilginç olay veya olaylar zinciri, serüven, sergüzeşt, avantür. Tehlikeli iş, risk, spekülasyon gibi anlamlara da gelebilir. Mecazi olarak da \"olmayacakmış gibi görünen iş\" anlamında kullanılmaktadır.', 'macera'),
(13, 'Aksiyon', 'Birçok alt türü bulunur; dövüş sanatları aksiyon, doğa sporları aksiyonu, araba kovalamaları, gerilim aksiyonu ve aksiyon komedisi.\r\n', 'aksiyon'),
(15, 'Drama', 'Drama, lider ve katılımcıların atölye ortamında rol oynama, doğaçlama gibi tiyatro tekniklerini kullanarak bir olayı, anıyı, kavramı, konuyu, düşünceyi canlandırması olarak ifade edilebilir.', 'drama'),
(16, 'Korku', 'Korku filmlerinin konusunu gündelik hayata sızan ve bazen doğaüstü şekillerde ortaya çıkan şeytani güçler, olaylar ya da karakterler oluşturur. Korku filmi karakterleri vampirler, zombiler, canavarlar, hayaletler, psikopatlar, seri katiller ya da korku uyandıran başka bir dizi karakteri içerir.', 'korku'),
(18, 'Gerilim', 'Gerilim filmi izleyicilerde heyecan ve gerilim uyandıran geniş bir film türüdür. Çoğu filmin olay örgüsünde bulunan gerilim öğesi, bu türde film yapımcısı tarafından özellikle kullanılır.', 'gerilim');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `self_link` varchar(599) NOT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `background_url` varchar(599) NOT NULL,
  `video_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `category_id`, `release_date`, `self_link`, `banner_url`, `background_url`, `video_url`) VALUES
(31, 'Peaky Blinders', 'Birmingham\'da geçen dizi, Peaky Blinders suç çetesinin I. Dünya Savaşı\'nın hemen sonrasındaki maceralarını konu almaktadır. Kurgusal çete, 1880\'lerden 1910\'lara kadar şehirde aktif olan aynı isimli genç suçlulardan oluşan gerçek bir sokak çetesine dayanmaktadır.', 18, '2005-06-05', 'peaky-blinders', 'images/movieBanner/1715509980_peky.jpg', 'images/movieBackground/1715509980_13730809_1661431997512928_3023023901316113243_o.jpg', 'movies/peakyBlinders_Fragman.mp4'),
(32, 'Who Am I?', 'Genç bir bilgisayar dehası olan Benjamin, sadece Almanya\'da değil dünya çapında tanınan biri olmak istemektedir. Yer altı bir hacker grubu, Benjamin\'i aralarına katılmaya çağırınca, Benjamin bu tehlikeli teklifi kabul eder ancak bu tehlikeli oyunlarda başına geleceklerden habersizdir.', 13, '2004-07-12', 'who-am-i', 'images/movieBanner/664278f1f0d01_6640a24110fe6_afis.jpg', 'images/movieBackground/66427921e8bc4_wallpaperflare.com_wallpaper.jpg', 'movies/WhoAmI-fragman.mp4'),
(41, 'Hitman 2007', 'Bu eğitimi alan çocuklardan biri olan ve suikastçılık geçinen Ajan 47, Interpol ajanı Mike Whittier\'ın yaptığı çalışma sonucu keşfedilir. Mike, onun profesyonel bir suikastçı ve dolayısıyla suçlu olduğunu bilmektedir ve onu her ne şekilde olursa olsun yakalamak istemektedir.', 13, '2007-12-07', 'hitman-2007', 'images/movieBanner/1716408030_images (1).jpeg', 'images/movieBackground/1716408030_wp7111330-agent-movies-wallpapers.jpg', 'movies/hitmanFragman.mp4');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `userhistory`
--

CREATE TABLE `userhistory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `watched_at` enum('0','1') NOT NULL DEFAULT '0',
  `favorite` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `userhistory`
--

INSERT INTO `userhistory` (`id`, `user_id`, `movie_id`, `watched_at`, `favorite`) VALUES
(77, 2, 32, '1', '1'),
(78, 2, 31, '0', '1'),
(79, 2, 41, '1', '0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`) VALUES
(1, 'lastuser', '$2y$10$sGXrkNDcqXrGL4ga7mk7AeIKWvN32PuiunXMHlsrsDgcorj8VGALC', 'lastuser@hotmail.com', '0'),
(2, 'admin', '$2y$10$7J90fjYcGNiWLN/IJT7U6O2uvEyu1I.kjYNvY8t2dg8im4SPisrkC', 'admin@hotmail.com', '1');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Tablo için indeksler `userhistory`
--
ALTER TABLE `userhistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Tablo için AUTO_INCREMENT değeri `userhistory`
--
ALTER TABLE `userhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Tablo kısıtlamaları `userhistory`
--
ALTER TABLE `userhistory`
  ADD CONSTRAINT `userhistory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `userhistory_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

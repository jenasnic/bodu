-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 03 Juin 2014 à 06:11
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

TRUNCATE TABLE `banner`;
TRUNCATE TABLE `menu`;
TRUNCATE TABLE `news`;
TRUNCATE TABLE `picture`;
TRUNCATE TABLE `slideshow`;
TRUNCATE TABLE `section`;
TRUNCATE TABLE `skin`;
TRUNCATE TABLE `video`;

-- --------------------------------------------------------

--
-- Contenu de la table `banner`
--

INSERT INTO `banner` (`id`, `title`, `content`, `pictureUrl`, `rank`) VALUES
(1, 'Image 01', 'Contenu descriptif de l''image 01', '/userfiles/home/home_001.jpg', 1),
(2, 'Image 02', 'Contenu descriptif de l''image 02', '/userfiles/home/home_002.jpg', 2),
(3, 'Image 03', 'Contenu descriptif de l''image 03', '/userfiles/home/home_003.jpg', 3);

-- --------------------------------------------------------

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`id`, `name`, `url`, `rank`) VALUES
(1, 'Accueil', '/app_dev.php/', 1),
(2, 'Noir & Blanc', '/app_dev.php/r/1', 2),
(3, 'Couleur', '/app_dev.php/r/3', 4),
(4, 'Vidéos', '/app_dev.php/videos', 5),
(5, 'Invités', '#', 6),
(6, 'Actus', '/app_dev.php/news', 7),
(7, 'Contact', '#', 8);

-- --------------------------------------------------------

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `date`, `link`, `size`, `published`, `pictureUrl`, `rank`) VALUES
(1, 'Nam tempus pharetra justo', 'Nam tempus pharetra justo, a dictum nisi cursus a. Mauris euismod ligula in est varius bibendum. Donec et odio lacus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam non condimentum nisi. Fusce velit dui, ullamcorper ac dictum eget, sagittis eget eros. Nullam eu elementum tellus, eu consequat nisi. Donec massa nunc, gravida adipiscing nibh ut, posuere blandit justo. Donec ipsum lorem, sagittis in ornare sollicitudin, tempus nec eros.', '2014-03-07 00:00:00', 'http://www.google.fr', 'large', 1, '/userfiles/news/news_001.jpg', '1'),
(2, 'Morbi ultrices pulvinar nunc', 'Morbi ultrices pulvinar nunc, sit amet ullamcorper massa. Morbi lorem enim, suscipit a condimentum nec, hendrerit aliquam nisl. Curabitur sit amet tincidunt leo. Donec suscipit dolor at ipsum mollis fringilla. Ut congue lacus sit amet aliquet tempus. Mauris at augue ut ligula sollicitudin tempor. Phasellus tincidunt convallis pellentesque. Donec viverra dictum porttitor. Morbi nisi libero, interdum eu mauris id, condimentum ornare risus. ', '2014-04-11 00:00:00', 'http://www.google.fr', 'small', 1, '/userfiles/news/news_002.jpg', '2'),
(3, 'Integer accumsan venenatis nulla', 'Integer accumsan venenatis nulla, ut vulputate lectus convallis ut. Duis a tempor nulla. Donec porttitor ac ante ut laoreet. Mauris quam quam, commodo varius massa eget, faucibus gravida justo. Etiam metus sem, eleifend feugiat laoreet eu, porta ut odio. Vivamus tempor eros a tempor sodales. Cras quis quam elit. Aenean consequat sagittis eros eget sagittis. Maecenas hendrerit nibh dui, sed lobortis turpis ultrices eget. Vivamus hendrerit eleifend convallis. Sed non massa vehicula libero venenatis fermentum vitae non lectus. Donec condimentum gravida lacinia. Pellentesque sagittis nulla a elementum dictum.', '2014-05-25 00:00:00', 'http://www.google.fr', 'small', 1, '/userfiles/news/news_003.jpg', '3'),
(4, 'Sed aliquet orci libero', 'Sed aliquet orci libero, id imperdiet massa feugiat vitae. Ut vestibulum erat egestas ornare egestas. Morbi et accumsan tortor. Praesent in laoreet diam. Aenean aliquet, libero et scelerisque eleifend, est arcu interdum lorem, at dictum mi felis ac odio. Vestibulum egestas iaculis odio, sit amet venenatis libero convallis eget. Fusce sed blandit magna, at venenatis libero. Vivamus sagittis adipiscing commodo. Aliquam erat volutpat. Aenean tincidunt purus quis massa vehicula rutrum. Duis enim dolor, elementum a ipsum non, consectetur ornare nibh. Nulla lobortis fringilla tortor, at imperdiet neque ultrices non. Ut a mollis est, ac laoreet risus. ', '2014-06-19 00:00:00', 'http://www.google.fr', 'large', 1, '/userfiles/news/news_004.jpg', '4');

-- --------------------------------------------------------

--
-- Contenu de la table `skin`
--

INSERT INTO `skin` (`id`, `name`, `cssFile`) VALUES
(1, 'Bleu', 'resources/css/skin/blue.css'),
(2, 'Dark', 'resources/css/skin/dark.css'),
(3, 'Light', 'resources/css/skin/light.css'),
(4, 'BO', 'resources/css/skin/bo.css');

-- --------------------------------------------------------

--
-- Contenu de la table `section`
--

INSERT INTO `section` (`id`, `skin_id`, `name`, `description`) VALUES
(1, NULL, 'Noir & Blanc', 'Diaporamas avec des photos noir et blanc...'),
(2, 1, 'Galerie', 'Diaporamas divers...'),
(3, 2, 'Couleur', 'Diaporama avec des photos couleurs...');

-- --------------------------------------------------------

--
-- Contenu de la table `slideshow`
--

INSERT INTO `slideshow` (`id`, `section_id`, `name`, `description`, `published`, `activBorder`, `borderColor`, `thicknessValue`, `thumbnailPosition`, `rank`) VALUES
(1, 3, 'Couleur (vertical)', 'Diaporama avec des photos en couleur 01...', 1, 1, 'ff0000', 2, 'left', 1),
(2, 3, 'Autre couleur en vertical', 'Diaporama avec des photos en couleur 02...', 1, 1, '00ff00', 4, 'left', 2),
(3, 3, 'Couleur (horizontal)', 'Diaporama avec des photos en couleur 03...', 1, 1, '0000ff', 2, 'top', 3),
(4, 3, 'Autre couleur en horizontal', 'Diaporama avec des photos en couleur 04...', 1, 0, NULL, 0, 'top', 4),
(5, 1, 'N&B vertical', 'Diaporama avec des photos en noir et blanc 01...', 1, 1, 'ffffff', 2, 'left', 1),
(6, 1, 'N&B horizontal', 'Diaporama avec des photos en noir et blanc 01...', 1, 0, NULL, 0, 'top', 2),
(7, 1, 'Un autre diaporama en noir et blanc', 'Diaporama avec des photos en noir et blanc 01...', 1, 1, '000000', 2, 'top', 3);

-- --------------------------------------------------------

--
-- Contenu de la table `picture`
--

-- Diaporama for Section 3 'Couleur'
INSERT INTO `picture` (`id`, `slideshow_id`, `name`, `pictureUrl`, `thumbnailUrl`, `fullScreenUrl`, `vertical`, `rank`) VALUES
(1, 1, 'Photo D01 P01', '/userfiles/slideshow/slideshow_1/coul_horiz_001.jpg', '/userfiles/slideshow/slideshow_1/thumbnail/coul_horiz_001.jpg', '', 0, 1),
(2, 1, 'Photo D01 P02', '/userfiles/slideshow/slideshow_1/coul_horiz_002.jpg', '/userfiles/slideshow/slideshow_1/thumbnail/coul_horiz_002.jpg', '/userfiles/slideshow/slideshow_1/fullscreen/coul_horiz_002.jpg', 0, 2),
(3, 1, 'Photo D01 P03', '/userfiles/slideshow/slideshow_1/coul_horiz_003.jpg', '/userfiles/slideshow/slideshow_1/thumbnail/coul_horiz_003.jpg', '/userfiles/slideshow/slideshow_1/fullscreen/coul_horiz_003.jpg', 0, 3),
(4, 1, 'Photo D01 P04', '/userfiles/slideshow/slideshow_1/coul_vert_004.jpg', '/userfiles/slideshow/slideshow_1/thumbnail/coul_vert_004.jpg', '', 1, 4),
(5, 1, 'Photo D01 P05', '/userfiles/slideshow/slideshow_1/coul_vert_005.jpg', '/userfiles/slideshow/slideshow_1/thumbnail/coul_vert_005.jpg', '', 1, 5),
(6, 2, 'Photo D02 P01', '/userfiles/slideshow/slideshow_2/coul_horiz_001.jpg', '/userfiles/slideshow/slideshow_2/thumbnail/coul_horiz_001.jpg', '', 0, 1),
(7, 2, 'Photo D02 P02', '/userfiles/slideshow/slideshow_2/coul_horiz_002.jpg', '/userfiles/slideshow/slideshow_2/thumbnail/coul_horiz_002.jpg', '', 0, 2),
(8, 2, 'Photo D02 P03', '/userfiles/slideshow/slideshow_2/coul_horiz_003.jpg', '/userfiles/slideshow/slideshow_2/thumbnail/coul_horiz_003.jpg', '', 0, 3),
(9, 2, 'Photo D02 P04', '/userfiles/slideshow/slideshow_2/coul_vert_004.jpg', '/userfiles/slideshow/slideshow_2/thumbnail/coul_vert_004.jpg', '', 1, 4),
(10, 2, 'Photo D02 P05', '/userfiles/slideshow/slideshow_2/coul_vert_005.jpg', '/userfiles/slideshow/slideshow_2/thumbnail/coul_vert_005.jpg', '', 1, 5),
(11, 3, 'Photo D03 P01', '/userfiles/slideshow/slideshow_3/coul_horiz_001.jpg', '/userfiles/slideshow/slideshow_3/thumbnail/coul_horiz_001.jpg', '', 0, 1),
(12, 3, 'Photo D03 P02', '/userfiles/slideshow/slideshow_3/coul_horiz_002.jpg', '/userfiles/slideshow/slideshow_3/thumbnail/coul_horiz_002.jpg', '', 0, 2),
(13, 3, 'Photo D03 P03', '/userfiles/slideshow/slideshow_3/coul_horiz_003.jpg', '/userfiles/slideshow/slideshow_3/thumbnail/coul_horiz_003.jpg', '', 0, 3),
(14, 3, 'Photo D03 P04', '/userfiles/slideshow/slideshow_3/coul_vert_004.jpg', '/userfiles/slideshow/slideshow_3/thumbnail/coul_vert_004.jpg', '', 1, 4),
(15, 3, 'Photo D03 P05', '/userfiles/slideshow/slideshow_3/coul_vert_005.jpg', '/userfiles/slideshow/slideshow_3/thumbnail/coul_vert_005.jpg', '', 1, 5),
(16, 4, 'Photo D04 P01', '/userfiles/slideshow/slideshow_4/coul_horiz_001.jpg', '/userfiles/slideshow/slideshow_4/thumbnail/coul_horiz_001.jpg', '', 0, 1),
(17, 4, 'Photo D04 P02', '/userfiles/slideshow/slideshow_4/coul_horiz_002.jpg', '/userfiles/slideshow/slideshow_4/thumbnail/coul_horiz_002.jpg', '', 0, 2),
(18, 4, 'Photo D04 P03', '/userfiles/slideshow/slideshow_4/coul_horiz_003.jpg', '/userfiles/slideshow/slideshow_4/thumbnail/coul_horiz_003.jpg', '', 0, 3),
(19, 4, 'Photo D04 P04', '/userfiles/slideshow/slideshow_4/coul_vert_004.jpg', '/userfiles/slideshow/slideshow_4/thumbnail/coul_vert_004.jpg', '', 1, 4),
(20, 4, 'Photo D04 P05', '/userfiles/slideshow/slideshow_4/coul_vert_005.jpg', '/userfiles/slideshow/slideshow_4/thumbnail/coul_vert_005.jpg', '', 1, 5);

-- Diaporama for Section 1 'Noir & Blanc'
INSERT INTO `picture` (`id`, `slideshow_id`, `name`, `pictureUrl`, `thumbnailUrl`, `fullScreenUrl`, `vertical`, `rank`) VALUES
(21, 5, 'Photo D05 P01', '/userfiles/slideshow/slideshow_5/nb_horiz_001.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_horiz_001.jpg', '', 0, 1),
(22, 5, 'Photo D05 P02', '/userfiles/slideshow/slideshow_5/nb_horiz_002.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_horiz_002.jpg', '', 0, 2),
(23, 5, 'Photo D05 P03', '/userfiles/slideshow/slideshow_5/nb_horiz_003.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_horiz_003.jpg', '', 0, 3),
(24, 5, 'Photo D05 P04', '/userfiles/slideshow/slideshow_5/nb_vertic_004.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_vertic_004.jpg', '', 1, 4),
(25, 5, 'Photo D05 P05', '/userfiles/slideshow/slideshow_5/nb_vertic_005.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_vertic_005.jpg', '', 1, 5),
(26, 5, 'Photo D05 P01', '/userfiles/slideshow/slideshow_5/nb_vertic_006.jpg', '/userfiles/slideshow/slideshow_5/thumbnail/nb_vertic_006.jpg', '', 1, 6),
(27, 6, 'Photo D06 P01', '/userfiles/slideshow/slideshow_6/nb_horiz_001.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_horiz_001.jpg', '', 0, 1),
(28, 6, 'Photo D06 P02', '/userfiles/slideshow/slideshow_6/nb_horiz_002.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_horiz_002.jpg', '', 0, 2),
(29, 6, 'Photo D06 P03', '/userfiles/slideshow/slideshow_6/nb_horiz_003.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_horiz_003.jpg', '', 0, 3),
(30, 6, 'Photo D06 P04', '/userfiles/slideshow/slideshow_6/nb_vertic_004.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_vertic_004.jpg', '', 1, 4),
(31, 6, 'Photo D06 P05', '/userfiles/slideshow/slideshow_6/nb_vertic_005.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_vertic_005.jpg', '', 1, 5),
(32, 6, 'Photo D06 P01', '/userfiles/slideshow/slideshow_6/nb_vertic_006.jpg', '/userfiles/slideshow/slideshow_6/thumbnail/nb_vertic_006.jpg', '', 1, 6),
(33, 7, 'Photo D07 P01', '/userfiles/slideshow/slideshow_7/nb_horiz_001.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_horiz_001.jpg', '', 0, 1),
(34, 7, 'Photo D07 P02', '/userfiles/slideshow/slideshow_7/nb_horiz_002.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_horiz_002.jpg', '', 0, 2),
(35, 7, 'Photo D07 P03', '/userfiles/slideshow/slideshow_7/nb_horiz_003.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_horiz_003.jpg', '', 0, 3),
(36, 7, 'Photo D07 P04', '/userfiles/slideshow/slideshow_7/nb_vertic_004.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_vertic_004.jpg', '', 1, 4),
(37, 7, 'Photo D07 P05', '/userfiles/slideshow/slideshow_7/nb_vertic_005.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_vertic_005.jpg', '', 1, 5),
(38, 7, 'Photo D07 P01', '/userfiles/slideshow/slideshow_7/nb_vertic_006.jpg', '/userfiles/slideshow/slideshow_7/thumbnail/nb_vertic_006.jpg', '', 1, 6);

-- --------------------------------------------------------

--
-- Contenu de la table `video`
--

INSERT INTO `video` (`id`, `name`, `description`, `videoIntegration`, `published`, `rank`) VALUES
(1, 'Solar After Effects Experiment', 'Created inside of After Effects without additional 3rd Party plug-ins. From Andrew Kramer', '<iframe width="560" height="315" src="//www.youtube.com/embed/LsstKVAWPTc" frameborder="0" allowfullscreen></iframe>', 1, 1),
(2, 'Over The Edge', 'Free Adobe After Effects Tutorial. Music: "We''re Going Up" by Tim McMorris. From inlifethrill', '<iframe src="http://player.vimeo.com/v2/video/54837394?title=0&color=00a2dc" width="650" height="350" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', 1, 2),
(3, 'Spatial Experiment - Particular 2', 'This is the final render of my latest After Effects Tutorial on Trapcode Particular. From Mattias Peresini', '<iframe src="http://player.vimeo.com/v2/video/11711926?title=0&color=00a2dc" width="650" height="350" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', 1, 3);

-- --------------------------------------------------------

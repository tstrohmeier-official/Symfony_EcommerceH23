-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mar. 18 avr. 2023 à 02:57
-- Version du serveur : 10.10.2-MariaDB
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetsession`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `idCategory` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategory`, `category`) VALUES
(1, 'Dark Roast'),
(2, 'Medium Roast'),
(3, 'Light Roast'),
(4, 'Marble Roast'),
(5, 'Accessories'),
(6, 'Cups');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `orderDate` datetime NOT NULL,
  `deliveryDate` datetime DEFAULT NULL,
  `rateTPS` double NOT NULL,
  `rateTVQ` double NOT NULL,
  `deliveryFee` double NOT NULL,
  `state` varchar(85) NOT NULL,
  `stripeIntent` varchar(255) NOT NULL,
  `idPurchase` int(11) NOT NULL,
  `idProfil` int(11) NOT NULL,
  PRIMARY KEY (`idCommande`),
  KEY `IDX_F529939832B4FB60` (`idPurchase`),
  KEY `IDX_F529939885C71A0D` (`idProfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `idProduct` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `price` double NOT NULL,
  `quantityInStock` int(11) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `idMainCategory` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProduct`),
  KEY `IDX_B3BA5A5A21A797F2` (`idMainCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`idProduct`, `name`, `price`, `quantityInStock`, `description`, `imagePath`, `idMainCategory`) VALUES
(1, 'Guatemalan Antigua', 20, 0, 'A clean, pure, and comforting cup, this blend marries flavour and easy sipping.\r\n*Winner - Bronze medal - Pour Over Filter - at the 2019 Golden Bean Roasting Competition*\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nButter Cookies, Plum, Orange Zest\r\n\r\nAcidity: 2/5\r\nSweetness: 4/5\r\nBody: 2/5', 'images/products/guatemalanAntiguaLR.png', 3),
(2, 'Atwood Blend', 20, 0, 'As unforgettable as Atwood herself, this blend balances tang and sweetness with a compelling and undeniable smoothness.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nApple, Caramel, Cocoa\r\n\r\nAcidity: 3/5\r\nSweetness: 4/5\r\nBody: 3/5', 'images/products/atwoodBlendMR.png', 2),
(3, 'Expresso Blend', 20, 0, 'Based on traditional blends, our premium version yields a deep, rich crema, with a sweet and balanced profile. Great for getting things done.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nBrown sugar, Bakers chocolate, Macadamia\r\n\r\nAcidity: 2/5\r\nSweetness: 4/5\r\nBody: 4/5', 'images/products/expressoBlendMR.png', 2),
(4, 'Farmers\' Blend', 25, 0, 'As brisk and bright as a country morning, this hearty blend with a clean acidity is a Stratford favourite!\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nPine Nuts, Green apple, Chocolate\r\n\r\nAcidity: 3/5\r\nSweetness: 4/5\r\nBody: 3/5', 'images/products/farmersBlendMR.png', 2),
(5, 'Las Rosas', 36, 0, 'A dynamic and flavourful coffee with rich tannins balanced by full-bodied fruitiness and subtle sweetness.\r\n*Sourced from the Las Rosas Women’s Group in La Plata, Colombia*\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nPomegranate, Palm sugar, Black tea\r\n\r\nAcidity: 3/5\r\nSweetness: 4/5\r\nBody: 2/5', 'images/products/lasRosasMR.png\r\n', 2),
(6, 'Muramba', 18, 0, 'A sweet, balanced, creamy smooth coffee with floral, earthy notes this sensuous coffee is a favorite of artists and dreamers.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nFloral, earthy\r\n\r\nAcidity: 3/5\r\nSweetness: 2/5\r\nBody: 3/5', 'images/products/muramba.png', 2),
(7, 'Peru Penachi', 24, 0, 'A warm and balanced body with mild acidity, you can taste the sun-kissed Peruvian mountain ranges with every sip.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nNutty, malty, raisins\r\n\r\nAcidity: 2/5\r\nSweetness: 5/5\r\nBody: 4/5', 'images/products/peruPenachiMR.png', 2),
(8, 'A Dark Affair', 20, 0, 'This full-bodied blend boasts a rich and intense taste. Deep, dark, and a touch mysterious, this blend brews a serious cup.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nSmokey, roasted almonds, earthy\r\n\r\nAcidity: 0/5\r\nSweetness: 3/5\r\nBody: 5/5', 'images/products/aDarkAffairDR.png', 1),
(9, 'Bards Blend', 20, 0, 'Inspired by the the works of William Shakespeare, this thrilling blend tells a mighty tale with every sip and ends with a dramatic finish.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nBlueberry, cocoa powder, licorice\r\n\r\nAcidity: 0/5\r\nSweetness: 3/5\r\nBody: 5/5', 'images/products/bardsBlendDR.png', 1),
(10, 'Ethiopian Yirgacheffe', 20, 0, 'This single origin bean brings an electrifying and intense flavour, full of complex citrus and berry notes. A must try for bean aficionados.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nFloral, honeysuckle, taffy\r\n\r\nAcidity: 3/5\r\nSweetness: 3/5\r\nBody: 5/5', 'images/products/ethiopianYirgacheffeDR.png', 1),
(11, 'Swiss Water Decaf', 23, 0, 'Made with Colombian coffee and processed in the 100% chemical-free Swiss water decaffeination method. It won’t keep you up at night or let you down on flavour.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nHazelnut, earthy, pepper\r\n\r\nAcidity: 2/5\r\nSweetness: 4/5\r\nBody: 4/5', 'images/products/swissWaterDecafDR.png', 1),
(12, 'Balzac\'s Blend', 20, 0, 'Based on Honoré de Balzac’s own blend. Bold, rounded and generous, with subtle sweetness. Makes an excellent companion for writing.\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nMolasses, graham crackers, milk chocolate\r\n\r\nAcidity: 2/5\r\nSweetness: 4/5\r\nBody: 3/5', 'images/products/balzacsBlendMRR.png', 4),
(13, 'Winter Blend', 24, 0, 'Like the perfect snowy holiday morning, this blend balances plum sweetness with rich earthy tones. Pairs beautifully with gazing out a frosted window.\r\n\r\nSize: 340 g / 12 oz\r\n\r\nTaste profile:\r\nDried berries, dark chocolate, smoked almonds\r\n\r\nAcidity: 2/5\r\nSweetness: 4/5\r\nBody: 4/5', 'images/products/winterBlendMRR.png', 4),
(14, '1Zpreso J-Max', 270, 0, 'THE SIMPLER, THE BETTER\r\n\r\nDesigned with your enjoyment in mind, 1Zpresso grinders combine efficiency, consistency and simplicity to provide reliable performance for your coffee ritual.\r\n\r\nFeaturing titanium-coated 48mm burrs and super fine external adjustment ring with 90 settings per rotation and a progression of just 8.8 microns between settings, the J-Max Manual Coffee Grinder specialises in grinding for espresso of the highest quality. With a capacity of 35-40g and a magnetic catch cup for even greater ease of use, the J-Max is sleekly designed to provide an upscale grinding experience.\r\n\r\nIncludes cylinder case, air blower & double-sided cleaning brush.', 'images/products/1zpresoJ-max.png', 5),
(15, 'Craig Lyn Design', 1365, 0, 'PRIMED FOR PERFORMANCE\r\n\r\nBuilding on the legacy of the original HG-1, PRIME is the next step in the evolution of a tried and true design.\r\n\r\nIntroducing a host of mindful design improvements, with outstanding performance and a build quality that is second to none, the HG-1 PRIME offers the home barista the perfect platform for achieving a great cup of coffee—with all of the features you need and none of the ones you don\'t.\r\n\r\nAvailable with optional bamboo Skid Plate, and with your choice of redistribution tool.', 'images/products/craigLynDesign.png', 5),
(16, 'Baratza | Sette 270', 579, 0, 'REVOLUTIONARY DESIGN & EXCEPTIONAL FOR ESPRESSO\r\n\r\nThe Sette 270 introduces a revolutionary design to home espresso grinders, bringing professional functionality to home users.\r\n\r\nThe mouthfeel and flavour clarity of espresso pulled on the Sette simply out-classes other home espresso grinders. Thirty steps of macro-adjustment and a fully stepless micro-adjustment system give the user a near-infinite number of grind settings to allow for the most precise dial-in of any Baratza grinder, allowing even the most attentive taster to explore delicate layers of sweetness, acidity, and body for all types of espresso.\r\n\r\nExcelling at fine grinding up to and including small, single-cup AeroPress and pour over brews, the Sette 270 features Baratza\'s radically redesigned gearbox and burr set, in which the outer ring burr rotates around a stationary cone burr.', 'images/products/baratzaSette270.png', 5),
(17, 'Breville Milk Cafe Frother', 229.99, 0, 'Overview\r\nBreville\'s Milk Cafe is designed to produce creamy milk for coffee drinks and hot chocolate. It uses spinning and induction heating technology to produce tiny bubbles and smoother texture, and offers variable temperature settings to achieve perfect results.', 'images/products/brevilleMilkCafeFrother.png', 5),
(18, 'Instant Pot Milk Frother ', 59.98, 0, 'Overview\r\nCraft café-worthy beverages in a snap with the Instant Pot instant milk frother. This versatile frother creates warm microfoam for your lattes and cappuccinos or cool creamy foam for cold brews and iced coffees. It can even heat your coffee without any frothing at all for multifunctional options you\'ll use over and over again.', 'images/products/instantPotMilkFrother.png', 5),
(19, 'Nespresso Aeroccino 3', 99.97, 0, 'Overview\r\nCafe-quality beverages are easy to create at home thanks to this Nespresso Aeroccino 3 milk frother. It quickly produces cold milk foam, hot milk foam, and hot milk to add a rich and creamy finish to your coffee. It\'s incredibly easy to use with touch-button operation and its compact size sits neatly on any countertop.', 'images/products/nespressoAeroccino3MilkFrother.png', 5),
(20, 'Keurig Coffee Pod Storage', 34.99, 0, 'Overview\r\nKeep your K-Cup pods within convenient reach using this premium storage drawer. Designed to fit underneath your Keurig coffee maker to save space, this drawer is roomy enough to hold up to 35 coffee pods.', 'images/products/keurigRollingCofeeStorage.png', 5);

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

DROP TABLE IF EXISTS `profils`;
CREATE TABLE IF NOT EXISTS `profils` (
  `idProfil` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `postalCode` varchar(255) NOT NULL,
  `province` varchar(2) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  PRIMARY KEY (`idProfil`),
  UNIQUE KEY `UNIQ_75831F5EE7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profils`
--

INSERT INTO `profils` (`idProfil`, `email`, `lastName`, `firstName`, `password`, `address`, `town`, `postalCode`, `province`, `phone`, `roles`) VALUES
(3, '1232614@cstj.qc.ca', 'Strohmeier', 'Tom', '$argon2id$v=19$m=65536,t=4,p=1$MZQE+6c+L03oxgapwg8+fw$AlKLzEPC0BqjQFFxDaSpy8xORyR18fu4WGJO8BDaZ4w', '1433 rue des pierrots', 'sainte-adele', 'J8B3A5', 'QC', '4502297739', '[]');

-- --------------------------------------------------------

--
-- Structure de la table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `idPurchase` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`idPurchase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F529939832B4FB60` FOREIGN KEY (`idPurchase`) REFERENCES `purchase` (`idPurchase`),
  ADD CONSTRAINT `FK_F529939885C71A0D` FOREIGN KEY (`idProfil`) REFERENCES `profils` (`idProfil`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_BE2DDF8CF68A5CAC` FOREIGN KEY (`idMainCategory`) REFERENCES `categories` (`idCategory`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

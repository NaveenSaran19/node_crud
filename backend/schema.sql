-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sri_sakthi
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `media_url` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL DEFAULT 'image',
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--


/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (8,'Kanchipuram Kotta Cotton Sarees – Timeless Traditional Elegance','hero_section','uploads/banners/1792256260_2e7797b550d005a4a588.jpg','image','',1,'2026-10-17 16:57:40','2026-10-17 16:57:40'),(9,'Timeless Traditional Elegance','secondary-banner-carousel','uploads/banners/1792256292_b925d05a6ab280291f8d.jpg','image','',1,'2026-10-17 16:58:12','2026-10-17 16:58:12'),(10,'Diwali offer','diwali-dropdownvideo-banner','uploads/banners/1792256338_98d132610e2105380f00.jpg','image','',1,'2026-10-17 16:58:58','2026-10-17 16:58:58'),(11,'pure','secondary-banner-carousel','uploads/banners/1792256432_76c94968dbe895206f1d.jpg','image','',1,'2026-10-17 17:00:32','2026-10-17 17:00:32'),(12,'silk','hero_section','uploads/banners/1792256451_970f59219b93b6f8c8b7.jpg','image','',1,'2026-10-17 17:00:51','2026-10-17 17:00:51');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;


--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author` varchar(150) NOT NULL DEFAULT 'Selvi Tex',
  `published_at` date DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--


/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'The Grand Diwali Saree Collection Launch','grand-diwali-saree-collection-launch','Discover the intricate patterns and vibrant colors of our new festive collection. Handwoven to perfection for your special occasions.','<p>The festival of lights calls for timeless grace and radiance. This festive season, <strong>Selvi Tex</strong> proudly unveils our exclusive bridal and festival collection directly from our Elampillai looms.</p><p>Featuring rich zari brocades, contrasting pallus, and feather-light silk-cotton weaves, each piece is handcrafted over weeks by master weavers. Whether you seek regal deep jewel tones like royal sapphire, emerald green, and deep maroon or trending pastel hues, our new festive drops will illuminate your festivities.</p>','http://localhost/sri_sakthi/ssptex.com/cdn/shop/collections/exclusive-pure-silk-9427867_v=1785135584&width=600.jpg','Selvi Tex','2026-10-05','The Grand Diwali Saree Collection Launch | Selvi Tex','Explore the vibrant Diwali Saree Collection Launch from Selvi Tex, handwoven in Elampillai with rich zari and pure silk.','Diwali sarees, festive handlooms, Elampillai silk, bridal sarees, Selvi Tex','published','2026-10-16 23:58:11','2026-10-16 23:58:11'),(2,'Why South Cotton Sarees Are A Must-Have','why-south-cotton-sarees-are-must-have','Comfort meets elegance. Learn about the rich history of South Cotton and why it remains a staple in every Indian woman\'s wardrobe.','<p>South Indian cotton sarees have been beloved for centuries for their unparalleled breathability, regal drape, and all-day comfort. At <strong>Selvi Tex</strong>, we combine organic combed cotton yarn with subtle zari borders to create sarees that transition seamlessly from office meetings to evening temple visits.</p><p>Unlike synthetic blends, pure cotton sarees allow your skin to breathe in hot climates while lending an effortlessly poised and graceful aesthetic.</p>','http://localhost/sri_sakthi/ssptex.com/cdn/shop/collections/south-cotton-collection-3212632_v=1786017975&width=600.jpg','Selvi Tex','2026-09-28','Why South Cotton Sarees Are A Must-Have | Selvi Tex','Discover the comfort and heritage of pure South Cotton sarees from Selvi Tex weavers in Tamil Nadu.','South cotton sarees, daily wear sarees, comfortable sarees, Selvi Tex handlooms','published','2026-10-16 23:58:11','2026-10-16 23:58:11'),(3,'Behind the Loom: The Art of Pure Silk Weaving','behind-the-loom-art-of-pure-silk-weaving','Take an exclusive look into the workshops of our master weavers in Elampillai and the dedication that goes into every silk thread.','<p>Step inside our handloom weaving atelier in Elampillai, Salem. Here, time-honored wooden shuttle looms rhythmically dance from dawn to dusk. Every intricate butti, border motif, and zari pallu requires meticulous calculations and sheer manual dexterity.</p><p>By patronizing <strong>Selvi Tex</strong>, you are directly empowering our artisan families and preserving a glorious heritage craft that has defined Tamil Nadu handlooms for generations.</p>','http://localhost/sri_sakthi/ssptex.com/cdn/shop/collections/pure-venpattu-saree-collection-9624379_v=1786521269&width=600.jpg','Selvi Tex','2026-09-15','Behind the Loom: The Art of Pure Silk Weaving | Selvi Tex','A behind-the-scenes look into the master silk weavers of Selvi Tex in Elampillai, Salem.','silk weaving, Elampillai master weavers, handloom craftsmanship, pure silk, Selvi Tex','published','2026-10-16 23:58:11','2026-10-16 23:58:11');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;


--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--


/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (4,'Silk','silk','2026-10-09 04:30:36','2026-10-09 04:30:36',NULL,NULL,NULL,NULL,NULL),(8,'Cottons','cottons','2026-10-20 13:22:52','2026-10-27 02:45:38','uploads/settings/1793069138_83e068c64b2aa24b1a27.jpeg',NULL,NULL,NULL,NULL),(9,'Combo','combo','2026-10-27 05:41:18','2026-10-27 05:41:18','uploads/settings/1793079678_ce1ac8f1bde358724508.jpg',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


--
-- Table structure for table `enquiries`
--

DROP TABLE IF EXISTS `enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(50) DEFAULT 'New',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiries`
--


/*!40000 ALTER TABLE `enquiries` DISABLE KEYS */;
INSERT INTO `enquiries` VALUES (1,'Kavitha Devi','kavitha.devi@example.com','09087590967','Looking for wholesale wedding bulk saree orders in Elampillai.','New','2026-10-13 21:35:08','2026-10-13 21:35:08'),(2,'Naveen Saravanan','naaveensaaran.01319@gmail.com','09087590967','hi','New','2026-10-20 05:14:11','2026-10-20 05:14:11');
/*!40000 ALTER TABLE `enquiries` ENABLE KEYS */;


--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--


/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'How do I place an order?','Placing an order is simple and quick:\n\n• Step 1: Browse our saree collections and select your preferred color or variant.\n• Step 2: Click Add to Cart or hit Buy Now for instant checkout.\n• Step 3: Fill in your delivery address, phone number, and choose your preferred payment method (UPI, Net Banking, Cards, or Cash on Delivery).\n• Step 4: Confirm your order. You will receive an immediate SMS & WhatsApp confirmation with live tracking updates!','Ordering',1,1,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(2,'What payment methods do you accept?','We provide 100% safe and secure checkout options with all major payment gateways: Google Pay, PhonePe, Paytm, Any UPI ID / QR, Debit & Credit Cards, Net Banking, and Cash on Delivery (COD) across serviceable pin codes across India.','Payments',2,1,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(3,'Do you ship internationally?','Yes, we proudly ship worldwide! We dispatch authentic Elampillai sarees to customers in USA, UK, Canada, Australia, Singapore, Malaysia, UAE, Sri Lanka, and over 50+ countries via trusted international air couriers (DHL / FedEx / Aramex). International shipping rates and transit times (usually 5–8 working days) are dynamically calculated at checkout based on destination country and parcel weight.','Shipping',3,1,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(4,'Are these authentic Elampillai handloom sarees?','100% Authentic Weaver Direct Guarantee. Every saree featured in our boutique is masterfully crafted in Elampillai, Salem District, Tamil Nadu. Because we operate our own direct weaving looms and partner with heritage artisanal weavers at Selvi Tex, there are zero middlemen markups—giving you the finest quality silk and cotton at authentic weaver prices.','Quality',4,1,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(5,'How long does delivery take within India?','All domestic orders are carefully quality-checked and dispatched within 24 to 48 hours directly from our central Elampillai hub.\n\n• Tamil Nadu & South India: Delivered within 2 to 3 business days.\n• Rest of India: Delivered within 4 to 6 business days.\n• Free Express Shipping: Available on all domestic orders above ₹1,000!','Shipping',5,1,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(6,'What is your exchange and return policy?','We stand firmly behind the quality of every saree we weave. If you receive a product that is damaged or defective in transit, we provide a smooth 3-Day Replacement or Return Policy. Simply share a short parcel unboxing video with our WhatsApp support desk within 48 hours of delivery, and our customer care team will promptly arrange assistance.','Returns',6,1,'2026-10-16 23:58:10','2026-10-16 23:58:10');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;


--
-- Table structure for table `footer_links`
--

DROP TABLE IF EXISTS `footer_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `footer_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `section` varchar(100) NOT NULL DEFAULT 'customer_care',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_links`
--


/*!40000 ALTER TABLE `footer_links` DISABLE KEYS */;
INSERT INTO `footer_links` VALUES (1,'About Our Story','/pages/about-selvi-tex','customer_care',1,1,0,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(2,'Contact & Support','/policies/contact-information','customer_care',3,1,0,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(3,'Shipping & Delivery','/policies/shipping-policy','customer_care',4,1,0,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(4,'Returns & Refund Policy','/policies/refund-policy','customer_care',5,1,0,'2026-10-16 23:58:10','2026-10-16 23:58:10'),(5,'Privacy Policy','/policies/privacy-policy','customer_care',6,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(6,'Terms of Service','/policies/terms-of-service','customer_care',7,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(7,'Saree Care Blogs','/blogs/news','customer_care',8,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(8,'Pure Silk Sarees','/category/silk','our_collections',1,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(9,'Cotton Sarees','/category/cotton','our_collections',2,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(10,'Elampillai Sarees','/category/sarees','our_collections',3,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(11,'New Arrivals','/collections/new-arrivals','our_collections',4,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(12,'Best Sellers','/collections/best-sellers','our_collections',5,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(13,'All Saree Collections','/collections','our_collections',6,1,0,'2026-10-16 23:58:11','2026-10-16 23:58:11'),(14,'Contact Us','/contact-us','customer_care',2,1,0,'2026-10-20 10:41:17','2026-10-20 10:41:17');
/*!40000 ALTER TABLE `footer_links` ENABLE KEYS */;


--
-- Table structure for table `gst_rates`
--

DROP TABLE IF EXISTS `gst_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `gst_rates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rate` decimal(5,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gst_rates`
--


/*!40000 ALTER TABLE `gst_rates` DISABLE KEYS */;
INSERT INTO `gst_rates` VALUES (1,0.00,'2026-10-10 16:10:20',NULL),(2,5.00,'2026-10-10 16:10:20',NULL),(3,12.00,'2026-10-10 16:10:20',NULL),(4,18.00,'2026-10-10 16:10:20',NULL),(5,28.00,'2026-10-10 16:10:20',NULL);
/*!40000 ALTER TABLE `gst_rates` ENABLE KEYS */;


--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--


/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-10-08-171259','App\\Database\\Migrations\\CreateCategoriesTable','default','App',1791479613,1),(2,'2026-10-08-171300','App\\Database\\Migrations\\CreateProductsTable','default','App',1791479613,1),(3,'2026-10-08-171301','App\\Database\\Migrations\\CreateUsersTable','default','App',1791479634,2),(4,'2026-10-08-171302','App\\Database\\Migrations\\CreateOrdersTable','default','App',1791479634,2),(5,'2026-10-08-171303','App\\Database\\Migrations\\CreateOrderItemsTable','default','App',1791479634,2),(6,'2026-10-08-175833','App\\Database\\Migrations\\AddParentIdToCategories','default','App',1791482341,3),(7,'2026-10-09-024513','App\\Database\\Migrations\\UpdateCategoriesTable','default','App',1791514003,4),(8,'2026-10-09-024524','App\\Database\\Migrations\\UpdateProductsTable','default','App',1791514003,4),(9,'2026-10-09-024528','App\\Database\\Migrations\\CreateProductMediaTable','default','App',1791514003,4),(10,'2026-10-09-024610','App\\Database\\Migrations\\CreateProductVariantsTable','default','App',1791514004,4),(11,'2026-10-09-024615','App\\Database\\Migrations\\CreateBannersTable','default','App',1791514004,4),(12,'2026-10-09-030411','App\\Database\\Migrations\\CreateSettingsTable','default','App',1791515083,5),(13,'2026-10-09-141811','App\\Database\\Migrations\\CreateSubcategoriesTable','default','App',1791555518,6),(14,'2026-10-09-143353','App\\Database\\Migrations\\AddImagesToSubcategories','default','App',1791556468,7),(15,'2026-10-10-160904','App\\Database\\Migrations\\AddSkuAndGstToProducts','default','App',1791648620,8),(16,'2026-10-10-160920','App\\Database\\Migrations\\CreateGstRatesTable','default','App',1791648620,8),(17,'2026-10-10-161953','App\\Database\\Migrations\\AddDisplaySettingsToProducts','default','App',1791857291,9),(18,'2026-10-10-162959','App\\Database\\Migrations\\AddStockAndBlouseToProducts','default','App',1791857291,9),(19,'2026-10-11-140000','App\\Database\\Migrations\\CreateEnquiriesTable','default','App',1791857291,9),(20,'2026-10-11-155500','App\\Database\\Migrations\\AddShortsChannelToProducts','default','App',1791857291,9),(21,'2026-10-13-120000','App\\Database\\Migrations\\AddMeasurementsAndHighlightsToProducts','default','App',1791857297,10),(22,'2026-10-13-130000','App\\Database\\Migrations\\UpdateProductVariantsAndMediaTables','default','App',1791857320,11),(23,'2026-10-14-020000','App\\Database\\Migrations\\AddVideoUrlToProducts','default','App',1792175285,12),(24,'2026-10-17-000000','App\\Database\\Migrations\\CreateContentManagementTables','default','App',1792175285,12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;


--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `variant_id` int(10) unsigned DEFAULT NULL,
  `variant_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--


/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (2,4,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue Free Size',1,1230.00),(3,5,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue Free Size',1,1230.00),(4,6,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink Free Size',1,1499.00),(5,7,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink Free Size',1,1499.00),(6,8,20,'Flower Pots Deluxe',12,'Pink',1,1.00),(7,8,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(8,8,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00),(9,9,20,'Flower Pots Deluxe',12,'Pink',1,1.00),(10,10,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(11,11,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(12,12,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(13,13,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(14,14,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(15,15,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(16,16,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(17,17,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(18,18,19,'Kanchipuram Malmal Cotton Soft Saree',11,'Pink',1,0.92),(19,19,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00),(20,20,26,'kids',21,'Pink 1-2 Yrs, 2-3 Yrs, 3-4 Yrs, 4-5 Yrs',1,1000.00),(21,21,26,'kids',21,'Pink 1-2 Yrs, 2-3 Yrs, 3-4 Yrs, 4-5 Yrs',1,1000.00),(22,22,26,'kids',21,'Pink 1-2 Yrs, 2-3 Yrs, 3-4 Yrs, 4-5 Yrs',1,1.00),(23,23,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00),(24,24,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00),(25,25,26,'kids',21,'Pink (3-4 Yrs)',1,1.00),(26,26,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00),(27,27,18,'Kanchipuram Kotta Cotton Traditional Saree',9,'blue',1,1.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;


--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(100) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_pincode` varchar(20) DEFAULT NULL,
  `shipping_country` varchar(50) DEFAULT 'India',
  `billing_address` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tip_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `payment_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `email_consent` tinyint(1) NOT NULL DEFAULT 0,
  `phone_consent` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--


/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,'ST-3B6EA243',2,4586.94,'Cash on Delivery','Processing','2026-10-13 03:31:31','Meena Sundaram','testcustomer@example.com','9876543210','12A, Temple Street, Elampillai, Opposite Sivan Kovil','Salem','Tamil Nadu','637502','India','12A, Temple Street, Elampillai, Opposite Sivan Kovil',4497.00,0.00,89.94,0.00,1503.00,NULL,NULL,NULL,'Pending (COD)',1,1,'2026-10-13 03:31:31'),(4,'ST-1412AB78',3,1230.00,'Cash on Delivery','Shipped','2026-10-20 06:21:05','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1230.00,0.00,0.00,0.00,170.00,NULL,NULL,NULL,'Paid',1,0,'2026-10-20 06:47:48'),(5,'ST-82183F30',3,1230.00,'Cash on Delivery','Processing','2026-10-20 06:23:04','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1230.00,0.00,0.00,0.00,170.00,NULL,NULL,NULL,'Paid',1,0,'2026-10-20 06:48:24'),(6,'ST-32108668',3,1499.00,'Cash on Delivery','Processing','2026-10-20 06:44:51','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1499.00,0.00,0.00,0.00,501.00,NULL,NULL,NULL,'Pending (COD)',1,0,'2026-10-20 06:44:51'),(7,'ST-28614330',3,1499.00,'Razorpay Secure','Pending','2026-10-20 07:12:53','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1499.00,0.00,0.00,0.00,501.00,'order_Tq4lOh8fNnHWeW',NULL,NULL,'Payment Pending',1,0,'2026-10-20 07:12:53'),(8,'ST-03C27C40',3,3.92,'Razorpay Secure','Pending','2026-10-20 07:18:42','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',2.92,1.00,0.00,0.00,5397.08,'order_Tq4rXOz8Ft702X',NULL,NULL,'Payment Pending',1,0,'2026-10-20 07:18:42'),(9,'ST-D86D0A34',3,2.00,'Razorpay Secure','Processing','2026-10-20 07:19:43','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1.00,1.00,0.00,0.00,1999.00,'order_Tq4scPuztExkQA','pay_Tq4sxAaflq0ipm','b980e92e23010eb144a3e7ce22dd9074bfb2dc19c9b2640c9679c7f23f3687a6','Paid',1,0,'2026-10-20 07:20:23'),(10,'ST-8E509362',3,1.92,'Cash on Delivery','Processing','2026-10-20 08:02:32','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,1,'2026-10-20 08:02:32'),(11,'ST-CB185D64',3,1.92,'Cash on Delivery','Processing','2026-10-20 08:03:56','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,1,'2026-10-20 08:03:56'),(12,'ST-87F57013',3,1.92,'Razorpay Secure','Pending','2026-10-20 08:14:50','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,'order_Tq5oqClma91erU',NULL,NULL,'Payment Pending',1,1,'2026-10-20 08:14:50'),(13,'ST-7905C440',3,1.92,'Cash on Delivery','Processing','2026-10-20 08:15:03','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,1,'2026-10-20 08:15:03'),(14,'ST-4B6B9C96',3,1.92,'Razorpay Secure','Pending','2026-10-20 09:38:13','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Kalaivani College of Education','Salem','Tamil Nadu','636015','United Arab Emirates','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Kalaivani College of Education',0.92,1.00,0.00,0.00,1999.08,'order_Tq7EvWvGRO31Xb',NULL,NULL,'Payment Pending',1,0,'2026-10-20 09:38:13'),(15,'ST-C907DE56',3,1.92,'Razorpay Secure','Pending','2026-10-20 09:39:25','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','Australia','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,'order_Tq7GBgPv4J38cs',NULL,NULL,'Payment Pending',1,0,'2026-10-20 09:39:25'),(16,'ST-D3D37F16',3,1.92,'Cash on Delivery','Processing','2026-10-20 09:42:21','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,0,'2026-10-20 09:42:21'),(17,'ST-C63AA890',3,1.92,'Cash on Delivery','Processing','2026-10-20 10:22:52','Naveen S','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,0,'2026-10-20 10:22:52'),(18,'ST-B092AB31',3,1.92,'Cash on Delivery','Processing','2026-10-20 10:23:39','Sampath','naveensaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Kalaivani College of Education','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Kalaivani College of Education',0.92,1.00,0.00,0.00,1999.08,NULL,NULL,NULL,'Pending (COD)',1,0,'2026-10-20 10:23:39'),(19,'ST-CDB6E333',5,2.00,'Razorpay Secure','Processing','2026-10-20 13:44:14','NAVEEN SARAVANAN','naveeensaran.19121@gmail.com','9087590967','E/504,Aspathiyar kaadu,Nellukuthai Paarai,Erumapalayam,Salem','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu,Nellukuthai Paarai,Erumapalayam,Salem',1.00,1.00,0.00,0.00,1399.00,'order_TqBQnnPofJJexC','pay_TqBRH6B8zBdvoC','9f7460f237781225db5dd4ce7efa7c2b5f3b5886126039da5a109f96d37895a0','Paid',1,0,'2026-10-20 13:45:01'),(20,'ST-39315678',3,1000.00,'Razorpay Secure','Pending','2026-11-10 05:07:33','Naveen S','naaveensaaran.01319@gmail.com','9087590967','2/504 E,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital','Salem','Tamil Nadu','636015','India','2/504 E,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital',1000.00,0.00,0.00,0.00,100.00,'order_TyLqW2o2suD7Mc',NULL,NULL,'Payment Pending',0,0,'2026-11-10 05:07:33'),(21,'ST-40E45796',3,1000.00,'Razorpay Secure','Pending','2026-11-10 05:08:05','Naveen S','naaveensaaran.01319@gmail.com','9087590967','2/504 E,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital','Salem','Tamil Nadu','636015','India','2/504 E,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital',1000.00,0.00,0.00,0.00,100.00,'order_TyLr5XKuU6tqOj',NULL,NULL,'Payment Pending',0,0,'2026-11-10 05:08:05'),(22,'ST-755DBD17',3,1.00,'Razorpay Secure','Processing','2026-11-10 05:09:59','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam, Govt Hospital',1.00,0.00,0.00,0.00,1099.00,'order_TyLt6JYTFgu7IF','pay_TyLtceXApjBBjW','e215cb730d96c8ebb3d26d30dcbad16b3bc2fdefcbc9c1c2a4a257d9394059c7','Paid',1,0,'2026-11-10 05:10:46'),(23,'ST-5B3AC036',3,1.00,'Razorpay Secure','Pending','2026-11-10 05:43:50','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India',', ',1.00,0.00,0.00,0.00,1399.00,'order_TyMSrAgFA1cq5C',NULL,NULL,'Payment Pending',1,0,'2026-11-10 05:43:50'),(24,'ST-8B062510',3,1.00,'Razorpay Secure','Processing','2026-11-10 05:44:25','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1.00,0.00,0.00,0.00,1399.00,'order_TyMTTFYkB0QKl7','pay_TyMU4jfZu7ZuhK','e6e62a97560bb712e7f17625f697bd13ab59fd24c420483e0493e3260acef109','Paid',1,1,'2026-11-10 05:45:19'),(25,'ST-C8B16129',3,1.00,'Razorpay Secure','Processing','2026-11-10 05:53:49','Naveen Saravanan','naaveensaaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1.00,0.00,0.00,0.00,1099.00,'order_TyMdOivqQXrnDA','pay_TyMe4fS7oRJAiu','314396f84be420584ea33af444367bb771019c4ef022a916fd98a43094f4fe18','Paid',1,0,'2026-11-10 05:54:45'),(26,'ST-F51C6315',6,1.00,'Razorpay Secure','Processing','2026-11-10 07:21:04','Naveen Saravanan','naveensaran.01319@gmail.com','9087590967','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam','Salem','Tamil Nadu','636015','India','E/504,Aspathiyar kaadu Nellu kuthai paarai,Erumapalayam',1.00,0.00,0.00,0.00,1399.00,'order_TyO7YiYUhJYh9R','pay_TyO88uMTkO5gn9','a8a0a006a27347d27f5ef5f0f08c8bfbcc5c96529684fdfc0142add878ba5ae6','Paid',1,0,'2026-11-10 07:21:55'),(27,'ST-E5B83C96',7,1.00,'Razorpay Secure','Processing','2026-11-10 07:26:39','Hayathbasha','hayathbashascorp98@gmail.com','9786543452','45, nara','Dharmapuri','Tamil Nadu','636701','India','45, nara',1.00,0.00,0.00,0.00,1399.00,'order_TyODSZDcuDxNnh','pay_TyODsqh8BrpQH2','32031387142e5ab091bb59405caf9a8ac59e15b9d88d8ed983f7f15627db58f3','Paid',1,0,'2026-11-10 07:27:19');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;


--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--


/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'About Selvi Tex','about-selvi-tex','<h2>Weaving Dreams into Reality</h2><p>\r\n</p><p>Welcome to <strong>Selvi Tex</strong>, where tradition meets contemporary elegance. For years, we have been at the forefront of bringing you the finest silk and cotton sarees, directly from the master weavers of Elampillai, Salem District, Tamil Nadu.</p><p>\r\n\r\n</p><p>Our journey began with a simple vision: to preserve the rich cultural heritage of Indian textiles while making them accessible to women around the world. Every saree in our collection is a testament to the skill, patience, and artistry of our talented weavers.</p><p>\r\n\r\n</p><blockquote>\r\n\"Every thread woven into our sarees carries generations of Elampillai heritage, artisanal dedication, and timeless Tamil Nadu craftsmanship.\"\r\n</blockquote><p>\r\n\r\n</p><h3>Our Heritage &amp; Commitment</h3><p>\r\n</p><p>Established in 2010 in Elampillai, <strong>Selvi Tex</strong> operates direct handloom weaving looms and partners with skilled master craftsmen. We take immense pride in ensuring that authentic Indian handloom artistry thrives without the exploitation of middlemen, allowing our weavers fair livelihoods and our customers uncompromised quality at direct weaver prices.</p><p>\r\n\r\n</p><h3>Why Choose Selvi Tex?</h3><p>\r\n</p><p><br></p>','About Selvi Tex | Authentic Elampillai Pure Silk & Handloom Sarees','Discover Selvi Tex, authentic Elampillai pure silk and cotton sarees crafted directly by master weavers in Salem, Tamil Nadu.','Selvi Tex, Elampillai sarees, pure silk sarees, cotton sarees, Salem handlooms','uploads/pages/1792259992_539c68e001b42ae1e1a3.jpg','uploads/pages/1792263120_f9270172f43c6bfc8913.jpg','published','2026-10-16 23:58:10','2026-10-17 18:52:00'),(2,'Contact & Support','contact-information','<div class=\"contact-page-modern\" style=\"font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; line-height: 1.6; color: #334155;\">\n\n  <div style=\"background: linear-gradient(135deg, #7a1530 0%, #4a0d1d 100%); color: #fff; padding: 36px 28px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 10px 25px rgba(122,21,48,0.18);\">\n    <span style=\"background: rgba(212,175,55,0.25); color: #ffd700; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; letter-spacing: 1.5px; text-transform: uppercase;\">Direct Weaver Support</span>\n    <h2 style=\"font-size: 28px; font-weight: 800; margin: 12px 0 8px 0; color: #fff; font-family: \'Playfair Display\', Georgia, serif;\">Customer Care &amp; Support</h2>\n    <p style=\"font-size: 15px; color: #fce7f3; margin: 0 0 16px 0; max-width: 650px;\">Have questions about our Elampillai handloom sarees, order tracking, bulk wholesale enquiries, or custom weave requests? We are here to help!</p>\n    <a href=\"/sri/public/contact-us\" style=\"display: inline-flex; align-items: center; gap: 8px; background: #ffd700; color: #1e1e1e; padding: 10px 20px; border-radius: 8px; font-weight: 800; font-size: 13.5px; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.18);\">\n      <i class=\"fa-solid fa-paper-plane\"></i> Send Online Enquiry Form &rarr;\n    </a>\n  </div>\n\n  <div style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;\">\n    <!-- Phone Support Card -->\n    <div style=\"background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);\">\n      <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #fdf2f4; color: #7a1530; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 14px;\">\n        <i class=\"fa-solid fa-phone\"></i>\n      </div>\n      <h4 style=\"font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;\">Customer Care Phone</h4>\n      <p style=\"font-size: 13px; color: #64748b; margin: 0 0 12px 0;\">Monday – Sunday: 9:00 AM – 9:00 PM IST</p>\n      <a href=\"tel:9789296751\" style=\"display: inline-block; font-size: 18px; font-weight: 800; color: #7a1530; text-decoration: none;\">9789296751</a>\n    </div>\n\n    <!-- WhatsApp Support Card -->\n    <div style=\"background: #ffffff; border: 1.5px solid #dcfce7; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);\">\n      <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 14px;\">\n        <i class=\"fa-brands fa-whatsapp\"></i>\n      </div>\n      <h4 style=\"font-size: 16px; font-weight: 800; color: #166534; margin: 0 0 6px 0;\">WhatsApp Chat Support</h4>\n      <p style=\"font-size: 13px; color: #15803d; margin: 0 0 12px 0;\">Instant saree selection, photos &amp; video walkthroughs</p>\n      <a href=\"https://wa.me/919789296751?text=Hello%20Selvi%20Tex%2C%20I%20have%20an%20inquiry%20about%20sarees.\" target=\"_blank\" style=\"display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 700; text-decoration: none;\">\n        <i class=\"fa-brands fa-whatsapp\" style=\"font-size: 17px;\"></i> Chat on WhatsApp (9789296751)\n      </a>\n    </div>\n\n    <!-- Email Support Card -->\n    <div style=\"background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);\">\n      <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 14px;\">\n        <i class=\"fa-solid fa-envelope\"></i>\n      </div>\n      <h4 style=\"font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;\">Official Email</h4>\n      <p style=\"font-size: 13px; color: #64748b; margin: 0 0 12px 0;\">We respond within 24 hours</p>\n      <a href=\"mailto:chinnabotanyelm@gmail.com\" style=\"display: inline-block; font-size: 15px; font-weight: 700; color: #2563eb; text-decoration: none; word-break: break-all;\">chinnabotanyelm@gmail.com</a>\n    </div>\n  </div>\n\n  <!-- Physical Showroom Address -->\n  <div style=\"background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 30px;\">\n    <div style=\"display: flex; align-items: flex-start; gap: 16px; flex-wrap: wrap;\">\n      <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #fdf2f4; color: #7a1530; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;\">\n        <i class=\"fa-solid fa-location-dot\"></i>\n      </div>\n      <div style=\"flex: 1; min-width: 260px;\">\n        <span style=\"font-size: 11px; font-weight: 800; color: #7a1530; text-transform: uppercase; letter-spacing: 1px;\">Flagship Showroom &amp; Weaving Center</span>\n        <h3 style=\"font-size: 18px; font-weight: 800; color: #0f172a; margin: 4px 0 8px 0;\">Sri Sakthi Selvi Tex</h3>\n        <p style=\"font-size: 14.5px; color: #475569; margin: 0 0 8px 0; line-height: 1.6;\">\n          D/No.13/10, Periya Mammu St, Elampillai to Chinnapampattii, Main Road,<br>Near Bavana Ganapathi Kovil, Elampillai - 637502\n        </p>\n        <div style=\"display: flex; gap: 14px; flex-wrap: wrap; margin-top: 12px;\">\n          <a href=\"https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai\" target=\"_blank\" style=\"display: inline-flex; align-items: center; gap: 6px; background: #7a1530; color: #fff; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none;\">\n            <i class=\"fa-solid fa-diamond-turn-right\"></i> Open Directions in Google Maps\n          </a>\n          <a href=\"tel:9789296751\" style=\"display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #cbd5e1; color: #1e293b; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none;\">\n            <i class=\"fa-solid fa-phone\"></i> Call: 9789296751\n          </a>\n        </div>\n      </div>\n    </div>\n  </div>\n\n  <!-- ======================================================== -->\n  <!-- GET IN TOUCH WITH SELVI TEX CONTAINER -->\n  <!-- ======================================================== -->\n  <div class=\"get-in-touch-container\" style=\"background: linear-gradient(180deg, #fefbfc 0%, #fdf5f7 100%); border: 2px solid #f2d9e0; border-radius: 16px; padding: 28px; margin-bottom: 30px; box-shadow: 0 8px 24px rgba(122,21,48,0.06);\">\n    <div style=\"display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; border-bottom: 1px solid #ebd0d8; padding-bottom: 16px;\">\n      <div>\n        <span style=\"font-size: 11.5px; font-weight: 800; color: #7a1530; text-transform: uppercase; letter-spacing: 1.5px; display: inline-flex; align-items: center; gap: 6px;\">\n          <i class=\"fa-solid fa-handshake-angle\"></i> Connect Across All Platforms\n        </span>\n        <h3 style=\"font-size: 22px; font-weight: 900; color: #1a080e; margin: 4px 0 0 0; font-family: \'Playfair Display\', Georgia, serif;\">\n          Get in Touch with Selvi Tex\n        </h3>\n      </div>\n      <span style=\"font-size: 13px; color: #7a1530; font-weight: 600; background: #ffffff; border: 1px solid #eecad3; padding: 5px 14px; border-radius: 20px;\">\n        Direct Contact &amp; Official Social Handles\n      </span>\n    </div>\n\n    <div style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;\">\n      \n      <!-- WhatsApp Card -->\n      <a href=\"https://wa.me/919789296751?text=Hello%20Selvi%20Tex%2C%20I%20would%20like%20to%20get%20in%20touch.\" target=\"_blank\" style=\"display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1.5px solid #bbf7d0; padding: 14px 18px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(37,211,102,0.08);\">\n        <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #25D366; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(37,211,102,0.3);\">\n          <i class=\"fa-brands fa-whatsapp\"></i>\n        </div>\n        <div>\n          <div style=\"font-size: 11px; color: #15803d; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;\">WhatsApp Number</div>\n          <div style=\"font-size: 15px; font-weight: 800; color: #166534;\">9789296751</div>\n          <div style=\"font-size: 11.5px; color: #64748b; margin-top: 2px;\">Instant Chat Support</div>\n        </div>\n      </a>\n\n      <!-- YouTube Card -->\n      <a href=\"https://youtube.com/@srisakthiselvitex\" target=\"_blank\" style=\"display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1.5px solid #fecaca; padding: 14px 18px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(255,0,0,0.06);\">\n        <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #FF0000; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(255,0,0,0.25);\">\n          <i class=\"fa-brands fa-youtube\"></i>\n        </div>\n        <div>\n          <div style=\"font-size: 11px; color: #991b1b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;\">YouTube Channel</div>\n          <div style=\"font-size: 14.5px; font-weight: 800; color: #991b1b;\">@srisakthiselvitex</div>\n          <div style=\"font-size: 11.5px; color: #64748b; margin-top: 2px;\">Watch Saree Demos</div>\n        </div>\n      </a>\n\n      <!-- Facebook Card -->\n      <a href=\"https://facebook.com/srisakthiselvitex\" target=\"_blank\" style=\"display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1.5px solid #bfdbfe; padding: 14px 18px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(24,119,242,0.08);\">\n        <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #1877F2; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(24,119,242,0.25);\">\n          <i class=\"fa-brands fa-facebook-f\"></i>\n        </div>\n        <div>\n          <div style=\"font-size: 11px; color: #1e40af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;\">Facebook Page</div>\n          <div style=\"font-size: 14.5px; font-weight: 800; color: #1e40af;\">srisakthiselvitex</div>\n          <div style=\"font-size: 11.5px; color: #64748b; margin-top: 2px;\">Community &amp; Posts</div>\n        </div>\n      </a>\n\n      <!-- Instagram Card -->\n      <a href=\"https://instagram.com/srisakthiselvitex\" target=\"_blank\" style=\"display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1.5px solid #fbcfe8; padding: 14px 18px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(225,48,108,0.08);\">\n        <div style=\"width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(225,48,108,0.25);\">\n          <i class=\"fa-brands fa-instagram\"></i>\n        </div>\n        <div>\n          <div style=\"font-size: 11px; color: #9d174d; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;\">Instagram Link</div>\n          <div style=\"font-size: 14.5px; font-weight: 800; color: #9d174d;\">@srisakthiselvitex</div>\n          <div style=\"font-size: 11.5px; color: #64748b; margin-top: 2px;\">Daily Reels &amp; Stories</div>\n        </div>\n      </a>\n\n      <!-- Twitter / X Card -->\n      <a href=\"https://twitter.com/srisakthiselvitex\" target=\"_blank\" style=\"display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1.5px solid #cbd5e1; padding: 14px 18px; border-radius: 12px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(15,23,42,0.06);\">\n        <div style=\"width: 44px; height: 44px; border-radius: 12px; background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(15,23,42,0.25);\">\n          <i class=\"fa-brands fa-x-twitter\"></i>\n        </div>\n        <div>\n          <div style=\"font-size: 11px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;\">Twitter / X Link</div>\n          <div style=\"font-size: 14.5px; font-weight: 800; color: #0f172a;\">@srisakthiselvitex</div>\n          <div style=\"font-size: 11.5px; color: #64748b; margin-top: 2px;\">Official Announcements</div>\n        </div>\n      </a>\n\n    </div>\n  </div>\n\n  <!-- Interactive Google Maps Showroom Section -->\n  <div style=\"margin-top: 15px; border-radius: 16px; overflow: hidden; border: 2px solid #e8e0d5; box-shadow: 0 10px 30px rgba(0,0,0,0.06);\">\n    <div style=\"background: #ffffff; padding: 14px 20px; border-bottom: 1px solid #e8e0d5; font-size: 13.5px; font-weight: 700; color: #1e293b; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;\">\n      <span><i class=\"fa-solid fa-map-location-dot\" style=\"color: #7a1530; margin-right: 8px;\"></i> Visit Our Showroom: Sri Sakthi Selvi Tex, Elampillai</span>\n      <a href=\"https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai\" target=\"_blank\" style=\"color: #7a1530; text-decoration: none; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;\">\n        <span>Open in Google Maps</span> <i class=\"fa-solid fa-arrow-up-right-from-square\" style=\"font-size: 11px;\"></i>\n      </a>\n    </div>\n    <iframe src=\"https://maps.google.com/maps?q=Sri+Sakthi+Selvi+Tex+Main+Road+Elampillai+Salem+Tamil+Nadu+637502&t=&z=15&ie=UTF8&iwloc=&output=embed\" width=\"100%\" height=\"400\" style=\"border:0; display: block;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>\n  </div>\n\n</div>','Contact & Support | Selvi Tex','Get in touch with Selvi Tex for saree orders, customer assistance, wholesale enquiries, and showroom visits in Elampillai.','contact Selvi Tex, customer support, Elampillai address, whatsapp support',NULL,NULL,'published','2026-10-16 23:58:10','2026-10-16 23:58:10'),(3,'Shipping & Delivery','shipping-policy','<h2>Shipping & Delivery Information</h2>\n<p>At <strong>Selvi Tex</strong>, every saree is meticulously inspected, steam-pressed, and packed securely before dispatch.</p>\n<h3>Dispatch & Delivery Timelines</h3>\n<ul>\n  <li><strong>Dispatch Time:</strong> Orders are dispatched within 24 to 48 business hours from our central Elampillai weaving hub.</li>\n  <li><strong>Tamil Nadu & South India:</strong> Delivered within 2 to 3 business days.</li>\n  <li><strong>Rest of India:</strong> Delivered within 4 to 6 business days via Professional Couriers, ST Courier, DTDC, or India Post Speed Post.</li>\n  <li><strong>Pre-booking Sarees:</strong> High-demand or custom handloom pre-booking sarees will be delivered according to the date mentioned on the product page.</li>\n</ul>\n<h3>Shipping Charges</h3>\n<ul>\n  <li><strong>Free Express Shipping:</strong> Available on all domestic orders across India above ₹1,000!</li>\n  <li><strong>Standard Domestic Shipping:</strong> Nominal flat shipping fee of ₹50 for orders below ₹1,000.</li>\n  <li><strong>International Shipping:</strong> Calculated at checkout based on destination country and parcel weight via DHL / FedEx.</li>\n</ul>','Shipping & Delivery Policy | Selvi Tex','Fast, secure doorstep delivery across India and worldwide for authentic Elampillai handlooms from Selvi Tex.','shipping policy, delivery time, courier partners, free shipping',NULL,NULL,'published','2026-10-16 23:58:10','2026-10-16 23:58:10'),(4,'Returns & Refund Policy','refund-policy','<h2>Returns & Refund Policy – Selvi Tex</h2>\n<p>We take immense pride in weaving and delivering high-quality handloom sarees. If your order arrives damaged or defective, we provide a smooth resolution.</p>\n<h3>3-Day Return & Replacement Policy</h3>\n<p>We offer a 3-day return or exchange window from the date of package delivery. To be eligible for a return:</p>\n<ul>\n  <li>The saree must be unworn, unused, with original tags intact and folded in its original protective packaging.</li>\n  <li><strong>Mandatory Unboxing Video:</strong> Please record a continuous, unedited video while opening the sealed courier parcel to confirm any transit damages or missing items.</li>\n  <li>To initiate a return, contact our support team at <a href=\"mailto:support@selvitex.com\">support@selvitex.com</a> or message our WhatsApp desk at <strong>9789296751</strong>.</li>\n</ul>\n<h3>Return Address</h3>\n<p><strong>Selvi Tex – Returns Department</strong><br>11-1/61/15, Thirumalai Gounder Street, Ulavar Sandhai Opp, Elampillai, Salem District, Tamil Nadu – 637502.</p>\n<h3>Refund Processing</h3>\n<p>Once your returned item is received and inspected at our workshop, your refund will be processed to your original payment method (or UPI/Bank Account for COD orders) within 5 to 7 business days.</p>','Returns & Refund Policy | Selvi Tex','Review the Selvi Tex 3-day return and refund policy for damaged or defective handloom sarees.','refund policy, saree returns, exchange policy, Selvi Tex return address',NULL,NULL,'published','2026-10-16 23:58:10','2026-10-16 23:58:10'),(5,'Privacy Policy','privacy-policy','<h2>Privacy Policy – Selvi Tex</h2>\n<p>This Privacy Policy describes how <strong>Selvi Tex</strong> (\"we\", \"us\", or \"our\") collects, uses, and safeguards your personal information when you visit, browse, or purchase from our website.</p>\n<h3>Information We Collect</h3>\n<p>When you place an order or create an account, we collect necessary transaction information including your name, shipping address, contact phone number, email address, and payment preferences. Payment credentials (card numbers, UPI PINs) are processed directly by RBI-authorized secure payment gateways (such as Razorpay) and are never stored on our servers.</p>\n<h3>How We Use Your Information</h3>\n<ul>\n  <li>To process, fulfill, and ship your saree orders.</li>\n  <li>To send real-time SMS, email, and WhatsApp shipping tracking updates.</li>\n  <li>To provide responsive customer support and resolve any queries.</li>\n  <li>To protect against fraud and ensure website security.</li>\n</ul>\n<h3>Zero Data Selling Guarantee</h3>\n<p>We respect your privacy. We will never sell, rent, or trade your personal information to third-party advertisers. For questions regarding your data, contact us at <a href=\"mailto:support@selvitex.com\">support@selvitex.com</a>.</p>','Privacy Policy | Selvi Tex','Learn how Selvi Tex protects your personal data, secure online payments, and browsing information.','privacy policy, customer privacy, secure checkout, Selvi Tex',NULL,NULL,'published','2026-10-16 23:58:10','2026-10-16 23:58:10'),(6,'Terms of Service','terms-of-service','<h2>Terms of Service – Selvi Tex</h2>\n<p>Welcome to <strong>Selvi Tex</strong>. By accessing or using our website and purchasing our handloom products, you agree to be bound by the following terms and conditions.</p>\n<h3>Product Authenticity & Color Variations</h3>\n<p>We make every effort to display the colors and weaves of our sarees with utmost accuracy. However, slight variations in color tone may occur due to photography lighting or digital display screen calibrations. Handloom fabrics may contain subtle characteristics (such as slight unevenness in weave) which are natural hallmarks of authentic artisan craft, not manufacturing defects.</p>\n<h3>Pricing & Order Acceptance</h3>\n<p>All prices listed on the website are in Indian Rupees (INR) and are inclusive of applicable GST taxes. We reserve the right to modify prices or cancel orders in cases of typographical errors or stock unavailability, in which case full refunds are issued immediately.</p>\n<h3>Governing Law</h3>\n<p>These terms and conditions are governed by and construed in accordance with the laws of India. Any legal disputes are subject to the jurisdiction of Salem, Tamil Nadu.</p>','Terms of Service | Selvi Tex','Terms and conditions governing the purchase of authentic handloom sarees from Selvi Tex.','terms of service, legal policy, terms and conditions, Selvi Tex',NULL,NULL,'published','2026-10-16 23:58:10','2026-10-16 23:58:10');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;


--
-- Table structure for table `product_media`
--

DROP TABLE IF EXISTS `product_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `product_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `variant_id` int(10) unsigned DEFAULT NULL,
  `media_url` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL DEFAULT 'image',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_media_product_id_foreign` (`product_id`),
  CONSTRAINT `product_media_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_media`
--


/*!40000 ALTER TABLE `product_media` DISABLE KEYS */;
INSERT INTO `product_media` VALUES (23,18,NULL,'uploads/products/1792252931_4552b6a24c8be5481925.jpg','image',1,0,1,'2026-10-17 16:02:11','2026-10-17 16:02:11'),(24,18,NULL,'uploads/products/1792252931_53f364ce573e84486e7e.mp4','video',1,0,0,'2026-10-17 16:02:11','2026-10-17 16:02:11'),(25,18,9,'uploads/products/1792252931_f6e6da905d27c2eb0dcc.jpg','image',1,1,0,'2026-10-17 16:02:11','2026-10-17 16:02:11'),(26,18,10,'uploads/products/1792252931_081c4151eb2fa295b0aa.jpg','image',1,1,0,'2026-10-17 16:02:11','2026-10-17 16:02:11'),(27,19,NULL,'uploads/products/1792255178_30ef314de1f3e721d31f.jpg','image',1,0,1,'2026-10-17 16:39:38','2026-10-17 16:39:38'),(28,20,NULL,'uploads/products/1792256012_7519524bb068b423e1c7.jpg','image',1,0,1,'2026-10-17 16:53:32','2026-10-17 16:53:32'),(29,21,NULL,'uploads/products/1792501288_66df72d3943ebd853142.jpg','image',1,0,1,'2026-10-20 13:01:28','2026-10-20 13:01:28'),(30,21,13,'uploads/products/1792501288_53d450fbc7f18b44bd14.jpg','image',1,1,0,'2026-10-20 13:01:28','2026-10-20 13:01:28'),(31,22,NULL,'uploads/products/1792503240_3490e87bf6f13bd3f797.jpg','image',1,0,1,'2026-10-20 13:34:00','2026-10-20 13:34:00'),(32,22,NULL,'uploads/products/1792503240_95f0239772b94d1971d0.jpg','image',1,0,2,'2026-10-20 13:34:00','2026-10-20 13:34:00'),(33,22,14,'uploads/products/1792503240_e7f37fbd057151cc0d83.jpg','image',1,1,0,'2026-10-20 13:34:00','2026-10-20 13:34:00'),(34,23,NULL,'uploads/products/1792731877_24f313f79d31a1d53ebf.jpg','image',1,0,1,'2026-10-23 05:04:37','2026-10-23 05:04:37'),(35,24,NULL,'uploads/products/1792732215_d315a84f4dda5fe183ac.jpg','image',1,0,1,'2026-10-23 05:10:15','2026-10-23 05:10:15'),(36,24,NULL,'uploads/products/1792732215_ba05426988045425fd03.jpg','image',1,0,2,'2026-10-23 05:10:15','2026-10-23 05:10:15'),(37,24,15,'uploads/products/1792732215_076eabd7da1469318c13.jpg','image',1,1,0,'2026-10-23 05:10:15','2026-10-23 05:10:15'),(39,25,18,'uploads/products/1793080645_7bdceb5e4e6c32bf3545.jpg','image',1,1,0,'2026-10-27 05:57:25','2026-10-27 05:57:25'),(40,25,19,'uploads/products/1793081498_4f3ab29418ffc71c900b.jpg','image',1,0,0,'2026-10-27 06:11:38','2026-10-27 06:11:38'),(41,26,NULL,'uploads/products/1793621348_0b6af1b0752b36f9ae08.webp','image',1,0,1,'2026-11-02 12:09:08','2026-11-02 12:09:08'),(42,26,NULL,'uploads/products/1793621348_8822d25974644484f10e.jpg','image',1,0,2,'2026-11-02 12:09:08','2026-11-02 12:09:08'),(43,26,20,'uploads/products/1793621348_8056f071c56ba33e605d.webp','image',1,1,0,'2026-11-02 12:09:08','2026-11-02 12:09:08'),(44,26,21,'uploads/products/1793621348_eb8a24f675bd78db7e76.jpg','image',1,1,0,'2026-11-02 12:09:08','2026-11-02 12:09:08');
/*!40000 ALTER TABLE `product_media` ENABLE KEYS */;


--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `review_title` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `review_image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_product_status` (`product_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--


/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
INSERT INTO `product_reviews` VALUES (1,1,'Kavitha R.','kavitha@example.com',5,'Exceptional Pure Silk Quality','The zari border and soft silk texture are truly magnificent. Delivered on time in secure packaging.',NULL,'Approved','2026-10-13 08:58:47','2026-10-13 08:58:47'),(2,7,'Sundari M.','sundari.m@example.com',5,'Outstanding quality cotton saree & super soft drape!','Purchased this Budget Dhamaka Cotton Saree directly from Sri Sakthi Selvi Tex. The Elampillai cotton weave is soft, breathable, and color is exactly as pictured. Very satisfied with prompt delivery and weaver pricing!',NULL,'Approved','2026-10-14 03:02:47','2026-10-14 03:02:47'),(3,2,'Meena Sundaram','meena.sundaram@gmail.com',5,'Superb semi silk feel & gorgeous zari shine','Bought this Vennila semi silk for a family wedding. The texture feels like authentic pure silk and the drape is so comfortable. Highly recommend SSPTEX!',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(4,2,'Lakshmi Priya','lakshmi.p@yahoo.com',5,'Excellent color contrast & rich pallu','Extremely satisfied with the weaving craftsmanship from Elampillai. Delivered within 3 days with perfect packaging.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(5,2,'Deepa Venkatesh','deepa.v@outlook.com',5,'Best value luxury saree','Very lightweight yet looks very rich for functions and festivals. Pure Elampillai traditional touch.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(6,2,'Anitha Rajesh','anitha.r@gmail.com',5,'True to picture and brilliant quality','The saree color and border luster matched the photos 100%. Got so many compliments at temple function.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(7,2,'Revathi Kannan','revathi.k@gmail.com',5,'Top notch customer service & fast delivery','Sri Sakthi Selvi Tex weavers offer direct genuine wholesale rates with top quality silk threads. Thank you!',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(8,1,'Sowmya N.','sowmya.n@gmail.com',5,'Exceptional Pure Silk Quality','The zari border and soft silk texture are truly magnificent. Delivered on time in secure packaging.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(9,1,'Gayathri M.','gayathri.m@gmail.com',5,'Finest Elampillai Weave','Rich look and very easy to pleat. Absolutely in love with this saree.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(10,1,'Radhika S.','radhika.s@gmail.com',5,'Stunning border and rich pallu','High quality traditional silk weave. Best purchase for wedding season.',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(11,1,'Bhavani K.','bhavani.k@gmail.com',5,'Authentic handloom craftsmanship','Prompt shipping and premium packaging. 5 stars all the way!',NULL,'Approved','2026-10-14 20:09:43','2026-10-14 20:09:43'),(12,18,'Naveen S','naaveensaaran.01319@gmail.com',5,'Beautiful','hi',NULL,'Approved','2026-10-17 18:41:09','2026-10-17 18:41:09');
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;


--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `color_name` varchar(100) DEFAULT NULL,
  `color_code` varchar(20) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--


/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (9,18,'blue / Free Size','blue','#124bce',NULL,1400.00,1.00,4,NULL,1,'2026-10-17 16:02:11','2026-11-10 07:27:19'),(10,18,'Maroon / Free Size','Maroon','#7a1530',NULL,1390.00,1.00,10,NULL,1,'2026-10-17 16:02:11','2026-10-20 07:16:05'),(11,19,'Pink / Free Size','Pink','#ffc0cb',NULL,2000.00,0.92,3,NULL,1,'2026-10-17 16:39:38','2026-10-21 11:59:00'),(12,20,'Pink / Free Size','Pink','#ffc0cb',NULL,2000.00,1.00,9,NULL,1,'2026-10-17 16:53:32','2026-10-21 12:10:35'),(13,21,'Pink','Pink','#7a154b',NULL,90.00,1.00,10,NULL,1,'2026-10-20 13:01:28','2026-10-21 11:58:41'),(14,22,'green','green','#697a15',NULL,500.00,440.00,15,NULL,1,'2026-10-20 13:34:00','2026-10-22 05:13:17'),(15,24,'Maronn','Maronn','#7a1530',NULL,800.00,760.00,10,NULL,1,'2026-10-23 05:10:15','2026-10-23 05:12:04'),(18,25,'Orange / S, M, L, XL, XXL','Orange','#ff9500','S, M, L, XL, XXL',1000.00,890.00,10,NULL,1,'2026-10-27 05:57:25','2026-10-27 07:48:33'),(19,25,'Orange / 3XL, Free Size','Orange','#ff9500','3XL, Free Size',1000.00,900.00,10,NULL,1,'2026-10-27 06:11:38','2026-10-27 07:48:33'),(20,26,'Pink / 0-3 Months, 3-6 Months, 6-12 Months, 1-2 Yrs','Pink','#ec4899','0-3 Months, 3-6 Months, 6-12 Months, 1-2 Yrs',1000.00,1.00,10,NULL,1,'2026-11-02 12:09:08','2026-11-10 05:09:13'),(21,26,'Pink / 1-2 Yrs, 2-3 Yrs, 3-4 Yrs, 4-5 Yrs','Pink','#ec4899','1-2 Yrs, 2-3 Yrs, 3-4 Yrs, 4-5 Yrs',1100.00,1.00,8,NULL,1,'2026-11-02 12:09:08','2026-11-10 05:54:45');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;


--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subcategory_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `gst_rate` decimal(5,2) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_combo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_live_collection` tinyint(1) DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `is_best_of_ssptex` tinyint(1) DEFAULT 0,
  `is_must_visit` tinyint(1) DEFAULT 0,
  `stock_quantity` int(11) DEFAULT 10,
  `blouse_measurement_image` varchar(255) DEFAULT NULL,
  `dhoti_measurement_image` varchar(255) DEFAULT NULL,
  `shorts_channel` varchar(100) DEFAULT NULL,
  `shorts_url` varchar(255) DEFAULT NULL,
  `instagram_channel` varchar(100) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_channel` varchar(100) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `saree_length` varchar(150) DEFAULT NULL,
  `saree_height` varchar(150) DEFAULT NULL,
  `dhoti_length` varchar(255) DEFAULT NULL,
  `blouse_length` varchar(150) DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_subcategory_id_foreign` (`subcategory_id`),
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--


/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (18,13,'Kanchipuram Kotta Cotton Traditional Saree','SSPTEX-COT-18','kanchipuram-kotta-cotton-traditional-saree','xperience timeless elegance with the Kanchipuram Kotta Cotton Traditional Saree, crafted for women who appreciate traditional Indian style and everyday comfort. Featuring a beautiful traditional look with a soft cotton texture, this saree is suitable for festivals, family functions, cultural celebrations, and everyday ethnic wear.\r\n\r\nIts graceful drape and classic design make it a lovely addition to your ethnic wardrobe. Pair it with a matching blouse, traditional jewellery, and your favourite accessories for an elegant and sophisticated look.\r\n\r\nProduct Highlights:\r\n\r\nProduct Type: Traditional Cotton Saree\r\n\r\nStyle: Kanchipuram-inspired traditional design\r\n\r\nFabric: Kotta Cotton (confirm fabric composition with supplier)\r\n\r\nOccasion: Festivals, family gatherings, and ethnic wear\r\n\r\nComfort: Lightweight and comfortable for extended wear\r\n\r\nPairing: Suitable with traditional or contrast-colour blouses\r\n\r\nCare Instructions: Follow the garment\'s care label.',1.00,NULL,1249.98,'uploads/products/1792252931_63212f5f50c6155d2d0b.jpg',1,1,0,'2026-10-17 16:02:11','2026-11-10 07:27:19',1,'','','',0,1,4,NULL,NULL,'SRI SAKTHI Selvi','https://youtube.com/shorts/ZHugoaROjVw?si=FHU5trKz3qTJF3Xq',NULL,NULL,NULL,NULL,'6.35 Meters (Including Blouse)','44 to 47 inches',NULL,'0.8 Meters',NULL,'uploads/products/1792252931_53f364ce573e84486e7e.mp4'),(19,12,'Kanchipuram Malmal Cotton Soft Saree',NULL,'kanchipuram-malmal-cotton-soft-saree','',1.00,NULL,250.00,'uploads/products/1792255178_c64dfaeb694f0b5a9af5.jpg',1,1,0,'2026-10-17 16:39:38','2026-10-21 11:59:00',1,'','','',1,1,3,NULL,NULL,'OFFER CHANNEL','https://youtube.com/shorts/IdVswR2S-y4?si=D77vAa4yCG4K5RnI','OFFER CHANNEL','https://www.instagram.com/reel/CvOWEOiMRLd/?srtk=MTJkMXBmenpzazcxNQ==',NULL,NULL,'6.30 Meters (Including Blouse)','44 to 47 inches',NULL,'0.8 Meters',NULL,NULL),(20,13,'Flower Pots Deluxe',NULL,'flower-pots-deluxe','',1.00,NULL,790.00,'uploads/products/1792256012_808d9fd869feaf9a2389.jpg',0,0,0,'2026-10-17 16:53:32','2026-10-21 12:10:35',0,'','','',0,0,9,NULL,NULL,NULL,NULL,'SRI SAKTHI Selvi','https://www.instagram.com/reel/CvOWEOiMRLd/?srtk=MTJkMXBmenpzazcxNQ%3D%3D','SRI SAKTHI Selvi','https://www.facebook.com/reel/1889155878185288/','6.30 Meters (Including Blouse)','44 to 47 inches',NULL,NULL,NULL,NULL),(21,14,'Tissue type silk',NULL,'tissue-type-silk','',1.00,NULL,6000.00,'uploads/products/1792501288_0c24b5ee1d50f4a03a67.jpg',1,1,0,'2026-10-20 13:01:28','2026-10-21 11:58:41',1,'','','',1,1,10,NULL,NULL,NULL,NULL,'SRI SAKTHI Selvi','https://www.instagram.com/reel/CvOWEOiMRLd/?srtk=MTJkMXBmenpzazcxNQ==',NULL,NULL,'6.30 Meters (Including Blouse)','44 to 47 inches',NULL,NULL,NULL,NULL),(22,15,'Erode Cotton Saree',NULL,'erode-cotton-saree','- this elegance saree',450.00,NULL,500.00,'uploads/products/1792503240_cdb766eb23360fe412e7.jpg',1,1,0,'2026-10-20 13:34:00','2026-10-22 05:13:17',1,'','','',1,1,15,NULL,NULL,'OFFER CHANNEL','https://youtube.com/shorts/mPCRc72U-2Q?si=e9ncPOYV87tE4XxV','OFFER CHANNEL','https://www.instagram.com/reel/CvOWEOiMRLd/?srtk=MTJkMXBmenpzazcxNQ==','SRI SAKTHI Selvi','https://www.facebook.com/reel/1889155878185288/','6.30 Meters (Including Blouse)','44 to 47 inches',NULL,'0.8 Meters',NULL,NULL),(23,15,' Kotta Cotton Traditional Saree',NULL,'kotta-cotton-traditional-saree','',780.00,NULL,900.00,'uploads/products/1792731877_275ea9158799a9f6701c.jpg',1,1,0,'2026-10-23 05:04:37','2026-10-23 05:04:37',1,'','','',1,1,10,NULL,NULL,'OFFER CHANNEL','https://youtube.com/shorts/YQc26gtm-7A?si=BkAPZb1JKfLizNFB',NULL,NULL,NULL,NULL,'6.30 Meters (Including Blouse)','44 to 47 inches',NULL,NULL,NULL,NULL),(24,16,'Kanchi Silk',NULL,'kanchi-silk','Experience timeless elegance with our Kanchi Silk Saree, beautifully crafted with traditional designs and a rich silk finish. Featuring elegant patterns, a luxurious texture, and a graceful drape, this saree is perfect for weddings, festivals, religious ceremonies, and special occasions. Celebrate the beauty of Indian tradition with a classic Kanchi Silk Saree that adds charm and sophistication to every celebration.',760.00,NULL,800.00,'uploads/products/1792732215_05dcf9e3bf1e2688ebac.jpg',1,1,0,'2026-10-23 05:10:15','2026-10-23 05:12:04',1,'','','',1,1,10,NULL,NULL,'SRI SAKTHI Selvi','https://youtube.com/shorts/Lm5KY7EIKt0?si=vBzNiNHpu7cju66d',NULL,NULL,NULL,NULL,'6.30 Meters (Including Blouse)','44 to 47 inches',NULL,NULL,NULL,NULL),(25,17,'Orange saree Dhoti',NULL,'orange-saree-dhoti','',890.00,NULL,1000.00,'uploads/products/1793080670_35a124919c87064688bb.jpg',1,1,1,'2026-10-27 05:57:25','2026-10-27 07:48:33',1,'','','',1,1,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6.30 Meters (Including Blouse)','44 to 47 inches','10x6 (Pattu Dhoti + Shawl Set)','0.8 Meters',NULL,NULL),(26,11,'kids',NULL,'kids','',1.00,NULL,1000.00,'uploads/products/1793621348_700b09e66981b5e67f1a.webp',1,1,0,'2026-11-02 12:09:08','2026-11-10 05:54:45',1,'','','',1,1,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;


--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--


/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'business_name','Selvi Tex','2026-10-09 03:09:44','2026-10-17 19:29:55'),(2,'short_name','Selvi Tex','2026-10-09 03:09:44','2026-10-17 19:29:55'),(3,'gst_number','','2026-10-09 03:09:44','2026-10-17 19:29:55'),(4,'whatsapp_no','919789296751','2026-10-09 03:09:44','2026-10-17 19:29:55'),(5,'facebook_link','https://facebook.com/srisakthiselvitex','2026-10-09 03:09:44','2026-10-11 10:58:16'),(6,'instagram_link','https://instagram.com/srisakthiselvitex','2026-10-09 03:09:44','2026-10-11 10:58:16'),(7,'twitter_link','https://twitter.com/srisakthiselvitex','2026-10-09 03:09:44','2026-10-11 10:58:16'),(8,'header_categories','[{\"name\":\"Cottons\",\"image\":\"uploads\\/settings\\/1793069138_83e068c64b2aa24b1a27.jpeg\"},{\"name\":\"Silk\",\"image\":\"\"},{\"name\":\"Combo\",\"image\":\"uploads\\/settings\\/1793079678_ce1ac8f1bde358724508.jpg\"}]','2026-10-09 03:30:55','2026-10-27 05:41:18'),(9,'google_analytics','','2026-10-09 15:48:19','2026-10-11 10:58:16'),(10,'seo_pages','{\"home\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"new_arrivals\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"best_sellers\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"collections\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"sarees\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"cotton\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"pudaivai\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"silk\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"tissue-type-silk-celebrity-inspired-saree\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"naveen-s\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"},\"cottons\":{\"title\":\"\",\"description\":\"\",\"keywords\":\"\"}}','2026-10-09 15:48:19','2026-10-11 10:58:16'),(11,'razorpay_key_id','rzp_test_placeholder','2026-10-09 16:29:11','2026-11-10 07:20:04'),(12,'razorpay_key_secret','placeholder_razorpay_secret','2026-10-09 16:29:11','2026-11-10 07:20:05'),(13,'business_logo','uploads/settings/1792250178_eb4980d4f096bfc6b50e.png','2026-10-09 16:29:12','2026-10-17 15:16:18'),(14,'favicon','uploads/settings/1792204986_a9d330f68b404c1905d4.png','2026-10-09 16:29:12','2026-10-17 02:43:06'),(15,'new_arrivals_banner','[\"uploads\\/settings\\/1792258160_78817d9871c35cd12ad9.jpg\",\"uploads\\/settings\\/1792258160_26525500dcdf1633f921.jpg\"]','2026-10-09 16:31:05','2026-10-17 17:29:20'),(16,'enable_gst','0','2026-10-10 16:20:32','2026-10-20 13:36:29'),(17,'trust_cards_data','[{\"icon\":\"fa-solid fa-landmark\",\"title\":\"Established in 2010\",\"desc\":\"Over a decade of cherished heritage, delivering authentic sarees with enduring craftsmanship and tradition.\",\"order\":1,\"visible\":1},{\"icon\":\"fa-solid fa-award\",\"title\":\"15+ Years of Experience\",\"desc\":\"Masterful weaving expertise, unmatched quality standards, and dedicated commitment to our valued patrons.\",\"order\":2,\"visible\":1},{\"icon\":\"fa-solid fa-earth-americas\",\"title\":\"Worldwide Customers\",\"desc\":\"Bridging distances with worldwide shipping, bringing beloved Indian weaves to saree lovers across the globe.\",\"order\":3,\"visible\":1},{\"icon\":\"fa-solid fa-star\",\"title\":\"5-Star Customer Experience\",\"desc\":\"Loved by thousands of happy customers for seamless service, pristine packaging, and exceptional satisfaction.\",\"order\":4,\"visible\":1}]','2026-10-11 09:46:23','2026-10-11 10:58:17'),(18,'trust_cards_enabled','1','2026-10-11 09:46:23','2026-11-02 12:05:16'),(19,'active_tab_redirect','homepage','2026-10-11 07:15:15','2026-10-11 10:58:16'),(20,'location_link','https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai','2026-10-11 07:15:15','2026-10-17 19:29:55'),(21,'business_address','D/No.13/10, Periya Mammu St, Elampillai to Chinnapampattii, Main Road, Near Bavana Ganapathi Kovil, Elampillai - 637502','2026-10-11 07:15:16','2026-10-17 19:29:55'),(22,'existing_new_arrivals_banner','[\"uploads\\/settings\\/1791563513_714243ab53d3a7116ecb.jpg\",\"uploads\\/settings\\/1791702915_a7b890ffafa5c8435ee0.jpeg\"]','2026-10-11 07:15:16','2026-10-11 10:58:16'),(23,'module_live_collections','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(24,'home_live_collections_title','Live Collections','2026-10-11 07:15:16','2026-10-11 10:58:16'),(25,'home_diwali_title','THE BEST DIWALI SAREE\'S','2026-10-11 07:15:16','2026-10-16 14:35:27'),(26,'home_diwali_subtitle','Presenting Timeless Crafts','2026-10-11 07:15:16','2026-10-16 14:35:27'),(27,'home_diwali_link','#','2026-10-11 07:15:16','2026-10-16 14:35:27'),(28,'home_shorts_title','SHORTS PRODUCTS','2026-10-11 07:15:16','2026-10-11 10:58:17'),(29,'home_shorts_subtitle','From Our various YT Channel we upload multiple products Daily...','2026-10-11 07:15:16','2026-10-11 10:58:17'),(30,'home_shorts_btn_text','VIEW ALL','2026-10-11 07:15:16','2026-10-11 10:58:17'),(31,'home_shorts_btn_link','#','2026-10-11 07:15:16','2026-10-11 10:58:17'),(32,'home_must_visit_title','Must Visit','2026-10-11 07:15:16','2026-10-20 06:01:58'),(33,'home_must_visit_subtitle','Everyone','2026-10-11 07:15:16','2026-10-20 06:01:58'),(34,'home_cotton_title','Cotton Lovers Assemble Here','2026-10-11 07:15:16','2026-10-11 10:58:17'),(35,'home_cotton_subtitle','Swipe and Explore Your best Outfit','2026-10-11 07:15:16','2026-10-11 10:58:17'),(36,'module_hero_banners','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(37,'module_diwali','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(38,'module_shorts','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(39,'module_must_visit','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(40,'module_cotton','1','2026-10-11 07:15:16','2026-10-11 10:58:17'),(41,'module_budget','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(42,'module_whatsapp','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(43,'module_gst','1','2026-10-11 07:15:16','2026-11-02 12:05:16'),(44,'best_sellers_banner','[]','2026-10-11 07:15:16','2026-10-17 17:29:20'),(45,'collections_banner','[]','2026-10-11 07:15:16','2026-10-17 17:29:20'),(46,'banner_text_overlay_enabled','1','2026-10-11 08:04:12','2026-10-17 17:29:20'),(47,'banner_quote_text','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 08:04:12','2026-10-17 17:29:20'),(48,'banner_heading_text','Best Collection For Ever','2026-10-11 08:04:12','2026-10-17 17:29:20'),(49,'banner_subtitle_text','ELAMPILLAI PATTU','2026-10-11 08:04:12','2026-10-17 17:29:20'),(50,'bestsellers_text_overlay_enabled','0','2026-10-11 08:04:12','2026-10-17 17:29:20'),(51,'bestsellers_quote_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(52,'bestsellers_heading_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(53,'bestsellers_subtitle_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(54,'collections_text_overlay_enabled','0','2026-10-11 08:04:12','2026-10-17 17:29:20'),(55,'collections_quote_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(56,'collections_heading_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(57,'collections_subtitle_text','','2026-10-11 08:04:12','2026-10-17 17:29:20'),(58,'category_text_overlay_enabled_cotton','1','2026-10-11 08:04:12','2026-10-17 17:29:20'),(59,'category_quote_text_cotton','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 08:04:12','2026-10-17 17:29:20'),(60,'category_heading_text_cotton','Cotton','2026-10-11 08:04:12','2026-10-17 17:29:20'),(61,'category_subtitle_text_cotton','Exclusive Collection','2026-10-11 08:04:12','2026-10-17 17:29:20'),(62,'category_text_overlay_enabled_saree','1','2026-10-11 08:04:12','2026-10-11 10:58:16'),(63,'category_quote_text_saree','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 08:04:12','2026-10-11 10:58:16'),(64,'category_heading_text_saree','saree','2026-10-11 08:04:12','2026-10-11 10:58:16'),(65,'category_subtitle_text_saree','Exclusive Collection','2026-10-11 08:04:12','2026-10-11 10:58:16'),(66,'category_text_overlay_enabled_pudaivai','1','2026-10-11 08:04:12','2026-10-17 17:29:20'),(67,'category_quote_text_pudaivai','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 08:04:12','2026-10-17 17:29:20'),(68,'category_heading_text_pudaivai','pudaivai','2026-10-11 08:04:12','2026-10-17 17:29:20'),(69,'category_subtitle_text_pudaivai','Exclusive Collection','2026-10-11 08:04:12','2026-10-17 17:29:20'),(70,'category_text_overlay_enabled_silk','1','2026-10-11 08:04:12','2026-10-17 17:29:20'),(71,'category_quote_text_silk','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 08:04:12','2026-10-17 17:29:20'),(72,'category_heading_text_silk','silk','2026-10-11 08:04:12','2026-10-17 17:29:20'),(73,'category_subtitle_text_silk','Exclusive Collection','2026-10-11 08:04:12','2026-10-17 17:29:20'),(74,'category_banner_cotton','[]','2026-10-11 08:04:12','2026-10-17 17:29:20'),(76,'category_banner_pudaivai','[]','2026-10-11 08:04:12','2026-10-17 17:29:20'),(77,'category_banner_silk','[]','2026-10-11 08:04:12','2026-10-17 17:29:20'),(78,'home_shorts_channels','[{\"name\":\"SRI SAKTHI Selvi\",\"target\":\"sri_sakthi_selvi\",\"image\":\"uploads\\/settings\\/1792257076_0b6ee37d3751df3f0233.jpg\",\"link\":\"\",\"visible\":1},{\"name\":\"OFFER CHANNEL\",\"target\":\"offer_channel\",\"image\":\"uploads\\/settings\\/1792257076_861ba781d04501d6b698.png\",\"link\":\"\",\"visible\":1},{\"name\":\"ELITE C\",\"target\":\"elite_c\",\"image\":\"uploads\\/settings\\/1792257076_1ad7b372faef0d4accc7.jpg\",\"link\":\"\",\"visible\":1}]',NULL,'2026-10-17 17:11:16'),(79,'announcement_bar_enabled','1','2026-10-11 10:49:15','2026-10-11 10:58:16'),(80,'announcement_bar_text','Free Shipping above Rs.1000/- Purchases.       Hurry Most Beautiful collections On its way!       Diwali Sale is Live       Free Shipping above Rs.1000/- Purchases.','2026-10-11 10:49:15','2026-10-11 10:58:16'),(81,'announcement_bar_bold','1','2026-10-11 10:49:15','2026-10-11 10:58:16'),(82,'announcement_bar_font_size','13px','2026-10-11 10:49:15','2026-10-11 10:58:16'),(83,'announcement_bar_text_color','#444444','2026-10-11 10:49:15','2026-10-11 10:58:16'),(84,'announcement_bar_bg_color','#e2e2e2','2026-10-11 10:49:15','2026-10-11 10:58:16'),(85,'category_text_overlay_enabled_cottons','1','2026-10-11 10:49:15','2026-10-16 16:10:38'),(86,'category_quote_text_cottons','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 10:49:15','2026-10-16 16:10:38'),(87,'category_heading_text_cottons','Cottons','2026-10-11 10:49:15','2026-10-16 16:10:38'),(88,'category_subtitle_text_cottons','Exclusive Collection','2026-10-11 10:49:15','2026-10-16 16:10:38'),(89,'category_text_overlay_enabled_sarees','1','2026-10-11 10:49:15','2026-10-16 16:10:38'),(90,'category_quote_text_sarees','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 10:49:15','2026-10-16 16:10:38'),(91,'category_heading_text_sarees','Sarees','2026-10-11 10:49:15','2026-10-16 16:10:38'),(92,'category_subtitle_text_sarees','Exclusive Collection','2026-10-11 10:49:15','2026-10-16 16:10:38'),(93,'category_text_overlay_enabled_tissue-type-silk-celebrity-inspired-saree','1','2026-10-11 10:49:15','2026-10-16 16:10:38'),(94,'category_quote_text_tissue-type-silk-celebrity-inspired-saree','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 10:49:15','2026-10-16 16:10:38'),(95,'category_heading_text_tissue-type-silk-celebrity-inspired-saree','Tissue Type Silk Celebrity Inspired Saree','2026-10-11 10:49:15','2026-10-16 16:10:38'),(96,'category_subtitle_text_tissue-type-silk-celebrity-inspired-saree','Exclusive Collection','2026-10-11 10:49:15','2026-10-16 16:10:38'),(97,'category_text_overlay_enabled_naveen-s','1','2026-10-11 10:49:15','2026-10-16 16:10:38'),(98,'category_quote_text_naveen-s','\"A Good life is a Collection of HAPPY MEMORIES\"','2026-10-11 10:49:15','2026-10-16 16:10:38'),(99,'category_heading_text_naveen-s','Naveen S','2026-10-11 10:49:15','2026-10-16 16:10:38'),(100,'category_subtitle_text_naveen-s','Exclusive Collection','2026-10-11 10:49:15','2026-10-16 16:10:38'),(101,'home_budget_enabled','1','2026-10-11 10:49:15','2026-10-11 10:58:16'),(102,'home_budget_title','Shop by Budget','2026-10-11 10:49:15','2026-10-11 10:58:16'),(103,'home_budget_subtitle','Handpicked Sarees Tailored to Your Budget','2026-10-11 10:49:15','2026-10-11 10:58:16'),(104,'home_why_choose_enabled','1','2026-10-11 10:49:15','2026-10-14 04:44:47'),(105,'home_why_choose_title','Why Choose Selvi Tex?','2026-10-11 10:49:15','2026-10-14 04:44:47'),(106,'home_why_choose_subtitle','Authentic Weaving Heritage • Direct Factory Prices • Global Doorstep Delivery','2026-10-11 10:49:15','2026-10-14 04:44:47'),(107,'why_choose_title_1','Direct From Master Weavers','2026-10-11 10:49:15','2026-10-14 04:44:47'),(108,'why_choose_desc_1','Woven directly on authentic Elampillai looms, preserving century-old silk and cotton craftsmanship without middleman commissions.','2026-10-11 10:49:15','2026-10-14 04:44:47'),(109,'why_choose_title_2','Wholesale Price for Everyone','2026-10-11 10:49:15','2026-10-14 04:44:47'),(110,'why_choose_desc_2','Direct manufacturer rates whether purchasing a single celebratory saree or bulk bridal and uniform orders.','2026-10-11 10:49:15','2026-10-14 04:44:47'),(111,'why_choose_title_3','100% Quality & Zari Guarantee','2026-10-11 10:49:15','2026-10-14 04:44:47'),(112,'why_choose_desc_3','Every piece undergoes multi-point inspection for authentic zari shine, soft drape, and durable texture.','2026-10-11 10:49:15','2026-10-14 04:44:47'),(113,'why_choose_title_4','Worldwide Express Delivery','2026-10-11 10:49:15','2026-10-14 04:44:47'),(114,'why_choose_desc_4','Fast, reliable door delivery across India and internationally with secure protective packaging and full tracking.','2026-10-11 10:49:15','2026-10-14 04:44:47'),(115,'about_store_enabled','1','2026-10-11 10:49:15','2026-10-17 17:38:39'),(116,'about_store_title','About Selvi Tex','2026-10-11 10:49:15','2026-10-17 17:38:39'),(117,'about_store_subtitle','VISIT OUR SHOWROOM','2026-10-11 10:49:15','2026-10-17 17:38:39'),(118,'about_store_desc','Experience the finest Elampillai pattu, traditional pure silk, and authentic handloom sarees directly from the master weavers of Tamil Nadu. Visit our exclusive showroom for an unforgettable saree shopping experience with personalized assistance.','2026-10-11 10:49:15','2026-10-17 17:38:39'),(119,'about_store_address','D/No.13/10, Periya Mammu St, Elampillai to Chinnapampattii, Main Road, Near Bavana Ganapathi Kovil, Elampillai - 637502','2026-10-11 10:49:15','2026-10-17 17:38:39'),(120,'about_store_timings','Monday - Sunday: 9:00 AM - 9:00 PM','2026-10-11 10:49:15','2026-10-17 17:38:39'),(121,'about_store_map_link','https://maps.google.com/?q=Sri+Sakthi+Selvi+Tex+Elampillai','2026-10-11 10:49:15','2026-10-17 17:38:39'),(122,'about_store_phone','9789296751','2026-10-11 10:49:15','2026-10-17 17:38:39'),(123,'about_store_whatsapp','919789296751','2026-10-11 10:49:15','2026-10-17 17:38:39'),(124,'about_store_images','[\"uploads\\/settings\\/1792258718_297667881bff1bcbe3b6.jpg\",\"uploads\\/settings\\/1792258719_c5b4a1985e2d202c946e.jpg\"]','2026-10-11 10:49:16','2026-10-17 17:38:39'),(129,'home_budget_tiers','[{\"badge\":\"BELOW\",\"price\":\"Rs.500\\/-\",\"title\":\"Below Rs 499\\/- Collection\",\"link\":\"collections?max_price=499\",\"visible\":1},{\"badge\":\"BETWEEN\",\"price\":\"Rs.501 to 1000\\/-\",\"title\":\"From Rs 500 To Rs 999\\/-\",\"link\":\"collections?min_price=500&max_price=999\",\"visible\":1},{\"badge\":\"BETWEEN\",\"price\":\"Rs.1001 to 2000\\/-\",\"title\":\"From Rs 1001 To Rs 2000\\/-\",\"link\":\"collections?min_price=1001&max_price=2000\",\"visible\":1},{\"badge\":\"ABOVE\",\"price\":\"Rs.2000\\/-\",\"title\":\"Above Rs 2000\\/- Collection\",\"link\":\"collections?min_price=2000\",\"visible\":1}]','2026-10-11 10:49:16','2026-10-11 10:58:17'),(130,'razorpay_enabled','1','2026-10-13 08:37:09','2026-11-10 07:20:04'),(131,'razorpay_env','live','2026-10-13 08:37:09','2026-11-10 07:20:04'),(132,'razorpay_test_key_id','','2026-10-13 08:37:09','2026-11-10 07:20:04'),(133,'razorpay_test_key_secret','','2026-10-13 08:37:09','2026-10-13 08:37:09'),(134,'razorpay_live_key_id','rzp_test_placeholder','2026-10-13 08:37:09','2026-11-10 07:20:04'),(135,'razorpay_live_key_secret','placeholder_razorpay_secret','2026-10-13 08:37:09','2026-11-10 07:20:04'),(136,'free_shipping_threshold','1','2026-10-13 08:37:09','2026-11-10 07:20:04'),(137,'standard_shipping_charge','1','2026-10-13 08:37:09','2026-11-10 07:20:04'),(138,'home_diwali_images_left','[]','2026-10-14 00:47:24','2026-10-16 14:35:27'),(139,'home_diwali_images_right','[]','2026-10-14 00:47:24','2026-10-16 14:35:27'),(140,'diwali_section_submitted','1','2026-10-13 19:21:59','2026-10-16 14:35:27'),(141,'existing_home_diwali_images_left','[\"https://ssptex.com\\/cdn\\/shop\\/collections\\/pure-venpattu-saree-collection-9624379_v=1786521269&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/south-cotton-collection-3212632_v=1786017975&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/exclusive-pure-silk-9427867_v=1785135584&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/butta-soft-silk-collection-2562138_v=1787402589&width=600.jpg\"]','2026-10-13 19:21:59','2026-10-13 19:21:59'),(142,'existing_home_diwali_images_right','[\"https://ssptex.com\\/cdn\\/shop\\/collections\\/fancy-cotton-sarees-9986664_v=1787402587&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/malmal-cotton-5810961_v=1785063029&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/banarasi-collection-9668374_v=1785495538&width=600.jpg\",\"https://ssptex.com\\/cdn\\/shop\\/collections\\/elampillai-budget-collection-5235329_v=1787402594&width=600.jpg\"]','2026-10-13 19:21:59','2026-10-13 19:21:59'),(143,'phone_no','9789296751',NULL,'2026-10-17 19:29:55'),(144,'contact_email','naaveensaaran.01319@gmail.com',NULL,'2026-10-17 19:29:55'),(145,'support_email','naaveensaaran.01319@gmail.com',NULL,NULL),(146,'business_hours','Mon-Sat, 9:00 AM to 8:00 PM',NULL,'2026-10-17 19:29:55'),(147,'youtube_link','https://youtube.com/@srisakthiselvitex',NULL,NULL),(148,'google_map_embed','https://maps.google.com/maps?q=Sri+Sakthi+Selvi+Tex+Main+Road+Elampillai+Salem+Tamil+Nadu+637502&t=&z=15&ie=UTF8&iwloc=&output=embed','2026-10-14 04:46:06','2026-10-17 19:29:55'),(149,'sticky_header_enabled','1','2026-10-14 06:55:04','2026-10-14 06:55:04'),(150,'sticky_header_shadow','1','2026-10-14 06:55:04','2026-10-14 06:55:04'),(151,'sticky_announcement_enabled','0','2026-10-14 06:55:04','2026-10-14 06:55:04'),(152,'youtube_channel_tabs','[{\"name\":\"SRI SAKTHI Selvi\",\"target\":\"sri_sakthi_selvi\",\"image\":\"uploads\\/settings\\/1792257076_0b6ee37d3751df3f0233.jpg\",\"link\":\"\",\"visible\":1},{\"name\":\"OFFER CHANNEL\",\"target\":\"offer_channel\",\"image\":\"uploads\\/settings\\/1792257076_861ba781d04501d6b698.png\",\"link\":\"\",\"visible\":1},{\"name\":\"ELITE C\",\"target\":\"elite_c\",\"image\":\"uploads\\/settings\\/1792257076_1ad7b372faef0d4accc7.jpg\",\"link\":\"\",\"visible\":1}]','2026-10-16 19:41:22','2026-10-17 17:11:16'),(153,'google_client_id','796019416522-6r427f081dfsng8qdtdqs0kd7dbjpk55.apps.googleusercontent.com','2026-10-16 23:11:09','2026-10-16 23:11:09'),(154,'google_client_secret','GOCSPX-placeholder_secret_clean','2026-10-16 23:11:09','2026-10-16 23:11:09'),(155,'admin_password_hash','$2y$10$PUDa7/VGeHJKJ0HfCPOUze.KKGNPiyPSVOf.9b2hT8S8HEqIKzeKa','2026-10-17 02:48:38','2026-10-18 08:18:07'),(156,'admin_name','Administrator','2026-10-17 02:48:38','2026-10-17 02:48:43'),(157,'admin_email','selvitex@gmail.com','2026-10-17 02:48:38','2026-10-18 08:18:07'),(158,'about_store_locations','[{\"name\":\"Flagship Showroom (Elampillai)\",\"address\":\"D\\/No.13\\/10, Periya Mammu St, Elampillai to Chinnapampattii, Main Road, Near Bavana Ganapathi Kovil, Elampillai - 637502\",\"timings\":\"Monday - Sunday: 9:00 AM - 9:00 PM\",\"map_link\":\"https:\\/\\/maps.google.com\\/?q=Sri+Sakthi+Selvi+Tex+Elampillai\",\"map_embed\":\"https:\\/\\/maps.google.com\\/maps?q=Sri+Sakthi+Selvi+Tex+Main+Road+Elampillai+Salem+Tamil+Nadu+637502&t=&z=15&ie=UTF8&iwloc=&output=embed\",\"phone\":\"9789296751\",\"whatsapp\":\"919789296751\"}]','2026-10-17 17:38:39','2026-10-17 17:38:39'),(159,'global_font_family','Prata','2026-10-17 19:18:25','2026-10-17 19:29:55'),(160,'heading_font_family','Prata','2026-10-17 19:18:25','2026-10-17 19:29:55'),(161,'module_product_sku','0',NULL,'2026-11-02 12:05:16'),(171,'about_store_map_embed','https://maps.google.com/maps?q=Sri+Sakthi+Selvi+Tex+Main+Road+Elampillai+Salem+Tamil+Nadu+637502&t=&z=15&ie=UTF8&iwloc=&output=embed',NULL,NULL),(183,'x_link','https://x.com/srisakthiselvitex',NULL,NULL),(184,'social_whatsapp','919789296751',NULL,NULL),(185,'social_youtube','https://youtube.com/@srisakthiselvitex',NULL,NULL),(186,'social_facebook','https://facebook.com/srisakthiselvitex',NULL,NULL),(187,'social_instagram','https://instagram.com/srisakthiselvitex',NULL,NULL),(188,'social_twitter','https://twitter.com/srisakthiselvitex',NULL,NULL),(189,'contact_us_banner','[\"uploads\\/settings\\/1792258160_78817d9871c35cd12ad9.jpg\"]',NULL,NULL),(190,'contact_us_text_overlay_enabled','1',NULL,NULL),(191,'contact_us_quote_text','\"We Weave Authentic Relationships\"',NULL,NULL),(192,'contact_us_heading_text','Get in Touch with Selvi Tex',NULL,NULL),(193,'contact_us_subtitle_text','ELAMPILLAI WEAVING SHOWROOM & SUPPORT',NULL,NULL),(194,'home_must_visit_category_id','','2026-10-20 06:01:58','2026-10-20 06:01:58'),(195,'home_must_visit_limit','3','2026-10-20 06:01:58','2026-10-20 06:01:58'),(196,'attribute_colors','[{\"name\":\"Red\",\"code\":\"#dc2626\",\"dark\":1},{\"name\":\"Maroon\",\"code\":\"#800020\",\"dark\":1},{\"name\":\"Pink\",\"code\":\"#ec4899\",\"dark\":1},{\"name\":\"Blue\",\"code\":\"#2563eb\",\"dark\":1},{\"name\":\"Navy\",\"code\":\"#1e3a8a\",\"dark\":1},{\"name\":\"Green\",\"code\":\"#16a34a\",\"dark\":1},{\"name\":\"Teal\",\"code\":\"#0d9488\",\"dark\":1},{\"name\":\"Yellow\",\"code\":\"#eab308\",\"dark\":0},{\"name\":\"Orange\",\"code\":\"#ea580c\",\"dark\":1},{\"name\":\"Gold\",\"code\":\"#d97706\",\"dark\":1},{\"name\":\"Purple\",\"code\":\"#9333ea\",\"dark\":1},{\"name\":\"Lavender\",\"code\":\"#c084fc\",\"dark\":0},{\"name\":\"Black\",\"code\":\"#18181b\",\"dark\":1},{\"name\":\"Cream\",\"code\":\"#fef08a\",\"dark\":0},{\"name\":\"White\",\"code\":\"#ffffff\",\"dark\":0}]',NULL,NULL),(197,'attribute_sizes','[{\"name\":\"0-3 Months\",\"category\":\"Kids\",\"order\":1},{\"name\":\"3-6 Months\",\"category\":\"Kids\",\"order\":2},{\"name\":\"6-12 Months\",\"category\":\"Kids\",\"order\":3},{\"name\":\"1-2 Yrs\",\"category\":\"Kids\",\"order\":4},{\"name\":\"2-3 Yrs\",\"category\":\"Kids\",\"order\":5},{\"name\":\"3-4 Yrs\",\"category\":\"Kids\",\"order\":6},{\"name\":\"4-5 Yrs\",\"category\":\"Kids\",\"order\":7},{\"name\":\"5-6 Yrs\",\"category\":\"Kids\",\"order\":8},{\"name\":\"6-7 Yrs\",\"category\":\"Kids\",\"order\":9},{\"name\":\"7-8 Yrs\",\"category\":\"Kids\",\"order\":10},{\"name\":\"8-9 Yrs\",\"category\":\"Kids\",\"order\":11},{\"name\":\"9-10 Yrs\",\"category\":\"Kids\",\"order\":12},{\"name\":\"10-12 Yrs\",\"category\":\"Kids\",\"order\":13},{\"name\":\"12-14 Yrs\",\"category\":\"Kids\",\"order\":14},{\"name\":\"Free Size\",\"category\":\"General\",\"order\":15},{\"name\":\"S\",\"category\":\"Apparel\",\"order\":16},{\"name\":\"M\",\"category\":\"Apparel\",\"order\":17},{\"name\":\"L\",\"category\":\"Apparel\",\"order\":18},{\"name\":\"XL\",\"category\":\"Apparel\",\"order\":19},{\"name\":\"XXL\",\"category\":\"Apparel\",\"order\":20},{\"name\":\"3XL\",\"category\":\"Apparel\",\"order\":21},{\"name\":\"4 Muzham (Single Dhoti)\",\"category\":\"Dhoti\",\"order\":22},{\"name\":\"8 Muzham (Double Dhoti)\",\"category\":\"Dhoti\",\"order\":23},{\"name\":\"9x5 (Dhoti + Angavastram Set)\",\"category\":\"Dhoti\",\"order\":24},{\"name\":\"10x6 (Pattu Dhoti + Shawl Set)\",\"category\":\"Dhoti\",\"order\":25},{\"name\":\"2.0 Meters (Single Dhoti)\",\"category\":\"Dhoti\",\"order\":26},{\"name\":\"4.0 Meters (Double Dhoti)\",\"category\":\"Dhoti\",\"order\":27},{\"name\":\"Readymade \\/ Pocket Dhoti\",\"category\":\"Dhoti\",\"order\":28}]',NULL,NULL),(198,'module_creation_type','1',NULL,'2026-11-02 12:05:16'),(199,'module_measurements_section','0',NULL,'2026-11-02 12:05:16');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_category_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subcategories_parent_category_id_foreign` (`parent_category_id`),
  CONSTRAINT `subcategories_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--


/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (11,8,'silk cotton','silk-cotton','2026-10-17 15:43:15','2026-10-17 15:43:15','uploads/categories/1792251795_80e6f9388d317c688427.jpg',NULL),(12,8,'Malmaal Cotton','malmaal-cotton','2026-10-17 15:44:04','2026-10-17 15:44:04','uploads/categories/1792251844_532ba87a498a4be2e972.jpg',NULL),(13,8,'Kotta Cotton','kotta-cotton','2026-10-17 15:44:42','2026-10-17 15:44:42','uploads/categories/1792251882_c542254719c6e1e1ab24.jpg',NULL),(14,4,'Kanchipuram Sarees','kanchipuram-sarees','2026-10-17 15:45:14','2026-10-17 15:45:14','uploads/categories/1792251913_ec090511c273d144a5ef.jpg',NULL),(15,8,'Erode Cotton','erode-cotton','2026-10-20 13:28:22','2026-10-20 13:28:22','uploads/categories/1792502902_ec2c95a40a8f1f95f0f4.jpg',NULL),(16,4,'Pure Soft Silk','pure-soft-silk','2026-10-23 05:07:40','2026-10-23 05:07:40','uploads/categories/1792732060_3491cf7555ade6a947cb.jpg',NULL),(17,9,'Saree+Dhoti','sareedhoti','2026-10-27 05:41:49','2026-10-27 05:41:49',NULL,NULL);
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;


--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--


/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
INSERT INTO `subscribers` VALUES (1,'test_buyer_1791927308@example.com','2026-10-13 21:35:08'),(2,'kavitha.devi@example.com','2026-10-13 21:35:08'),(3,'naaveensaaran.01319@gmail.com','2026-10-17 18:41:09');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;


--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--


/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Guest Shopper','guest@ssptex.com',NULL,NULL,'2026-10-13 09:01:24','2026-10-13 09:01:24'),(2,'Naveen S','testcustomer@example.com',NULL,'google_1791987647','2026-10-14 14:20:47','2026-10-14 14:20:47'),(3,'Naveen S','naaveensaaran.01319@gmail.com',NULL,'108019614845177430412','2026-10-16 17:49:04','2026-10-16 17:49:04'),(4,'Naveen','naveen@example.com',NULL,NULL,'2026-10-20 08:46:33','2026-10-20 08:46:33'),(5,'NAVEEN SARAVANAN','naveeensaran.19121@gmail.com',NULL,'100993296325252177489','2026-10-20 13:06:07','2026-10-20 13:06:07'),(6,'Naveen Saravanan','naveensaran.01319@gmail.com',NULL,NULL,'2026-11-10 07:21:03','2026-11-10 07:21:03'),(7,'Hayathbasha','hayathbashascorp98@gmail.com',NULL,NULL,'2026-11-10 07:26:38','2026-11-10 07:26:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-19 16:42:13

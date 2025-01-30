-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 06:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blinkbasket`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`) VALUES
(9, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user`, `email`, `password`, `reset_token`, `reset_token_expiration`) VALUES
(1, 'aryan', 'aryanamipara3@gmail.com', '$2y$10$fPdujaPS08vSRntrZt2hpOYF34EyR8czzA/.2gMuQeWCHE1CTEmWa', NULL, NULL),
(2, 't', 'kachhadiyafenil11@gmail.com', '$2y$10$HwKeeDHIWTD2CTP5TSzMhu2wmqXBay11AoFdWzV.2vWso7sQXDBpi', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `payment_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `order_status`, `quantity`, `total_amount`, `order_date`, `full_name`, `delivery_address`, `email`, `phone_no`, `payment_method`) VALUES
(1, 1, 5, 'Pending', 1, 399.00, '2025-01-27 05:57:41', 'aryan', 'qaz', 'aryanamipara3@gmail.com', '7418529630', 'upi'),
(2, 1, 9, 'Pending', 1, 599.00, '2025-01-27 06:04:17', 'qa', 'qazsd', 'aryanamipara3@gmail.com', '7410852963', 'upi'),
(3, 1, 9, 'Pending', 1, 798.00, '2025-01-27 06:11:36', 'wsx', 'qazsw', 'aryanamipara3@gmail.com', '9638520741', 'upi'),
(4, 1, 9, 'Pending', 1, 798.00, '2025-01-27 06:31:04', 'qa', 'qa', 'aryanamipara3@gmail.com', '9632857410', 'online'),
(5, 1, 9, 'Pending', 1, 798.00, '2025-01-27 06:34:37', 'qa', 'qa', 'aryanamipara3@gmail.com', '9632857410', 'online'),
(6, 1, 9, 'Pending', 1, 798.00, '2025-01-27 06:40:54', 'qa', 'qa', 'aryanamipara3@gmail.com', '9632857410', 'online'),
(7, 1, 4, 'Pending', 1, 499.00, '2025-01-27 06:45:39', 'qaz', 'qaz', 'aryanamipara3@gmail.com', '9638527410', 'online'),
(8, 1, 4, 'Pending', 1, 499.00, '2025-01-27 06:57:04', 'qwasz', 'qwed', 'aryanamipara3@gmail.com', '1234567899', 'online'),
(9, 1, 0, 'Delivered', 0, 249.00, '2025-01-27 07:11:17', 'qaw', 'qaszse', 'aryanamipara3@gmail.com', '1236547890', 'COD');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `size`, `subtotal`) VALUES
(1, 1, 5, 1, 'L', 399.00),
(2, 2, 9, 1, 'L', 599.00),
(3, 3, 9, 1, 'L', 599.00),
(4, 3, 7, 1, 'M', 199.00),
(5, 4, 9, 1, 'L', 599.00),
(6, 4, 7, 1, 'M', 199.00),
(7, 5, 9, 1, 'L', 599.00),
(8, 5, 7, 1, 'M', 199.00),
(9, 6, 9, 1, 'L', 599.00),
(10, 6, 7, 1, 'M', 199.00),
(11, 7, 4, 1, '7', 499.00),
(12, 8, 4, 1, '7', 499.00),
(13, 9, 3, 1, '6', 249.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_urls` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `size1` varchar(255) DEFAULT NULL,
  `size1_stock` int(11) NOT NULL DEFAULT 0,
  `size2` varchar(255) DEFAULT NULL,
  `size2_stock` int(11) NOT NULL DEFAULT 0,
  `size3` varchar(255) DEFAULT NULL,
  `size3_stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `name`, `description`, `price`, `stock`, `image_urls`, `created_at`, `size1`, `size1_stock`, `size2`, `size2_stock`, `size3`, `size3_stock`) VALUES
(2, 1, 'Shoes', 'DOCTOR EXTRA SOFT Men\'s Sports Shoes with Memory Foam Cushioned Insole| Running Walking Gym Training Jogging Athletics| Comfortable & Stylish| Casual & Lightweight|Slipon Sneaker Gent\'s Boy\'s ART-2008', 1599.00, 10, 'images/pro2.webp,images/pro22.webp,images/pro222.webp', '2025-01-27 05:31:19', '6', 3, '7', 5, '8', 2),
(3, 1, 'Shoes', 'Nivia Flash 2.0 Badminton Shoes for Men | Your Go-to Shoe for Pickleball, Padel, and All Court Sports | Badminton Sports Shoes | (Blue/White/Sky Blue)', 999.00, 12, 'images/pro3.webp,images/pro33.webp,images/pro333.webp', '2025-01-27 05:33:40', '6', 4, '7', 6, '8', 2),
(4, 1, 'Shoes', 'Black Shoes', 499.00, 14, 'images/pro4.webp,images/pro44.webp,images/pro444.webp', '2025-01-27 05:34:24', '7', 3, '8', 5, '9', 3),
(5, 1, 'Shirt', 'Fancy Shirt', 399.00, 22, 'images/pro5.webp,images/pro55.webp,images/pro555.webp', '2025-01-27 05:36:11', 'M', 5, 'L', 8, 'XL', 8),
(6, 1, 'Shirt', 'Plain Shirt', 199.00, 21, 'images/pro6.webp,images/pro66.webp,images/pro666.webp', '2025-01-27 05:37:29', 'L', 8, 'XL', 5, 'XXL', 8),
(7, 1, 'T-Shirt', 'White T-Shirt', 199.00, 17, 'images/pro7.webp,images/pro77.webp,images/pro777.webp', '2025-01-27 05:38:29', 'M', 2, 'L', 8, 'XL', 3),
(8, 1, 'Hoody', 'Boy\'s Hoody', 299.00, 17, 'images/pro8.webp,images/pro88.webp,images/pro888.webp', '2025-01-27 05:39:51', 'M', 8, 'L', 5, 'XL', 3),
(9, 1, 'T-Shirt', 'Fancy T-Shirt', 599.00, 14, 'images/pro9.webp,images/pro99.webp,images/pro999.webp', '2025-01-27 05:41:00', 'L', 0, 'XL', 6, 'XXL', 3),
(10, 1, 'Clock', 'Wall Clock', 699.00, 10, 'images/pro10.webp,images/pro100.webp,images/pro1000.webp', '2025-01-27 05:41:59', '', 10, '', 0, '', 0),
(11, 1, 'Fancy Clock', 'Fancy Wall Clock', 999.00, 20, 'images/pro101.webp,images/pro1011.webp,images/pro10111.webp', '2025-01-27 05:43:12', '', 20, '', 0, '', 0),
(15, 1, 'AC', 'Panasonic 1.5 Ton 5 Star Wi-Fi Inverter Smart Split AC (India\'s 1st Matter Enabled RAC, Copper Condenser, 7in1 Convertible, True AI, 4 Way Swing, PM 0.1 Filter, CS/CU-NU18ZKY5W, 2024 Model, White)', 42999.00, 24, 'images/ac4.jpg,images/ac44.jpg,images/ac444.jpg', '2025-01-27 11:35:14', '', 24, '', 0, '', 0),
(17, 1, 'Audio Device', 'BRIVAL 16GB Mini Voice Recorder -Small Hidden Spy Audio Recorder Device | Long Super Storage Cpacity | Crystal Clear Sound Recorder Device for Professional and Personal Use (Black)', 1899.00, 30, 'images/ad1.jpg,images/ad11.jpg,images/ad111.jpg', '2025-01-27 11:37:06', '', 30, '', 0, '', 0),
(20, 1, 'Camera', 'CP PLUS 3 MP Full HD Smart Wi-fi CCTV Camera | 360° Pan & Tilt | View & Talk | Motion Alert | Night Vision | SD Card (Up to 128 GB) | Alexa & OK Google | 2-Way Talk | IR Distance 10Mtr | CP-E35A', 1449.00, 24, 'images/c1.webp,images/c11.jpg,images/c111.jpg', '2025-01-27 11:39:01', '', 24, '', 0, '', 0),
(25, 1, 'Fan', 'Crompton Energion Hyperjet 1200mm BLDC Ceiling Fan | Point Anywhere Remote Control | BEE 5 Star Rated Energy Efficient | Superior Air Delivery | Anti-Rust | 2 Year Manufacturer Warranty | Ivory Black', 2880.00, 32, 'images/f1.webp,images/f11.jpg,images/f111.jpg', '2025-01-27 11:43:42', '', 32, '', 0, '', 0),
(32, 1, 'Laptop', 'HP Victus, 13th Gen Intel Core i5-13420H, 6GB NVIDIA RTX 4050, 16GB DDR4, 512GB SSD (Win11, Office 21, Silver, 2.29kg) 144Hz, 9MS, IPS, 15.6-inch(39.6cm) FHD Gaming Laptop, Enhanced Cooling, fa1319TX', 80990.00, 20, 'images/l4.webp,images/l44.jpg,images/l444.jpg', '2025-01-27 11:48:34', '', 20, '', 0, '', 0),
(34, 1, 'Mobile', 'OnePlus 13 | Smarter with OnePlus AI (16GB RAM, 512GB Storage Midnight Ocean)', 76990.00, 10, 'images/Mo1.webp,images/Mo11.jpg,images/Mo111.jpg', '2025-01-27 11:50:31', '', 10, '', 0, '', 0),
(37, 1, 'Extension Board', 'Wipro Smart Extension with WiFi, 4-Socket Smart Extension Board with Alexa Support, 16 Amp Power Socket, Control Your Appliances from Anywhere, White (DSE2150), Pack of 1', 2000.00, 10, 'images/SH1.webp,images/SH11.jpg,images/SH111.jpg', '2025-01-27 11:52:44', '', 10, '', 0, '', 0),
(38, 1, 'Alexa', 'Amazon Echo Dot (5th Gen) | Smart speaker with Bigger sound, Motion Detection, Temperature Sensor, Alexa and Bluetooth| Blue', 5499.00, 24, 'images/SH2.jpg,images/SH22.jpg,images/SH222.jpg', '2025-01-27 11:53:26', '', 24, '', 0, '', 0),
(39, 1, 'Night Lamp', 'Hoard Led Plug In Smart Night Lamp With Automatic Sensor Smart Led Night Lamp -(Whiskey White) Pack Of 1(Polycarbonate)', 399.00, 23, 'images/SH3.webp,images/SH33.jpg,images/SH333.jpg', '2025-01-27 11:54:26', '', 23, '', 0, '', 0),
(40, 1, 'Auto Light', 'ROMINO Motion Sensor LED Light for Home with USB Charging Wireless Rechargeable Self Adhesive LED Body Induction Lamp Night Light On-Off Sensor for Bedroom Wardrobe, Cupboard, Stairs (1)', 329.00, 12, 'images/SH4.webp,images/SH44.jpg,images/SH444.jpg', '2025-01-27 11:55:21', '', 12, '', 0, '', 0),
(42, 1, 'TV', 'Acer 139 cm (55 inches) Super Series 4K Ultra HD Smart QLED Google TV with Android 14 (Black) | MEMC | ALLM | VRR | AI Picture Optimisation | 80W PRO Speakers | GIGA Bass | Dolby Vision-Atmos', 37990.00, 20, 'images/TV2.jpg,images/TV22.jpg,images/TV222.jpg', '2025-01-27 11:56:49', '', 20, '', 0, '', 0),
(45, 1, 'Air Hand Dryer', 'TARGET HYGIENE New Shape Premium Hot & Cold Jet Air Hand Dryer | ABS-Plastic Wall Mounted Electrical Auto Sensor Jet Air Hand Dryer for Office Mall Hotel School - 1200 W, White', 3990.00, 14, 'images/Acce1.jpg,images/Acce11.jpg,images/Acce111.jpg', '2025-01-27 12:07:31', '', 14, '', 0, '', 0),
(46, 1, 'Multipurpose Bathroom Shelf', 'GRIVAN Stainless Steel 3 Layer/3 Tier Multipurpose Bathroom Shelf/Rack/Organizer/Stand/Holder with Double Soap Dish and Toothbrush Holder Tumbler Bathroom Accessories', 1198.00, 27, 'images/Acce3.webp,images/Acce33.jpg,images/Acce333.jpg', '2025-01-27 12:08:50', '', 27, '', 0, '', 0),
(47, 1, 'Hanger', 'Gloxy Enterprise 24 Inch Wall Mounted Stainless Steel Bathroom Towel Hanger - Space-Saving - Durable Bathroom Accessories and Fittings - Easy Installation- (24 Inch, Chrome, Foldable)', 469.00, 34, 'images/Acce2.jpg,images/Acce22.jpg,images/Acce222.jpg', '2025-01-27 12:10:01', '', 34, '', 0, '', 0),
(50, 1, 'Cap', 'PALAY® Straw Hat Sun Hats for Women, Wide Brim Beach Hat for Women Summer with Printed Ribbon, Fashion UV Protection Visor Cap, Packable Travel Hat for Women Ladies', 699.00, 21, 'images/cap3.jpg,images/cap33.jpg,images/cap333.jpg', '2025-01-27 12:11:46', '', 21, '', 0, '', 0),
(52, 1, 'Clogs', 'SVAAR Men\'s Lightweight Classic Clogs || Sandals with Adjustable Back Strap for Men', 499.00, 23, 'images/Foot1.jpg,images/Foot11.jpg,images/Foot111.jpg', '2025-01-30 04:55:53', '7', 12, '8', 3, '9', 8),
(53, 1, 'Googles', 'Dervin UV Protected Square Rimless Sunglasses for Men and Women', 449.00, 14, 'images/Go1.jpg,images/Go11.jpg,images/Go111.jpg', '2025-01-30 04:59:29', '', 14, '', 0, '', 0),
(54, 1, 'Boy\'s Sweatshirt', 'Googo Gaaga Boy\'s Cotton full Sleeves Printed Sweatshirt and Pant Set in Multi Color', 499.00, 8, 'images/KD1.jpg,images/KD11.jpg,images/KD111.jpg', '2025-01-30 05:01:30', 'L', 5, 'M', 3, '', 0),
(55, 1, 'Men Cargo Pants', 'Lymio Men Cargo || Men Cargo Pants || Men Cargo Pants Cotton || Cargos for Men (Cargo-34-37)', 749.00, 17, 'images/Men1.jpg,images/Men11.jpg,images/Men111.jpg', '2025-01-30 05:02:44', 'L', 5, 'XL', 3, 'XXL', 9),
(56, 1, 'Women\'s Kurta', 'GoSriKi Women\'s Cotton Blend Embroidered Straight Kurta with Pant & Dupatta', 649.00, 28, 'images/Wo1.jpg,images/Wo11.jpg,images/Wo111.jpg', '2025-01-30 05:04:01', 'L', 9, 'XL', 12, 'XXL', 7),
(57, 1, 'Bed Sheet', 'Cozy Line Home Fashions Adeline Red Teal Khaki Floral Pint Pattern Real Patchwork 100% Cotton Reversible Coverlet Bedspread Quilt Bedding Set for Women (Red Aqua, Queen - 3 Piece)', 18999.00, 25, 'images/BL1.jpg,images/BL11.jpg,images/BL111.jpg', '2025-01-30 05:05:24', '', 25, '', 0, '', 0),
(58, 1, 'Carpets', 'TAUKIR CARPETS Handmade 3D Edge Collection Super Soft Microfiber Silk Touch Rectangular Rugs, Size 3X5,Feet Color, Shadow;Ivory', 2390.00, 8, 'images/CF1.webp,images/CF11.jpg,images/CF111.jpg', '2025-01-30 05:06:51', '9X5 Feet', 2, '8X4', 6, '', 0),
(59, 1, 'Kitchen Tool Set', 'Pigeon by Stovekraft Mio Nonstick Aluminium Cookware Gift Set, Includes Nonstick Flat Tawa, Nonstick Fry Pan, Kitchen Tool Set, Kadai with Glass Lid, 8 Pieces Non-Induction Base Kitchen Set - Red', 998.00, 24, 'images/CKT1.webp,images/CKT11.jpg,images/CKT111.jpg', '2025-01-30 05:07:50', '', 24, '', 0, '', 0),
(60, 1, 'Wooden Bookshelf', 'MODERN FURNITURE SHEESHAM Contemporary Solid Sheesham Wooden Bookshelf | Book Shelf Cabinet for Home & Office Living Room Furniture, Walnut Finish | Pre Assembled Bookshelf', 1699.00, 12, 'images/Fur1.jpg,images/Fur11.jpg,images/Fur111.jpg', '2025-01-30 05:09:09', '', 12, '', 0, '', 0),
(61, 1, 'Home Decoration', 'HindCraft Seven Chakra Crystal Tree Good Luck Showpiece for Home Decor Items Crystals Gemstones Bonsai Money Tree for Good Luck, Decoration Gift Item, Multicolor Golden Wire-8-10 Inches-200 Beads', 499.00, 11, 'images/HD1.webp,images/HD11.jpg,images/HD111.jpg', '2025-01-30 05:10:09', '', 11, '', 0, '', 0),
(62, 1, 'Juicer Mixer Grinder', 'Nutripro Copper Juicer Mixer Grinder - Smoothie Maker - 500 Watts (3 Jars, Silver) - 2 Year Warranty', 1799.00, 9, 'images/KA1.webp,images/KA11.jpg,images/KA111.jpg', '2025-01-30 05:11:04', '', 9, '', 0, '', 0),
(63, 1, 'LED Wall Lamp', 'FILLISKA Luxurious LED Wall Lamp for Home with Adjustable Colour Changing Function (Black Spiral-5253)', 1999.00, 19, 'images/L1.webp,images/L11.jpg,images/L111.jpg', '2025-01-30 05:12:46', '', 19, '', 0, '', 0),
(64, 1, 'Stainless Steel Flatware Set', '30 Piece Silverware Premium Stainless Steel Flatware Set, Mirror Polished Cutlery Set Durable Tableware Set, Includes - (Salad Fork 6+Fork 6+Knife 6+Salad Spoon 6+Tea Spoon 6,Gold Hotel Spoon,30)', 2404.00, 11, 'images/TW1.webp,images/TW11.jpg,images/TW111.jpg', '2025-01-30 05:15:40', '', 11, '', 0, '', 0),
(65, 1, 'Yoga Track Pants for Girls & Women', 'NEVER LOSE Women\'s Stretch Fit Yoga,Compression Leggings, Stretchable Gym wear Sports Leggings Ankle Length Workout Tights | Sports Fitness Yoga Track Pants for Girls & Women', 499.00, 18, 'images/Ac1.jpg,images/Ac11.jpg,images/Ac111.jpg', '2025-01-30 05:17:57', '', 18, '', 0, '', 0),
(66, 1, 'Exercise Machine', 'Life Line Fitness Multi Home Gym Multiple Muscle Workout Exercise Machine Chest Biceps Shoulder Back Triceps Legs For Men At Home,72Kg Weight Stack,Made In India(Hg-002(Without Installation))', 15999.00, 12, 'images/EM1.jpg,images/EM11.jpg,images/EM111.jpg', '2025-01-30 05:20:22', '', 12, '', 0, '', 0),
(67, 1, 'Multifunctional weight training kit', 'Burnlab 6 in 1 multifunctional weight training kit - Dumbells, Kettlebells, Barbells & Push up brackets in 1 | Adjustable Weights | Perfect for Full Body Workout for Men & Women (12, Kilograms)', 4459.00, 26, 'images/FA1.webp,images/FA11.jpg,images/FA111.jpg', '2025-01-30 05:21:18', '', 26, '', 0, '', 0),
(68, 1, 'Running Machine', 'LET\'s PLAY Cspeed Non-Motorized Curve Treadmill For Home Gym, Commercial Running Machine With 6 Resistance Level, Digital Display, 200Kg User Weight Support', 11668.00, 8, 'images/FE1.webp,images/FE11.jpg,images/FE111.jpg', '2025-01-30 05:23:56', '', 8, '', 0, '', 0),
(69, 1, 'Hand Exercise Gloves', 'XTRIM X-Macho Leather Gym Gloves for Men & Women with Wrist Support Accessories for Weightlifting, Hand Exercise Gloves with Half-Finger Length for Gym Workout (M, Fits 7.5-8 Inches,Blue-Black)', 399.00, 14, 'images/OS1.webp,images/OS11.jpg,images/OS111.jpg', '2025-01-30 05:27:51', '', 14, '', 0, '', 0),
(70, 1, 'Fitness Tracksuit', 'WMX Men\'s Sports Running Set Compression Shirt + Pants Skin-Tight Long Sleeves Quick Dry Fitness Tracksuit Gym Yoga Suit Athletics Fit', 499.00, 16, 'images/SG2.jpg,images/SG22.jpg,images/SG222.jpg', '2025-01-30 05:29:19', '', 16, '', 0, '', 0),
(71, 1, 'Yoga Mat', 'Bodyband Yoga Mat for Women and Men 4mm with Carry Strap EVA Material Extra Thick Exercise Mat for Workout Yoga Fitness Pilates and Meditation, Anti Tear Anti Slip For Home & Gym Use - Army Black', 249.00, 15, 'images/YP1.webp,images/YP11.jpg,images/YP111.jpg', '2025-01-30 05:31:03', '', 15, '', 0, '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_orders_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- SQL QUERIES FOR ELECTROHOLICS DATABASE

-- CREATE DATABASE electroholics;

-- Database set at config.php: $databaseName = 'electroholics'

START TRANSACTION;

-- Table structure for account

DROP TABLE IF EXISTS account;
CREATE TABLE IF NOT EXISTS account (
    accountID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    accountEmail varchar(255) NOT NULL UNIQUE,
    accountPassword varchar(255) NOT NULL,
    username varchar(255) NOT NULL UNIQUE,
    accountRegistrationDate date NOT NULL DEFAULT CURRENT_DATE,
    accountRole int NOT NULL DEFAULT 2 COMMENT '1 - Admin, 2 - Customer'
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for account
INSERT INTO account (accountEmail, accountPassword, username, accountRole) VALUES
('customer1@email.com', '$2y$10$hRDSgtfDCBtt.Fu1nyEYce7l56fiiEK7BeuC0uNzyUJE4R2oz4JVe', 'customer1', 2),
('customer2@email.com', '$2y$10$6XHOhXde7DR9hga814MJL.wOIGKoOodmsISF7zqRDBSb1fA47Ok.O', 'customer2', 2),
('admin1@email.com', '$2y$10$.widZukkG3knahPHzDdReenkrQhOH2oJ6WWdDPUExgpuwBdLJVvaO', 'admin1', 1);

-- Table structure for user_profile

DROP TABLE IF EXISTS user_profile;
CREATE TABLE IF NOT EXISTS user_profile (
    userID int PRIMARY KEY AUTO_INCREMENT,
    accountID int,
    userFullName varchar(255),
    userAddress varchar(255),
    userContact varchar(255),
    userDOB date,
    userProfileImagePath varchar(255),
    FOREIGN KEY (accountID) REFERENCES account(accountID) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for user_profile

INSERT INTO user_profile (accountID, userFullName, userAddress, userContact, userDOB, userProfileImagePath) VALUES
(1, 'Customer 1', 'Kota Kinabalu, Sabah', '012-345 6789', '2024-01-01', '../images/profilePictures/anya.jpeg'),
(2, 'Customer 2', '', '', '', ''),
(3, 'Admin 1', '', '', '', '');

-- Table structure for catalog_item

DROP TABLE IF EXISTS catalog_item;
CREATE TABLE IF NOT EXISTS catalog_item (
    productIndex int PRIMARY KEY AUTO_INCREMENT,
    productID varchar(16),
    productType varchar(16),
    productName varchar(255),
    productDescription varchar(255),
    productPrice double,
    productStock int,
    productImagePath varchar(255)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for CPUs for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('CPU001', 'cpu', 'Intel Core i5 10500 6 Cores/12 Threads 3.1/4.5Ghz LGA1200 CPU Processor', '', 705.00, 100, '../images/websiteElements/catalogueIMGs/cpu/LGA1200.png'),
('CPU002', 'cpu', 'Intel Core i5 12600 6 Cores/12 Threads 3.3/4.8 GHz LGA1700 CPU Processor', '', 1285.00, 90, '../images/websiteElements/catalogueIMGs/cpu/LGA1700.png'),
('CPU003', 'cpu', 'Intel Core i7 13700F 16 Cores/24 Threads 2.1/5.2GHz LGA1700 CPU Processor', '', 1709.00, 80, '../images/websiteElements/catalogueIMGs/cpu/LGA1700i7.png'),
('CPU004', 'cpu', 'AMD Ryzen 5 5600 6 Core/12 Threads 3.9/4.4GHz AM4 CPU Processor 100-100000927BOX', '', 819.00, 70, '../images/websiteElements/catalogueIMGs/cpu/Ryzen55600.png'),
('CPU005', 'cpu', 'AMD Ryzen 7 5700G 8 Core/16 Threads 3.8/4.6GHz AM4 CPU Processor 100-100000263BOX', '', 1249.00, 60, '../images/websiteElements/catalogueIMGs/cpu/Ryzen75700G.png'),
('CPU006', 'cpu', 'AMD Ryzen 9 5900X 12 Core/24 Threads 3.7/4.8GHz AM4 CPU Processor 100-100000061WOF', '', 2299.00, 50, '../images/websiteElements/catalogueIMGs/cpu/Ryzen95900X.png');

-- Data for Motherboards for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('MBD001', 'motherboards', 'Gigabyte Intel H610M H DDR4 Micro ATX LGA1700 Motherboard', '', 399.00, 30, '../images/websiteElements/catalogueIMGs/motherboards/H610MMotherboard.png'),
('MBD002', 'motherboards', 'Asrock B660M PG Riptide MATX Motherboard B660M PG RIPTIDE', '', 605.00, 20, '../images/websiteElements/catalogueIMGs/motherboards/AsrockMotherboard.png'),
('MBD003', 'motherboards', 'Gigabyte Intel Z590 AORUS XTREME E-ATX LGA1200 Motherboard ', '', 1899.00, 10, '../images/websiteElements/catalogueIMGs/motherboards/Z590Motherboard.png');

-- Data for Graphics Cards (GPUs) for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('GPU001', 'gpu', 'INNO3D GeForce GTX 1660 Twin X2 6GB GDDR5 Non OC Graphics Card N16602-06D5-1521VA15L', '', 939.00, 15, '../images/websiteElements/catalogueIMGs/gpu/gtx1660.png'),
('GPU002', 'gpu', 'Palit GeForce RTX 3050 Dual OC 8GB GDDR6 Graphics Card NE63050T19P1-190AD', '', 1599.00, 10, '../images/websiteElements/catalogueIMGs/gpu/rtx3050.png'),
('GPU003', 'gpu', 'Palit GeForce RTX 4070 Ti Gaming Pro 12GB GDDR6X Video Card NED407T019K9-1043A', '', 3919.00, 5, '../images/websiteElements/catalogueIMGs/gpu/rtx4070ti.png');

-- Data for Memory (RAM) for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('RAM001', 'ram', 'Corsair Vengeance RGB RS DDR4 3200MHz 8GB (1x8) Desktop Memory', '', 125.00, 75, '../images/websiteElements/catalogueIMGs/ram/vengeance1x8.png'),
('RAM002', 'ram', 'PNY XLR8 RGB DDR4 3200MHZ 8GB (1x8) Desktop Memory Silver', '', 116.00, 65, '../images/websiteElements/catalogueIMGs/ram/pny1x8.png'),
('RAM003', 'ram', 'Kingston FURY BEAST RGB DDR4 3600MHz 8GB (1x8) CL17 Desktop Memory', '', 134.00, 55, '../images/websiteElements/catalogueIMGs/ram/fury1x8.png');

-- Data for Storage Devices (SSDs and HDDs) for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('SSD001', 'ssd', 'Seagate BarraCuda 3.5" 1TB SATA3 Internal Hard Drive ST1000DM010', '', 159.00, 80, '../images/websiteElements/catalogueIMGs/ssd/seagate1tb.png'),
('SSD002', 'ssd', 'Seagate BarraCuda 3.5" 2TB SATA3 Internal Hard Drive ST2000DM008', '', 229.00, 70, '../images/websiteElements/catalogueIMGs/ssd/seagate2tb.png'),
('SSD003', 'ssd', 'Seagate BarraCuda 3.5" 8TB SATA3 Internal Hard Drive ST8000DM004', '', 1339.00, 30, '../images/websiteElements/catalogueIMGs/ssd/seagate8tb.png');

-- Data for Power Supplies (PSUs) for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('PSU001', 'psu', 'FSP HV Pro 550W 80 plus Bronze Power Supply FSP550-51AAC', '', 195.00, 35, '../images/websiteElements/catalogueIMGs/psu/hv550W.png'),
('PSU002', 'psu', 'Cooler Master G Gold 600W 80+ Gold Non Modular Power Supply', '', 299.00, 30, '../images/websiteElements/catalogueIMGs/psu/cm600W.png'),
('PSU003', 'psu', 'Cooler Master V650 Gold V2 Gold Full Modular Power Supply-White edition', '', 422.00, 25, '../images/websiteElements/catalogueIMGs/psu/cm650W.png');

-- Data for Cases and Cooling for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('CAS001', 'cases', 'Tecware Forge S TG ARGB ATX Black Gaming Case', '', 185.00, 45, '../images/websiteElements/catalogueIMGs/cases/tecware.png'),
('CAS002', 'cases', 'Cooler Master MasterBox TD500 ARGB Mesh ATX Case Black', '', 365.00, 40, '../images/websiteElements/catalogueIMGs/cases/cmmasterbox.png'),
('CAS003', 'cases', 'Cooler Master MasterCase H500P ARGB Mesh Mid Tower ATX Case Black', '', 559.00, 35, '../images/websiteElements/catalogueIMGs/cases/cmh500p.png'),
('CLG001', 'cooling', 'Thermalright TL-C12015 120mm Cooling Fan - Black', '', 55.00, 52, '../images/websiteElements/catalogueIMGs/cooling/thermalright.png'),
('CLG002', 'cooling', 'Corsair iCUE SP140 RGB PRO Performance 140mm Fan', '', 119.00, 48, '../images/websiteElements/catalogueIMGs/cooling/corsaircooler.png'),
('CLG003', 'cooling', 'Corsair iCUE QL140 RGB PWM White 140mm Fan', '', 179.00, 46, '../images/websiteElements/catalogueIMGs/cooling/corsaircooler2.png');

-- Data for Cables and Connectors for catalog_item
INSERT INTO catalog_item (productID, productType, productName, productDescription, productPrice, productStock, productImagePath) VALUES
('CBL001', 'cables', 'Bitfenix Sleeved 45cm Blue/Black 24-pin ATX ext Cable', '', 2.00, 200, '../images/websiteElements/catalogueIMGs/cables/atxcable.png'),
('CBL002', 'cables', 'Orico XD-DPDT4 DP (M) to DP (M) Version 1.2 4K Adapter Cable - 3M', '', 33.00, 55, '../images/websiteElements/catalogueIMGs/cables/dpcable.png'),
('CBL003', 'cables', 'Bitfenix Sleeved 45cm Blue/Black 8-pin video card ext cable', '', 2.00, 150, '../images/websiteElements/catalogueIMGs/cables/videocardcable.png');

-- Table structure for cart

DROP TABLE IF EXISTS cart;
CREATE TABLE IF NOT EXISTS cart (
    cartID int PRIMARY KEY AUTO_INCREMENT,
    userID int,
    totalCost double,
    isActive int,
    FOREIGN KEY (userID) REFERENCES user_profile(userID) ON DELETE CASCADE
)   ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for cart

INSERT INTO cart (userID, totalCost, isActive) VALUES
(1, 1990.00, 0),
(2, 445.00, 0),
(3, 0.00, 1),
(1, 0.00, 1),
(2, 0.00, 1);

-- Table structure for item_order

DROP TABLE IF EXISTS item_order;
CREATE TABLE IF NOT EXISTS item_order (
    cartID int,
    productIndex int,
    orderQuantity int,
    orderCost double,
    FOREIGN KEY (cartID) REFERENCES cart(cartID) ON DELETE CASCADE,
    FOREIGN KEY (productIndex) REFERENCES catalog_item(productIndex) ON DELETE CASCADE
)   ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for item_order
INSERT INTO item_order (cartID, productIndex, orderQuantity, orderCost) VALUES
(1, 1, 1, 705.00),
(1, 2, 1, 1285.00),
(2, 19, 1, 195.00),
(2, 13, 2, 250.00);

-- Table structure for order_receipt

DROP TABLE IF EXISTS order_receipt;
CREATE TABLE IF NOT EXISTS order_receipt (
    orderID int PRIMARY KEY AUTO_INCREMENT,
    cartID int,
    paymentAmount double,
    orderDatetime datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cartID) REFERENCES cart(cartID)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data for order_receipt

INSERT INTO order_receipt (orderID, cartID, paymentAmount, orderDatetime) VALUES
(1, 1, 1990.00, '2024-01-14 14:11:05'),
(2, 2, 445.00, '2024-01-14 14:11:28');
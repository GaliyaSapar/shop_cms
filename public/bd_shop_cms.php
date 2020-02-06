<?php

$servername = 'localhost';
$username = 'root';
$password = 'def';
$dbname = 'shop_cms';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
} 

// $sql = 'CREATE DATABASE shop_cms;';


// $sql = 'CREATE TABLE products (
// id INT UNSIGNED AUTO_INCREMENT,
// name VARCHAR(255) NOT NULL,
// price FLOAT(10,2) NOT NULL,
// amount INT NOT NULL,
// description TEXT,
// vendor_id INT UNSIGNED NOT NULL,
// PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
// ';


// $sql = 'CREATE TABLE vendors (
// id INT UNSIGNED AUTO_INCREMENT,
// name VARCHAR(255) NOT NULL,
// description TEXT,
// PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;;
// ';


// $sql = 'CREATE TABLE folders (
// id INT UNSIGNED AUTO_INCREMENT,
// name VARCHAR(255) NOT NULL,
// description TEXT,
// PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;;
// ';

// $sql = 'CREATE TABLE products_folders (
// product_id INT UNSIGNED NOT NULL,
// folder_id INT UNSIGNED NOT NULL,
// PRIMARY KEY(product_id, folder_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;;
// ';


// $sql = "INSERT INTO vendors (name, description)
// VALUES ('LG', 'LG Electronics Inc. — южнокорейская компания, один из крупнейших мировых производителей потребительской электроники и бытовой техники. Входит в состав конгломерата LG Group. Главный офис компании LG Electronics находится в Сеуле, Республика Корея, 120 представительств компании открыты в 95 странах мира'),
// ('SAMSUNG', 'Samsung Group — южнокорейская группа компаний, один из крупнейших чеболей, основанный в 1938 году. На мировом рынке известен как производитель высокотехнологичных компонентов, включая полноцикловое производство интегральных микросхем, телекоммуникационного оборудования, бытовой техники, аудио- и видеоустройств'),
// ('PHILIPS', 'Koninklijke Philips N.V. — нидерландская транснациональная компания'),
// ('LENOVO', 'Lenovo Group Limited — китайская компания, выпускающая персональные компьютеры и другую электронику. Является крупнейшим производителем персональных компьютеров в мире с долей на рынке более 20 %, а также занимает пятое место по производству мобильных телефонов'),
// ('HP', 'Hewlett-Packard — одна из крупнейших американских компаний в сфере информационных технологий, существовавшая в период 1939—2015 годов, поставщик аппаратного и программного обеспечения для организаций и индивидуальных потребителей. Штаб-квартира — в Пало-Альто'),
// ('POLARIS','это международный производитель бытовой техники, техники для кухни, товаров для красоты и здоровья, климатического оборудования')
// ";



$sql = "INSERT INTO folders (name, description)
VALUES ('Computers', 'some text'),
('Smartphones', 'some text'),
('Kuhonnaya tehnika', 'some text'),
('Melkaya tehnika', 'some text'),
('Krupnaya tehnika', 'some text'),
('Fridges','some text'),
('Kettles','some text')
";

// $sql = "INSERT INTO products (name, price, amount, description, vendor_id)
// VALUES ('Чайник 2', 10000.5, 20, 'some text', 3),

// ('Ноутбук 2', 250000, 30, 'some text', 5)
// ";

if (mysqli_query($conn, $sql)) {
	echo 'success';
} else {
	echo 'Error creating db: ' . mysqli_error($conn);
}

//echo '<pre>'; var_dump($sql); echo '</pre>';
// mysqli_close($conn);
?>
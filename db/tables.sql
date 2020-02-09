
# CREATE DATABASE shop_cms;

CREATE TABLE products (
id INT UNSIGNED AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
price FLOAT(10,2) NOT NULL,
amount INT NOT NULL,
description TEXT,
vendor_id INT UNSIGNED NOT NULL,
PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE vendors (
id INT UNSIGNED AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
description TEXT,
PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE folders (
id INT UNSIGNED AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
description TEXT,
PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE products_folders (
product_id INT UNSIGNED NOT NULL,
folder_id INT UNSIGNED NOT NULL,
PRIMARY KEY(product_id, folder_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO vendors (name, description)
VALUES ('LG', 'LG Electronics Inc. — южнокорейская компания, один из крупнейших мировых производителей потребительской электроники и бытовой техники. Входит в состав конгломерата LG Group. Главный офис компании LG Electronics находится в Сеуле, Республика Корея, 120 представительств компании открыты в 95 странах мира'),
('SAMSUNG', 'Samsung Group — южнокорейская группа компаний, один из крупнейших чеболей, основанный в 1938 году. На мировом рынке известен как производитель высокотехнологичных компонентов, включая полноцикловое производство интегральных микросхем, телекоммуникационного оборудования, бытовой техники, аудио- и видеоустройств'),
('PHILIPS', 'Koninklijke Philips N.V. — нидерландская транснациональная компания'),
('LENOVO', 'Lenovo Group Limited — китайская компания, выпускающая персональные компьютеры и другую электронику. Является крупнейшим производителем персональных компьютеров в мире с долей на рынке более 20 %, а также занимает пятое место по производству мобильных телефонов'),
('HP', 'Hewlett-Packard — одна из крупнейших американских компаний в сфере информационных технологий, существовавшая в период 1939—2015 годов, поставщик аппаратного и программного обеспечения для организаций и индивидуальных потребителей. Штаб-квартира — в Пало-Альто'),
('POLARIS','это международный производитель бытовой техники, техники для кухни, товаров для красоты и здоровья, климатического оборудования')



INSERT INTO products (name, price, amount, description, vendor_id)
VALUES ('Чайник 2', 10000.5, 20, 'some text', 3),

('Ноутбук 2', 250000, 30, 'some text', 5);

INSERT INTO folders (name, description)
VALUES ('Чайники', 'some text'),
       ('Мелкая бытовая техника', 'some text'),
       ('Крупная бытовая техника', 'some text'),
       ('Смартфоны', 'some text'),
       ('Холодильники', 'some text'),
       ('Ноутбуки', 'some text');




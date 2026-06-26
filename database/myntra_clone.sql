CREATE DATABASE IF NOT EXISTS cartifyx CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cartifyx;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS wishlist;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  phone VARCHAR(30),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  image VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(180) NOT NULL,
  brand VARCHAR(140) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  mrp DECIMAL(10,2) NOT NULL,
  image VARCHAR(500),
  sizes VARCHAR(160),
  colors VARCHAR(160),
  stock INT DEFAULT 0,
  status ENUM('active','inactive') DEFAULT 'active',
  is_featured TINYINT(1) DEFAULT 0,
  is_bestseller TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  size VARCHAR(40),
  color VARCHAR(60),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_wishlist (user_id, product_id),
  CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  full_name VARCHAR(140) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address_line VARCHAR(255) NOT NULL,
  city VARCHAR(80) NOT NULL,
  state VARCHAR(80) NOT NULL,
  pincode VARCHAR(20) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  address_id INT,
  subtotal DECIMAL(10,2) NOT NULL,
  gst DECIMAL(10,2) NOT NULL,
  shipping DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(60) NOT NULL,
  status VARCHAR(40) DEFAULT 'Placed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_address FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  size VARCHAR(40),
  color VARCHAR(60),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(60) NOT NULL,
  status VARCHAR(40) DEFAULT 'Pending',
  transaction_id VARCHAR(120),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO admins (name, email, password) VALUES
('CArtifyX Admin', 'admin@cartifyx.com', SHA2('admin123', 256));

INSERT INTO users (name, email, phone, password) VALUES
('Demo Customer', 'demo@cartifyx.com', '9876543210', SHA2('demo123', 256));

INSERT INTO categories (id, name, slug, image) VALUES
(1,'Women','women','https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80'),
(2,'Men','men','https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=900&q=80'),
(3,'Footwear','footwear','https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&q=80'),
(4,'Accessories','accessories','https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=80');

INSERT INTO products (category_id,name,brand,description,price,mrp,image,sizes,colors,stock,status,is_featured,is_bestseller) VALUES
(1,'Satin Wrap Midi Dress','Aurelia Noir','Liquid satin wrap dress with a polished evening drape.',2499,3999,'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=80','XS,S,M,L,XL','Rose,Black,Champagne',35,'active',1,1),
(2,'Tailored Linen Blazer','Maison Mode','Breathable linen blazer with a clean premium profile.',4299,6999,'https://images.unsplash.com/photo-1506629905607-d405b7a30db9?auto=format&fit=crop&w=900&q=80','S,M,L,XL','Ivory,Navy,Black',24,'active',1,1),
(3,'Premium Sneaker Drop','Street Luxe','Clean street sneakers with cushioned soles.',3299,5499,'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80','6,7,8,9,10','White,Black,Tan',42,'active',0,1),
(4,'Structured Tote Bag','Velvet Edit','Roomy tote with polished metal hardware.',1999,3499,'https://images.unsplash.com/photo-1594223274512-ad4803739b7c?auto=format&fit=crop&w=900&q=80','One Size','Tan,Black,Burgundy',30,'active',1,0),
(1,'Minimal Co-ord Set','CArtifyX Studio','A soft co-ord made for travel, brunch, and office Fridays.',2899,4499,'https://images.unsplash.com/photo-1550614000-4895a10e1bfd?auto=format&fit=crop&w=900&q=80','XS,S,M,L','Oat,Olive,Black',28,'active',1,0),
(2,'Oversized Resort Shirt','North Label','A relaxed shirt in a premium cotton blend.',1599,2599,'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=80','S,M,L,XL,XXL','White,Sage,Ink',45,'active',1,1),
(4,'Gold Minimal Hoops','Velvet Edit','Lightweight statement hoops for day to night styling.',899,1499,'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=900&q=80','One Size','Gold,Silver',80,'active',0,0),
(3,'Leather Block Heels','Aurelia Noir','Comfortable block heels with a clean occasionwear profile.',2799,4299,'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=900&q=80','5,6,7,8','Black,Nude',25,'active',1,0);

INSERT INTO addresses (user_id, full_name, phone, address_line, city, state, pincode) VALUES
(1,'Demo Customer','9876543210','Bandra West','Mumbai','Maharashtra','400050');

INSERT INTO orders (user_id, address_id, subtotal, gst, shipping, total, payment_method, status) VALUES
(1,1,2499.00,449.82,0.00,2948.82,'UPI','Delivered');

INSERT INTO order_items (order_id, product_id, quantity, price, size, color) VALUES
(1,1,1,2499.00,'M','Black');

INSERT INTO payments (order_id, amount, method, status, transaction_id) VALUES
(1,2948.82,'UPI','Paid','CXDEMO1001');

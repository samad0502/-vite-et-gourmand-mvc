

CREATE DATABASE IF NOT EXISTS vite_gourmand;




CREATE TABLE diets (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

INSERT INTO diets (id, name) VALUES
(1, 'Classique'),
(2, 'Végétarien'),
(3, 'Vegan');

CREATE TABLE themes (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

INSERT INTO themes (id, name) VALUES
(1, 'Mariage'),
(2, 'Anniversaire');

CREATE TABLE roles (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50)  UNIQUE NOT NULL,
  label VARCHAR(100) NOT NULL
);

INSERT INTO roles (id, name, label) VALUES
(1, 'admin', 'Administrateur'),
(2, 'employee', 'Employé'),
(3, 'utilisateur', 'Utilisateur');

CREATE TABLE opening_hours (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  day_name VARCHAR(20) NOT NULL,
  open_time TIME,
  close_time TIME,
  is_closed TINYINT
);

INSERT INTO opening_hours (id, day_name, open_time, close_time, is_closed) VALUES
(1, 'Lundi', '09:00:00', '18:00:00', 0),
(2, 'Mardi', '09:00:00', '18:00:00', 0),
(3, 'Mercredi', '09:00:00', '18:00:00', 0);



CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  zip_code VARCHAR(10) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME ,
  role_id INT NOT NULL,
  is_active TINYINT,
  reset_token VARCHAR(255),
  reset_expires_at DATETIME,
  FOREIGN KEY (role_id) REFERENCES roles (id)
);

INSERT INTO users (id, firstname, lastname, address, city, zip_code, phone, email, password, role_id) VALUES
(9, 'José', 'Admin', '', '', '', '', 'jose@vitegourmand.fr', '$2y$12$NbxpKz7qMuQZoZjaK4QnresVgk18AQ3yjkvHPY44lIYIzDyLf2Ug6', 1),
(11, 'Julie', 'Livreuse', '', '', '', '', 'julie@vitegourmand.fr', '$2y$12$28A.dFFnOiWdEChyn02HfeFxKGrjyH6PKh1qVsfwJzap3/2pNfJc6', 2),
(14, 'Juliette', 'Josette', '5 rue de la viennoiserie', 'Bordeaux', '33000', '0512003400', 'jujudu33@email.fr', '$2y$10$KZ/NeW36r62wm2lK3TcceuwvFv4sUq6/Oyz1nN2IigNAkFT7OjsV6', 3);

CREATE TABLE menus (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  starter VARCHAR(255),
  main_course VARCHAR(255),
  dessert VARCHAR(255),
  price DECIMAL(10,2) NOT NULL,
  min_people INT NOT NULL,
  remaining_quantity INT NOT NULL,
  conditions TEXT,
  image VARCHAR(255),
  theme_id INT NOT NULL,
  diet_id INT NOT NULL,
  allergens TEXT,
  is_active TINYINT,
  created_at DATETIME,
  FOREIGN KEY (theme_id) REFERENCES themes (id),
  FOREIGN KEY (diet_id) REFERENCES diets (id)
);

INSERT INTO menus (id, title, description, starter, main_course, dessert, price, min_people, remaining_quantity, conditions, image, theme_id, diet_id, allergens) VALUES
(1, 'Menu Classic', 'Menu traditionnel...', 'Salade composée...', "Cotes d'agneau...", 'Crème brulée', 15.00, 10, 60, 'Commander 48h avant', 'classic_1.jpg', 1, 1, 'Gluten'),
(3, 'Menu Vegan', 'Menu vegan...', 'salade crudités...', 'steak vegan...', 'cheesecake', 19.00, 8, 41, '48h avant', 'vegan_1.jpg', 2, 3, 'Soja');



CREATE TABLE orders (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(50) NOT NULL,
  order_date DATETIME,
  order_status VARCHAR(50) NOT NULL,
  number_people INT NOT NULL,
  equipment_ready TINYINT,
  user_id INT NOT NULL,
  menu_id INT NOT NULL,
  delivery_address VARCHAR(255) NOT NULL,
  delivery_date DATE NOT NULL,
  delivery_time TIME NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  cancellation_reason TEXT,
  contact_method ENUM,
  is_modified_by_client TINYINT,
  FOREIGN KEY (user_id) REFERENCES users (id) ,
  FOREIGN KEY (menu_id) REFERENCES menus (id)
);

INSERT INTO orders (id, order_number, order_date, order_status, number_people, equipment_ready, user_id, menu_id, delivery_address, delivery_date, delivery_time, total_price, is_modified_by_client) VALUES
(18, 'ORD-6993D3A1D9024', '2026-04-16 11:34:09', 'finished', 8, 0, 14, 1, '5 rue de la viennoiserie (Bordeaux)', '2026-04-20', '12:00:00', 200.00, 0),
(19, 'ORD-6993D3D4A4E9A', '2026-04-18 16:35:00', 'finished', 20, 0, 14, 3, '5 rue de la viennoiserie (Bordeaux)', '2026-04-25', '18:00:00' ,500.00, 0);

CREATE TABLE reviews (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  rating INT NOT NULL ,
  comment TEXT,
  is_published TINYINT,
  created_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users (id) 
);

INSERT INTO reviews (id, order_id, user_id, rating, comment, is_published) VALUES
(6, 18, 14, 5, 'Super prestation merci !', 1),
(7, 19, 14, 4, 'Plats delicieux...', 1);
-- Creation de la base de donees
DROP DATABASE IF EXISTS garage_app;

CREATE DATABASE IF NOT EXISTS garage_app;

-- Utilizer la BD cree
USE garage_app;

-- Utilizateurs
DROP TABLE IF EXISTS garage_app.employees;

DROP TABLE IF EXISTS garage_app.users;

CREATE TABLE
  garage_app.users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM ('admin', 'employee') NOT NULL
  );

-- Horaire d'ouverture
DROP TABLE IF EXISTS garage_app.opening_hours;

CREATE TABLE
  garage_app.opening_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week INT NOT NULL,
    opening_time_am TIME NULL,
    closing_time_am TIME NULL,
    opening_time_pm TIME NULL,
    closing_time_pm TIME NULL,
    state ENUM ('ouvert', 'ferme') NOT NULL
  );

-- Voitures d'occasion
DROP TABLE IF EXISTS garage_app.used_cars;

CREATE TABLE
  used_cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    year YEAR NOT NULL,
    mileage INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  );

-- Images des voitures
DROP TABLE IF EXISTS garage_app.car_images;

CREATE TABLE
  car_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (car_id) REFERENCES used_cars (id) ON DELETE CASCADE
  );

-- Services
DROP TABLE IF EXISTS garage_app.services;

CREATE TABLE
  garage_app.services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL
  );

-- Evaluations
DROP TABLE IF EXISTS garage_app.testimonials;
CREATE TABLE
  garage_app.testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    rating INT NOT NULL,
    is_approved BOOLEAN NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  );

-- Messages
DROP TABLE if EXISTS garage_app.messages;
CREATE TABLE
  IF NOT EXISTS garage_app.messages (
    id int (11) NOT NULL AUTO_INCREMENT,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    phone_number varchar(255) NOT NULL,
    message text NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
  );
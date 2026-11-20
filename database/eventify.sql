-- Active: 1793901824921@@127.0.0.1@3306@eventify

-- ==========================================
-- EVENTIFY DATABASE
-- Tabellen worden aangemaakt in de juiste volgorde
-- ==========================================

-- 1. USERS
CREATE TABLE IF NOT EXISTS Users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (user_id),
    CONSTRAINT UQ_Users_username UNIQUE (username),
    CONSTRAINT UQ_Users_email UNIQUE (email)
) ENGINE=InnoDB;

-- 2. LOCATIONS
CREATE TABLE IF NOT EXISTS locations (
    location_id INT NOT NULL AUTO_INCREMENT,
    location_name VARCHAR(100) NULL,
    street VARCHAR(100) NULL,
    house_number VARCHAR(10) NULL,
    postal_code VARCHAR(10) NULL,
    city VARCHAR(50) NULL,
    country VARCHAR(50) NULL,
    capacity INT NULL,
    PRIMARY KEY (location_id)
) ENGINE=InnoDB;

-- 3. CATEGORIES
CREATE TABLE IF NOT EXISTS categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (category_id),
    CONSTRAINT UQ_categories_category_name UNIQUE (category_name)
) ENGINE=InnoDB;

-- 4. ROLES
CREATE TABLE IF NOT EXISTS roles (
    role_id INT NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (role_id),
    CONSTRAINT UQ_roles_role_name UNIQUE (role_name)
) ENGINE=InnoDB;

-- 5. ORGANIZERS
CREATE TABLE IF NOT EXISTS Organizers (
    organizer_id INT NOT NULL AUTO_INCREMENT COMMENT 'Unieke organisator-ID',
    user_id INT NOT NULL COMMENT 'Gekoppelde gebruikersaccount',
    company_name VARCHAR(100) NULL,
    description TEXT NULL,
    website VARCHAR(255) NULL,
    contact_email VARCHAR(100) NULL,
    verified BOOLEAN NULL,
    PRIMARY KEY (organizer_id),
    CONSTRAINT UQ_Organizers_user_id UNIQUE (user_id),
    CONSTRAINT FK_Users_TO_Organizers
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
) ENGINE=InnoDB;

-- 6. USER PROFILES
CREATE TABLE IF NOT EXISTS User_profiles (
    profile_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    date_of_birth DATE NULL,
    gender VARCHAR(20) NULL,
    street VARCHAR(100) NULL,
    house_number VARCHAR(10) NULL,
    postal_code VARCHAR(10) NULL,
    city VARCHAR(50) NULL,
    country VARCHAR(50) NULL,
    PRIMARY KEY (profile_id),
    CONSTRAINT UQ_User_profiles_user_id UNIQUE (user_id),
    CONSTRAINT FK_Users_TO_User_profiles
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
) ENGINE=InnoDB;

-- 7. USER ROLES
CREATE TABLE IF NOT EXISTS user_roles (
    user_roles_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_roles_id),
    UNIQUE INDEX uq_user_role (user_id, role_id),
    CONSTRAINT FK_Users_TO_user_roles
        FOREIGN KEY (user_id) REFERENCES Users(user_id),
    CONSTRAINT FK_roles_TO_user_roles
        FOREIGN KEY (role_id) REFERENCES roles(role_id)
) ENGINE=InnoDB;

-- 8. EVENTS
CREATE TABLE IF NOT EXISTS events (
    event_id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL,
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    status ENUM('draft','published','cancelled','completed') NULL,
    image_url VARCHAR(255) NULL,
    created_at DATETIME NULL,
    organizer_id INT NOT NULL COMMENT 'Unieke organisator-ID',
    location_id INT NOT NULL,
    PRIMARY KEY (event_id),
    CONSTRAINT FK_Organizers_TO_events
        FOREIGN KEY (organizer_id) REFERENCES Organizers(organizer_id),
    CONSTRAINT FK_locations_TO_events
        FOREIGN KEY (location_id) REFERENCES locations(location_id)
) ENGINE=InnoDB;

-- 9. EVENT CATEGORIES
CREATE TABLE IF NOT EXISTS event_categories (
    event_category_id INT NOT NULL AUTO_INCREMENT,
    event_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (event_category_id),
    UNIQUE INDEX uq_event_categories (event_id, category_id),
    CONSTRAINT FK_events_TO_event_categories
        FOREIGN KEY (event_id) REFERENCES events(event_id),
    CONSTRAINT FK_categories_TO_event_categories
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
) ENGINE=InnoDB;

-- 10. TICKETS
CREATE TABLE IF NOT EXISTS tickets (
    ticket_id INT NOT NULL AUTO_INCREMENT,
    ticket_name VARCHAR(100) NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity_total INT NOT NULL,
    quantity_sold INT NOT NULL,
    sales_start DATETIME NULL,
    sales_end DATETIME NULL,
    status ENUM('available','sold_out','inactive') NULL,
    event_id INT NOT NULL,
    PRIMARY KEY (ticket_id),
    CONSTRAINT FK_events_TO_tickets
        FOREIGN KEY (event_id) REFERENCES events(event_id)
) ENGINE=InnoDB;

-- 11. ORDERS
CREATE TABLE IF NOT EXISTS orders (
    order_id INT NOT NULL AUTO_INCREMENT,
    order_number VARCHAR(30) NULL,
    order_date DATETIME NULL,
    total_amount DECIMAL(10,2) NULL,
    status ENUM('pending','paid','cancelled','refunded') NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (order_id),
    CONSTRAINT UQ_orders_order_number UNIQUE (order_number),
    CONSTRAINT FK_Users_TO_orders
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
) ENGINE=InnoDB;

-- 12. ORDER ITEMS
CREATE TABLE IF NOT EXISTS order_items (
    order_items_id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    ticket_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_items_id),
    CONSTRAINT FK_tickets_TO_order_items
        FOREIGN KEY (ticket_id) REFERENCES tickets(ticket_id),
    CONSTRAINT FK_orders_TO_order_items
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
) ENGINE=InnoDB;

-- 13. PAYMENTS
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT NOT NULL AUTO_INCREMENT,
    transaction_id VARCHAR(100) NULL,
    payment_method ENUM('ideal','creditcard','paypal','bancontact') NULL,
    amount DECIMAL(10,2) NULL,
    order_id INT NULL,
    payment_status ENUM('pending','successful','failed','refunded') NULL,
    payment_date DATETIME NULL,
    PRIMARY KEY (payment_id),
    CONSTRAINT FK_orders_TO_payments
        FOREIGN KEY (order_id) REFERENCES orders(order_id)
) ENGINE=InnoDB;

-- 14. REVIEWS
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    rating TINYINT NULL,
    review_text TEXT NULL,
    created_at DATETIME NULL,
    status ENUM('published','hidden','reported') NULL,
    PRIMARY KEY (review_id),
    UNIQUE INDEX UQ_reviews_user_event (user_id, event_id),
    CONSTRAINT FK_Users_TO_reviews
        FOREIGN KEY (user_id) REFERENCES Users(user_id),
    CONSTRAINT FK_events_TO_reviews
        FOREIGN KEY (event_id) REFERENCES events(event_id)
) ENGINE=InnoDB;

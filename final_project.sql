CREATE DATABASE project_db;
USE project_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    discount DECIMAL(5,2) DEFAULT 0,
    category ENUM('men','women','kids','accessories') NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Cart table (links users with products)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Demo products
INSERT INTO products (name, description, price, discount, category, image) VALUES
('Jacket', 'Warm winter jacket', 42.99, 30, 'men', 'images/Home/1.jpg'),
('Black Denim Jeans', 'Comfortable fit', 62.99, 20, 'men', 'images/Home/2.jpg'),
('Summer Dress', 'Light and trendy', 66.99, 40, 'women', 'images/Home/3.jpg'),
('Casual Blazer', 'Perfect for office', 94.99, 15, 'men', 'images/Home/4.jpg'),

('Classic White T-Shirt', 'Everyday wear', 37.99, 20, 'men', 'images/men/1.png'),
('Black Denim Jeans', 'Stylish jeans', 49.99, 0, 'men', 'images/men/2.png'),
('Leather Jacket', 'Premium quality', 142.99, 30, 'men', 'images/men/3.png'),
('Casual Sneakers', 'Comfort shoes', 59.99, 0, 'men', 'images/men/4.png'),

('Cartoon Print T-Shirt', 'Fun kids tee', 24.99, 20, 'kids', 'images/kids/4.png'),
('Denim Jeans', 'Durable kids jeans', 29.99, 0, 'kids', 'images/kids/2.png'),
('Floral Summer Dress', 'Cute girls dress', 49.99, 30, 'kids', 'images/kids/3.png'),
('Light-up Sneakers', 'Kids favorite shoes', 39.99, 0, 'kids', 'images/kids/11.png');

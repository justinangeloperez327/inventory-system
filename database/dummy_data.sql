-- Use the inventory_system database
USE inventory_system;

-- Insert 10 dummy records into categories table
INSERT INTO categories (name, parent_id) VALUES
('Electronics', NULL),
('Laptops', 1),
('Smartphones', 1),
('Furniture', NULL),
('Chairs', 4),
('Tables', 4),
('Books', NULL),
('Fiction', 7),
('Non-fiction', 7),
('Clothing', NULL);

-- Insert 10 dummy records into items table
INSERT INTO items (category_id, name) VALUES
(2, 'MacBook Pro'),
(2, 'Dell XPS 13'),
(2, 'HP Spectre x360'),
(3, 'iPhone 12'),
(3, 'Samsung Galaxy S21'),
(3, 'Google Pixel 5'),
(5, 'Office Chair'),
(6, 'Dining Table'),
(8, 'Harry Potter Book'),
(9, 'Science Book');

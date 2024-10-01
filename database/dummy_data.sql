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

-- Insert 10 dummy records into borrowed_items table
INSERT INTO borrowed_items (user_id, item_id, expected_return_date, status) VALUES
(1, 1, '2024-01-10', 'borrowed'),
(2, 2, '2024-01-15', 'borrowed'),
(3, 3, '2024-01-18', 'borrowed'),
(4, 4, '2024-01-20', 'borrowed'),
(5, 5, '2024-01-25', 'borrowed'),
(6, 6, '2024-01-28', 'borrowed'),
(7, 7, '2024-01-30', 'borrowed'),
(8, 8, '2024-02-01', 'borrowed'),
(9, 9, '2024-02-05', 'borrowed'),
(10, 10, '2024-02-08', 'borrowed');

-- Insert 10 dummy records into returned_items table
INSERT INTO returned_items (borrowed_item_id, return_date, condition_notes) VALUES
(1, '2024-01-10', 'Returned in good condition'),
(2, '2024-01-15', 'Returned with minor scratches'),
(3, '2024-01-18', 'Returned with no issues'),
(4, '2024-01-20', 'Slightly damaged'),
(5, '2024-01-25', 'In working condition'),
(6, '2024-01-28', 'Returned late'),
(7, '2024-01-30', 'Returned with some wear'),
(8, '2024-02-01', 'No issues'),
(9, '2024-02-05', 'Good condition'),
(10, '2024-02-08', 'Returned in acceptable condition');
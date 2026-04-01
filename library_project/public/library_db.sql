-- ============================================
-- Library Management System Database
-- Database Name: library_db
-- ============================================

CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','librarian','student') DEFAULT 'student',
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: books
-- ============================================
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    category VARCHAR(60),
    publisher VARCHAR(120),
    year INT,
    quantity INT DEFAULT 1,
    available INT DEFAULT 1,
    description TEXT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: issued_books
-- ============================================
CREATE TABLE IF NOT EXISTS issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    issued_by INT NOT NULL,
    issued_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    fine DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('issued','returned','overdue') DEFAULT 'issued',
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: contacts
-- ============================================
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SAMPLE DATA: users
-- Passwords are MD5 hashed (admin123, lib123, stu123)
-- ============================================
INSERT INTO users (name, username, email, password, role, phone) VALUES
('Admin User',       'admin',      'admin@library.com',       MD5('admin123'),  'admin',     '9000000001'),
('Ravi Kumar',       'librarian1', 'ravi@library.com',        MD5('lib123'),    'librarian', '9000000002'),
('Sneha Sharma',     'student1',   'sneha@student.com',       MD5('stu123'),    'student',   '9111111101'),
('Arjun Mehta',      'student2',   'arjun@student.com',       MD5('stu123'),    'student',   '9111111102'),
('Priya Nair',       'student3',   'priya@student.com',       MD5('stu123'),    'student',   '9111111103'),
('Rahul Singh',      'student4',   'rahul@student.com',       MD5('stu123'),    'student',   '9111111104'),
('Divya Reddy',      'librarian2', 'divya@library.com',       MD5('lib123'),    'librarian', '9000000003');

-- ============================================
-- SAMPLE DATA: books
-- ============================================
INSERT INTO books (title, author, isbn, category, publisher, year, quantity, available, description) VALUES
('The Pragmatic Programmer',         'David Thomas, Andrew Hunt',  '978-0135957059', 'Technology',   'Addison-Wesley',        2019, 4, 4, 'A guide to software craftsmanship covering career advice, best practices and tools for software development.'),
('Clean Code',                       'Robert C. Martin',           '978-0132350884', 'Technology',   'Prentice Hall',         2008, 3, 3, 'A handbook of agile software craftsmanship to write better code with clarity and maintainability.'),
('Introduction to Algorithms',       'Cormen, Leiserson, Rivest',  '978-0262033848', 'Technology',   'MIT Press',             2009, 5, 5, 'Comprehensive textbook covering algorithms and data structures.'),
('The Great Gatsby',                 'F. Scott Fitzgerald',        '978-0743273565', 'Fiction',      'Scribner',              2004, 3, 3, 'A novel about the glittering and shallow life of the super-rich during the Roaring Twenties.'),
('To Kill a Mockingbird',            'Harper Lee',                 '978-0061935466', 'Fiction',      'HarperCollins',         2002, 2, 2, 'Pulitzer Prize-winning masterwork of honor and injustice in the deep South.'),
('Sapiens: A Brief History',         'Yuval Noah Harari',          '978-0062316097', 'History',      'Harper',                2015, 4, 4, 'A brief history of humankind from the Stone Age through the 21st century.'),
('Atomic Habits',                    'James Clear',                '978-0735211292', 'Self-Help',    'Avery',                 2018, 5, 5, 'A proven framework for improving every day through tiny changes in behavior.'),
('Database System Concepts',         'Silberschatz, Korth',        '978-0078022159', 'Technology',   'McGraw-Hill',           2019, 3, 3, 'Comprehensive introduction to database management systems.'),
('Wings of Fire',                    'A.P.J. Abdul Kalam',         '978-8173711466', 'Biography',    'Universities Press',    1999, 6, 6, 'Autobiography of A.P.J. Abdul Kalam, former President of India.'),
('Rich Dad Poor Dad',                'Robert T. Kiyosaki',         '978-1612680194', 'Finance',      'Plata Publishing',      2017, 4, 4, 'What the rich teach their kids about money that the poor and middle class do not.'),
('Computer Networks',                'Andrew Tanenbaum',           '978-0132126953', 'Technology',   'Pearson',               2010, 3, 3, 'Comprehensive introduction to modern computer networks and protocols.'),
('Python Crash Course',              'Eric Matthes',               '978-1593279288', 'Technology',   'No Starch Press',       2019, 4, 4, 'A hands-on, project-based introduction to programming in Python.');

-- ============================================
-- SAMPLE DATA: issued_books
-- ============================================
INSERT INTO issued_books (book_id, member_id, issued_by, issued_date, due_date, return_date, fine, status) VALUES
(1,  3, 2, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 10 DAY), INTERVAL 14 DAY), NULL, 0.00, 'issued'),
(3,  4, 2, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 20 DAY), INTERVAL 14 DAY), NULL, 30.00, 'overdue'),
(7,  5, 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY),  DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 5 DAY),  INTERVAL 14 DAY), NULL, 0.00, 'issued'),
(9,  3, 2, DATE_SUB(CURDATE(), INTERVAL 30 DAY), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 30 DAY), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 18 DAY), 0.00, 'returned'),
(5,  6, 1, DATE_SUB(CURDATE(), INTERVAL 18 DAY), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 18 DAY), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 3 DAY),  20.00, 'returned');

-- Update book availability after issues
UPDATE books SET available = available - 1 WHERE id IN (1, 3, 7);

-- ============================================
-- END OF SCRIPT
-- ============================================

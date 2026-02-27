-- Create a database named 'ronim_ambulance' (if not already created)
-- CREATE DATABASE IF NOT EXISTS ronim_ambulance;
-- USE ronim_ambulance;

-- Table structure for contact submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

USE bodaboda_sacco;

-- Default Admin (password: admin123)
INSERT INTO admins (username, password, role)
VALUES (
    'admin',
    '$2y$10$wH8F6kT6Q5Yd7YFZKkG1uOQqF1Z1nZl6vZp6K5lZ7z0z1vXJx7y6G',
    'super_admin'
);

-- Sample Member
INSERT INTO members (full_name, email, phone, membership_id, password)
VALUES (
    'John Rider',
    'john@example.com',
    '0712345678',
    'MEM001',
    '$2y$10$wH8F6kT6Q5Yd7YFZKkG1uOQqF1Z1nZl6vZp6K5lZ7z0z1vXJx7y6G'
);
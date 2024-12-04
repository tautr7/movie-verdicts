INSERT INTO users (email, password_hash, created_at, updated_at)
VALUES (:email, :password_hash, NOW(), NOW());
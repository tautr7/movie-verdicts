SELECT id,
  email,
  password_hash,
  created_at
FROM users
WHERE email = :email;
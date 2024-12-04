UPDATE users
SET password_hash = :password_hash,
  updated_at = NOW()
WHERE id = :user_id;
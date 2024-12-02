UPDATE movies
SET title = :title,
  overview = :overview,
  release_date = :release_date,
  poster_path = :poster_path,
  updated_at = NOW()
WHERE tmdb_id = :tmdb_id;
SELECT tmdb_id,
  title,
  overview,
  release_date
FROM movies
WHERE tmdb_id = :tmdb_id;
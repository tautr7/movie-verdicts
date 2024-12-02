SELECT movies.tmdb_id,
  movies.title,
  movies.overview,
  movies.release_date,
  movies.poster_path
FROM movies
  JOIN movie_genres ON movies.tmdb_id = movie_genres.movie_id
  JOIN genres ON movie_genres.genre_id = genres.id
WHERE genres.name = :genre_name
ORDER BY movies.created_at DESC
LIMIT :limit OFFSET :offset;
INSERT INTO movies (
        tmdb_id,
        title,
        overview,
        release_date,
        poster_path,
        created_at,
        updated_at
    )
VALUES (
        :tmdb_id,
        :title,
        :overview,
        :release_date,
        :poster_path,
        NOW(),
        NOW()
    );
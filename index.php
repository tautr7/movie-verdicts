<?php
// index.php

require_once __DIR__ . '/bootstrap.php';

$page_title = 'Home';
$page_description = 'Welcome to Flix';
$page_keywords = 'flix, movies, tv shows, streaming';

include __DIR__ . '/includes/templates/header.php';

require_once 'src/Movie.php';

$movie = new Movie($pdo);
$genres = $movie->getAllGenres();
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : 'Animation';
$movies = $movie->getMoviesByGenre($selectedGenre, 12);
?>

<div class="container">
    <form method="GET" action="index.php" class="form-inline">
        <div class="form-group">
            <label for="genre">Select Genre:</label>
            <select name="genre" id="genre" class="form-control" onchange="this.form.submit()">
                <?php foreach ($genres as $genre): ?>
                    <option value="<?php echo htmlspecialchars($genre['name']); ?>" <?php echo $selectedGenre === $genre['name'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genre['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <div class="row mt-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <a href="movie.php?tmdb_id=<?php echo urlencode($movie['tmdb_id']); ?>"
                            style="color: inherit; text-decoration: none;">
                            <?php echo htmlspecialchars($movie['title']); ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <a href="movie.php?tmdb_id=<?php echo urlencode($movie['tmdb_id']); ?>">
                            <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                                class="img-responsive" style="width:100%"
                                alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        </a>
                    </div>
                    <div class="panel-footer"> <?php echo htmlspecialchars($movie['overview']); ?> </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include __DIR__ . '/includes/templates/footer.php';
?>
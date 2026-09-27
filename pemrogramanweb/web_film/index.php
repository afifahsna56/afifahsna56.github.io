<?php
function getMovies(string $endpoint){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0MjBhNTVhZjQ1MjBjNWE1NGJlMzJlY2Y1ZDlkNjQyNyIsIm5iZiI6MTc5MDQyODMzMC44NjgsInN1YiI6IjZhYjdjNGFhZTA5MDZmZTcwOGJlNjY3NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VkziNORO9sdIOt9tA6A5PsJm387kCB0E8tw7cpiSQQo',
            'Accept' => 'Application/json'
        ]
    ]);

    $response = curl_exec($curl);
    $results = json_decode($response, true)["results"];
    return $results;
}

if(isset($_GET["search"])){
    $judul_film = urlencode($_GET["search"]);
    $endpoint = "https://api.themoviedb.org/3/search/movie?query=$judul_film";
    $movies = getMovies($endpoint);
}else{
    $endpoint="https://api.themoviedb.org/3/movie/popular";
    $movies = getMovies($endpoint);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Film XI RPL</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #2b2b2b;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        form input[type="text"] {
            width: 100%;
            max-width: 420px;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        form input[type="text"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        form button {
            padding: 12px 24px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        form button:hover {
            background-color: #1d4ed8;
        }

        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
        }

        .movie-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .movie-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .poster {
            width: 100%;
            height: 320px;
            object-fit: cover;
            background-color: #e2e8f0;
            display: block;
        }

        .movie-details {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            margin-bottom: 10px;
            color: #64748b;
        }

        .rating {
            background-color: #fef3c7;
            color: #d97706;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .overview {
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body>

    <form action="" method="get">
        <input type="text" name="search" placeholder="Cari film....">
        <button type="submit">Cari</button>
    </form>

    <?php if(isset($movies)): ?>
        <div class="movies-grid">
            <?php foreach($movies as $movie): ?>
                <div class="movie-card">
                    <?php 
                        $poster = !empty($movie["poster_path"]) 
                            ? "https://image.tmdb.org/t/p/w500" . $movie["poster_path"] 
                            : "https://via.placeholder.com/500x750?text=No+Poster";
                    ?>
                    <img class="poster" src="<?= $poster; ?>" alt="<?= $movie["title"]; ?>">
                    
                    <div class="movie-details">
                        <h3 class="title"><?= $movie["title"]; ?></h3>
                        <div class="meta">
                            <span class="rating">★ <?= number_format($movie["vote_average"], 1); ?></span>
                            <span><?= $movie["release_date"] ?? '-'; ?></span>
                        </div>
                        <p class="overview"><?= $movie["overview"]; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>

</html>
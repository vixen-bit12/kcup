<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Menu</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #f4f1e6;
    margin: 0;
    padding: 20px;
  }

  .page-title {
    text-align: center;
    font-size: 2.5rem;
    color: #5a3e2b;
    margin-bottom: 10px;
  }

  /* Back button */
  .back-home {
    display: block;
    text-align: center;
    margin: 15px auto 30px auto;
  }

  .back-home a {
    display: inline-block;
    padding: 10px 20px;
    background: #2575fc;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
  }

  .back-home a:hover {
    background: #1a5dcc;
  }

  /* Big Tiles for Categories */
  .big-grid {
    display: grid;
    gap: 30px;
    grid-template-columns: 1fr;
    max-width: 900px;
    margin: 0 auto;
  }

  .big-tile {
    position: relative;
    height: 300px;
    border-radius: 1rem;
    background-size: cover;
    background-position: center;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: bold;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .big-tile::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.4);
    border-radius: 1rem;
  }

  .big-tile h2 {
    position: relative;
    z-index: 2;
  }

  .big-tile:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.4);
  }
</style>
<body>

  <h1 class="page-title">Explore Our Menu</h1>
  <div class="back-home">
    <a href="homepage.php">← Back to Homepage</a>
  </div>

  <div class="big-grid">
    <a href="drinks.php" class="big-tile" style="background-image: url('img/drinks.jpg');">
      <h2>Drinks</h2>
    </a>
    <a href="snacks.php" class="big-tile" style="background-image: url('img/snack.jpg');">
      <h2>Snacks</h2>
    </a>
    <a href="coffee.php" class="big-tile" style="background-image: url('img/coffee.jpg');">
      <h2>Coffee</h2>
    </a>
  </div>

</body>
</html>

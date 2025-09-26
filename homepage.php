<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Shop</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Arial', sans-serif;
      background: #fff;
      color: #333;
      line-height: 1.6;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,.1);
      position: sticky;
      top: 0;
      z-index: 1000; /* taasan para dropdown gumana */
    }
    .logo {
      width: 50px;
      height: auto;
    }
    .header h1 {
      flex: 1;
      text-align: center;
      font-size: 24px;
      color: #333;
    }

    /* Dropdown */
    .dropdown {
      position: relative;
      display: inline-block;
      z-index: 1500;
    }
    .dropbtn {
      background: #2575fc;
      color: #fff;
      padding: 8px 16px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background: #f1f1f1;
      min-width: 140px;
      box-shadow: 0 8px 16px rgba(0,0,0,.2);
      border-radius: 5px;
      z-index: 2000;
    }
    .dropdown-content a {
      color: #000;
      padding: 10px 14px;
      text-decoration: none;
      display: block;
      font-size: 15px;
    }
    .dropdown-content a:hover {
      background: #ddd;
    }
    .dropdown:hover .dropdown-content {
      display: block;
    }
    .dropbtn:hover {
      background: #1a5fd1;
    }

    /* Nav */
    .nav {
      display: flex;
      justify-content: center;
      gap: 30px;
      background: #fafafa;
      padding: 15px 0;
      border-bottom: 1px solid #ddd;
    }
    .nav a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
      font-size: 18px;
    }
    .nav a:hover {
      color: #2575fc;
    }

    /* Hero Section */
    .hero {
      position: relative;
      height: 80vh;
      background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding-left: 50px;
      color: #fff;
    }
    .overlay {
      position: absolute;
      top: 0; left: 0;
      height: 100%; width: 100%;
      background: rgba(0,0,0,.4);
      z-index: 1;
    }
    .intro-text {
      position: relative;
      z-index: 2;
      animation: fadeIn 2s ease-in-out;
    }
    .intro-text h1 {
      font-size: 48px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .intro-text h3 {
      font-size: 20px;
      margin-bottom: 20px;
    }
    .btn {
      background: #2575fc;
      padding: 12px 25px;
      border-radius: 5px;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }
    .btn:hover {
      background: #1a5fd1;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px);}
      to { opacity: 1; transform: translateY(0);}
    }

    /* Products */
    .products {
      padding: 60px 30px;
      text-align: center;
      background: #f9f9f9;
    }
    .products h2 {
      font-size: 32px;
      margin-bottom: 40px;
    }
    .product-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }
    .product {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 20px;
      transition: transform 0.3s ease;
    }
    .product:hover {
      transform: translateY(-5px);
    }
    .product img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .product h3 {
      color: #2575fc;
      margin-bottom: 10px;
    }

    /* Entertainment / Promo */
    .promo {
      padding: 60px 30px;
      background: linear-gradient(135deg, #ffb347, #ffcc33);
      text-align: center;
      color: #222;
    }
    .promo h2 {
      font-size: 30px;
      margin-bottom: 20px;
    }
    .promo p {
      font-size: 18px;
      max-width: 700px;
      margin: 0 auto 20px;
    }

    /* FAQ */
    .faq {
      padding: 60px 30px;
      background: #fff;
    }
    .faq h2 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 30px;
    }
    .faq-item {
      max-width: 800px;
      margin: 10px auto;
      border-bottom: 1px solid #ddd;
    }
    .faq-question {
      padding: 15px;
      font-weight: bold;
      cursor: pointer;
      position: relative;
    }
    .faq-question::after {
      content: '+';
      position: absolute;
      right: 20px;
    }
    .faq-question.active::after {
      content: '-';
    }
    .faq-answer {
      display: none;
      padding: 0 15px 15px;
      color: #555;
    }

    /* Footer */
    footer {
      background: #222;
      color: #fff;
      text-align: center;
      padding: 30px 20px;
    }
    .social {
      margin: 15px 0;
    }
    .social a {
      margin: 0 10px;
      display: inline-block;
      color: #fff;
      text-decoration: none;
      font-size: 20px;
      transition: 0.3s;
    }
    .social a:hover {
      color: #2575fc;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <img src="img/logo.jpg" alt="Logo" class="logo">
    <h1>K-Cup Coffee Shop</h1>
    <div class="dropdown">
      <button class="dropbtn">Account</button>
      <div class="dropdown-content">
        <a href="login.php">Login</a>
        <a href="signup.php">Register</a>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <div class="nav">
    <a href="#">Home</a>
    <a href="product.php">Menu</a>
    <a href="#faq">FAQ</a>
  </div>

  <!-- Hero -->
  <div class="hero">
    <div class="overlay"></div>
    <div class="intro-text">
      <h1>Fresh Coffee Everyday</h1>
      <h3>Your daily dose of energy and warmth</h3>
      <a href="#products" class="btn">Order Now</a>
    </div>
  </div>

  <!-- Products -->
  <section class="products" id="products">
    <h2>Our Best Sellers</h2>
    <div class="product-list">
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,latte" alt="Latte">
        <h3>Latte</h3>
        <p>Creamy and smooth espresso with milk.</p>
      </div>
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,espresso" alt="Espresso">
        <h3>Espresso</h3>
        <p>Strong and rich flavor for true coffee lovers.</p>
      </div>
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,cappuccino" alt="Cappuccino">
        <h3>Cappuccino</h3>
        <p>Perfect mix of espresso, steamed milk, and foam.</p>
      </div>
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,mocha" alt="Mocha">
        <h3>Mocha</h3>
        <p>Chocolate and espresso blend for sweet lovers.</p>
      </div>
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,americano" alt="Americano">
        <h3>Americano</h3>
        <p>Espresso diluted with hot water, simple and bold.</p>
      </div>
      <div class="product">
        <img src="https://source.unsplash.com/300x200/?coffee,iced" alt="Iced Coffee">
        <h3>Iced Coffee</h3>
        <p>Chilled and refreshing, perfect for summer days.</p>
      </div>
    </div>
  </section>

  <!-- Entertainment / Promo -->
  <section class="promo">
    <h2>Did You Know?</h2>
    <p>Coffee is the second most traded commodity in the world after crude oil.  
    And guess what? Drinking 2–3 cups a day may actually help you live longer!</p>
  </section>

  <!-- FAQ -->
  <section class="faq" id="faq">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-item">
      <div class="faq-question">Do you offer delivery?</div>
      <div class="faq-answer">Yes, we deliver within the city. Delivery fees may apply.</div>
    </div>
    <div class="faq-item">
      <div class="faq-question">Can I customize my coffee?</div>
      <div class="faq-answer">Absolutely! You can adjust sugar, milk, and flavors.</div>
    </div>
    <div class="faq-item">
      <div class="faq-question">What are your opening hours?</div>
      <div class="faq-answer">We’re open daily from 7 AM to 10 PM.</div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Coffee Shop. All rights reserved.</p>
    <div class="social">
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">Twitter</a>
    </div>
  </footer>

  <script>
    // FAQ accordion
    const questions = document.querySelectorAll('.faq-question');
    questions.forEach(q => {
      q.addEventListener('click', () => {
        q.classList.toggle('active');
        const answer = q.nextElementSibling;
        answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
      });
    });
  </script>
</body>
</html>

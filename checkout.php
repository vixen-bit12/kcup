<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout</title>
  <link rel="stylesheet" href="css/checkout.css" />
</head>

<body style="background:url('bg.home.jpg') no-repeat center center fixed; background-size:cover;">

  <div class="container">
    <h1>Checkout</h1>

    <div class="order-summary" id="order-summary"></div>

    <a href="cart.php" class="back-btn">← Back to Cart</a>

    
    <button id="confirmCheckoutBtn" style="float:right;">✔ Confirm & Checkout</button>
  </div>

  <script>
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const summaryContainer = document.getElementById('order-summary');

    let total = 0;
    cart.forEach(item => {
      const quantity = item.quantity || 1;
      const subtotal = item.price * quantity;
      total += subtotal;

      const itemDiv = document.createElement('div');
      itemDiv.className = 'item';
      itemDiv.innerHTML = `
        <div class="item-name">${item.name}</div>
        <div>Quantity: ${quantity}</div>
        <div>Price: ₱ ${item.price}</div>
        <div>Subtotal: ₱ ${subtotal}</div>
      `;
      summaryContainer.appendChild(itemDiv);
    });

    const totalDiv = document.createElement('div');
    totalDiv.className = 'total';
    totalDiv.textContent = `Total Amount: ₱ ${total}`;
    summaryContainer.appendChild(totalDiv);

   
    document.getElementById('confirmCheckoutBtn').addEventListener('click', () => {
      if(cart.length === 0){
        alert('Your cart is empty.');
        return;
      }
      alert('Thank you for ordering!');
      localStorage.removeItem('cart'); 
      window.location.href = 'homepage.php'; 
    });
  </script>

</body>
</html>

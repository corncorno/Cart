<?php
include("connectdb.php");
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Add product with quantity 1
    } else {
        $_SESSION['cart'][$product_id]++; // Increment quantity if already in cart
    }

    echo "<script>alert('Product added to cart!');</script>";
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beer Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Shop!</h1>
        <a href="cart.php" class="cart-icon">
            <img src="images/cart-icon.png" alt="View Cart" style="width: 75px; height: 40px;">
        </a>
        <a href="?logout=true">Logout</a>
    </header>
    <main>
        <section class="products">
            <h2>Our Products</h2>
            <div class="product-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>₱<?php echo number_format($row['price'], 2); ?></p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Beer Shop. All rights reserved.</p>
    </footer>
</body>
</html>
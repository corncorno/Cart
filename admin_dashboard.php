<?php
include("connectdb.php");
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy(); 
    header("Location: login.php");
    exit();
}

// CRUD for Accounts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $role = $_POST['role'];
        $sql = "UPDATE users SET username = '$username', role = '$role' WHERE id = $id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM users WHERE id = $id";
        $conn->query($sql);
    }
}

// Products ADDITION
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "INSERT INTO products (name, price, description) VALUES ('$name', '$price', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Select all users
$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard!</h1>
        <a href="?logout=true">Logout</a> <!-- Logout button -->
    </header>
    <main>
        <p>Here you can manage users, view reports, and more.</p>

        
        <h2>Create User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="create">Create</button>
        </form>

        
        <h2>Manage Users</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <!-- Update Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
                            <select name="role">
                                <option value="customer" <?php echo $row['role'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin" <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <button type="submit" name="update">Update</button>
                        </form>
                        <!-- Delete Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Product Form -->
        <h2>Add Product</h2>
        <form method="POST">
            <div>
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </main>
</body>
</html>

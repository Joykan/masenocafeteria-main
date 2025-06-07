<?php
include 'db_connect.php';

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Handle optional profile image upload
    if ($_FILES['profile_image']['name']) {
        $image = $_FILES['profile_image']['name'];
        $imageTmp = $_FILES['profile_image']['tmp_name'];
        move_uploaded_file($imageTmp, "images/" . $image);
    } else {
        // Keep existing image
        $imageRes = mysqli_query($conn, "SELECT profile_image FROM users WHERE id=$id");
        $imageData = mysqli_fetch_assoc($imageRes);
        $image = $imageData['profile_image'];
    }

    // Update database
    $sql = "UPDATE users 
            SET username='$username', email='$email', role='$role', profile_image='$image' 
            WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: edit_user.php?success=1");
    exit;
}

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users");

// If editing a specific user
$editUser = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $editUser = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Users</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #28a745; color: white; }

        .form-container {
            background-color: white;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .btn-edit {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background: green;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>All Users</h2>

<?php if (isset($_GET['success'])): ?>
    <p class="success">User updated successfully!</p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php while ($user = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="images/<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><a class="btn-edit" href="edit_user.php?edit_id=<?= $user['id'] ?>#edit-form">Edit</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php if ($editUser): ?>
    <div class="form-container" id="edit-form">
        <h3>Edit User</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_id" value="<?= $editUser['id'] ?>">

            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($editUser['username']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($editUser['email']) ?>" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin" <?= $editUser['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="customer" <?= $editUser['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
            </select>

            <label>Current Profile Image:</label><br>
            <?php if (!empty($editUser['profile_image'])): ?>
                <img src="images/<?= htmlspecialchars($editUser['profile_image']) ?>" alt="Profile"><br><br>
            <?php else: ?>
                <p>No current image.</p>
            <?php endif; ?>

            <label>Upload New Image (optional):</label>
            <input type="file" name="profile_image" accept="image/*">

            <button type="submit">Update</button>
        </form>
    </div>
<?php endif; ?>

<!-- Optional: Smooth scroll to form -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const formSection = document.getElementById('edit-form');
        if (window.location.hash === '#edit-form' && formSection) {
            formSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>

</body>
</html>

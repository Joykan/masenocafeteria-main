<?php
include 'db_connect.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Get profile image before deleting user
    $getUser = mysqli_query($conn, "SELECT profile_image FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($getUser);
    $image = $row['profile_image'];

    // Delete image from folder
    if (!empty($image) && file_exists("images/$image")) {
        unlink("images/$image");
    }

    // Delete user from DB
    $deleteQuery = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error deleting user.";
    }
}

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            text-align: center;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        img {
            max-width: 80px;
            max-height: 60px;
        }

        .btn-delete {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-delete:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<h2>Delete User</h2>

<?php if (!empty($message)) : ?>
    <p class="message"><?= $message ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Profile Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($user = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <?php if (!empty($user['profile_image'])) : ?>
                    <img src="images/<?= $user['profile_image'] ?>" alt="Profile">
                <?php else : ?>
                    No Image
                <?php endif; ?>
            </td>
            <td>
                <a href="delete_user.php?delete_id=<?= $user['id'] ?>" 
                   class="btn-delete"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                   Delete
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

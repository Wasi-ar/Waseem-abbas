<?php
include "conn.php";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM contact WHERE id = $delete_id";

    if ($conn->query($delete_query) === TRUE) {
        echo "<script>alert('Record deleted successfully!');</script>";
        echo "<script>window.location.href = 'display_contact.php';</script>";
    } else {
        echo "<script>alert('Failed to delete record: " . $conn->error . "');</script>";
    }
}

if (isset($_POST['update_record'])) {
    $update_id = intval($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $update_query = "UPDATE contact SET username = '$username', email = '$email', message = '$message' WHERE id = $update_id";
    if ($conn->query($update_query)) {
        echo "<script>alert('Record updated successfully');</script>";
        echo "<script>window.location.href = 'display_contact.php';</script>";
    } else {
        echo "<script>alert('Failed to update record');</script>";
    }
}

$sql = "SELECT id, username, email, message FROM contact ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Data</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .form-container {
            margin: 20px 0;
        }

        /* delete icon */
        .delete-button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease, opacity 0.3s ease;
        }

        .delete-button i {
            margin-right: 8px;
        }

        .delete-button:hover {
            background-color: #e03e3e;
            opacity: 0.8;
        }

        /* update icon */
        .update-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease, opacity 0.3s ease;
        }

        .update-button i {
            margin-right: 8px;
        }

        .update-button:hover {
            background-color: #45a049;
            opacity: 0.8;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h3 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-container label {
            font-size: 16px;
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            background-color: #fff;
            box-sizing: border-box;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus,
        .form-container textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        
    </style>
    
</head>

<body>

    <h1>Contact Form Data with CRUD Operations</h1>

    <!-- Table to Display Records -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
                    echo "<td>
                            <a href='display_contact.php?delete_id=" . $row["id"] . "' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this record?\")'>
                            <i class='fas fa-trash-alt'></i> Delete</a> |
                            <a href='?edit_id=" . $row["id"] . "' class='update-button'>
                            <i class='fas fa-edit'></i> Edit</a>
                            <a href='?edit_id=" . $row["id"] . "' class='update-button'>
                            </i> Edit</a>
                            
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Form to Update Record -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        $edit_query = "SELECT * FROM contact WHERE id = $edit_id";
        $edit_result = $conn->query($edit_query);
        if ($edit_result->num_rows > 0) {
            $edit_row = $edit_result->fetch_assoc();
            ?>
            <div class="form-container">
                <h3>Edit Record</h3>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
                    <label>Name:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($edit_row['username']); ?>"
                        required><br><br>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($edit_row['email']); ?>"
                        required><br><br>
                    <label>Message:</label>
                    <textarea name="message" rows="4"
                        required><?php echo htmlspecialchars($edit_row['message']); ?></textarea><br><br>
                    <button type="submit" name="update_record">Update</button>
                </form>
            </div>
            <?php
        }
    }
    ?>
</body>
</html>
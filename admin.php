<!-- in developing phase -->


<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db1"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_employee"])) {
        // Add Employee functionality
        $name = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        // $photo = $_FILES["photo"]["name"];
        $mobile_number = $_POST["mobileno"];

        // Upload photo
        // $target_dir = "uploads/";
        // $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        // move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

        // Insert employee into database
        $sql = "INSERT INTO employees (username, email, password, mobile_number) VALUES ('$username', '$email', '$password', '$mobileno')";
        if ($conn->query($sql) === TRUE) {
            echo "New employee added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST["edit_employee"])) {
        // Edit Employee functionality
        // $employee_id = $_POST["employee_id"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $mobileno = $_POST["mobile_no"];

        // Update employee in database
        $sql = "UPDATE employees SET username='$username', email='$email', password='$password', mobileno='$mobileno' WHERE email=$email";
        if ($conn->query($sql) === TRUE) {
            echo "Employee updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST["delete_employee"])) {
        // Delete Employee functionality
        $email = $_POST["email"];

        // Delete employee from database
        $sql = "DELETE FROM employees WHERE email=$email";
        if ($conn->query($sql) === TRUE) {
            echo "Employee deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// List of Employees functionality
$sql = "SELECT * FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Welcome Dear</h1>

    <!-- Add Employee Form -->
    <h2>Add Employee</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        
        <!-- <label>Photo:</label>
        <input type="file" name="photo" required><br><br> -->
        
        <label>Mobile Number:</label>
        <input type="text" name="mobile_number" required><br><br>
        
        <input type="submit" name="add_employee" value="Add Employee">
    </form>

    <!-- List of Employees -->
    <h2>List of Employees</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Photo</th>
            <th>Mobile Number</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['password']; ?></td>
                <td><img src="uploads/<?php echo $row['photo']; ?>" alt="Employee Photo" height="50"></td>
                <td><?php echo $row['mobile_number']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="edit_employee" value="Edit">
                        <input type="submit" name="delete_employee" value="Delete" onclick="return confirm('Are you sure you want to delete this employee?');">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Close database connection
$stmt->close();
$conn->close();
?>

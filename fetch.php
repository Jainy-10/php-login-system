<?php

// Backend to fetch and display all the data from the
// database.
/*
require_once 'config.php';

// SQl query to fetch all users , we assign that query
// to variable $sql.
// The query (SELECT * FROM users) does:
// SELECT - The command to fetch data from the database.
// *  Means "all columns"
// FROM users   Specifies the table to get data from users 
// table in the database.  
// In short, This query:
// Goes to your database then Finds the "users" table then
// Retrieves ALL columns then Returns ALL rows from that table.
// This query will end up returning all data.
$sql = "SELECT * FROM users";

// Run the query, this mysqli_query($conn, $sql) sends
// our query (SELECT * FROM users) to MySQL and returns
// a result object.
$result = mysqli_query($conn, $sql);

// mysqli_num_rows($result) tells you how many rows the $result has.
if(mysqli_num_rows($result) > 0){
    echo "<h2>User list</h2><br>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th></tr>";

    // mysqli_fetch_assoc($result) fetches one row at a time
    // as an associative array. So $row['name'] gives you 
    // the user's name from that row.
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<tr>";
    }
    echo "</table>";
} else {
    echo "No users found";
}
*/


// Backend to fetch and display all the data in the database
// require_once 'config.php';

// $sql = "SELECT * FROM users";

// $result = mysqli_query($conn, $sql);

// if(mysqli_num_rows($result) > 0){
//     echo "<table border='1' cellpadding='15'>";
//     echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th></tr>";
//     while($row = mysqli_fetch_assoc($result)){
//         echo "<tr>";
//         echo "<td>" . htmlspecailchars($row['id']) . "</td>";
//         echo "<td>" . htmlspecialchars($row['name']) . "</td>";
//         echo "<td>" . htmlspecialchars($row['email']) . "</td>";
//         echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
//         echo "<tr>";
//     }
//     echo "</table>";

// }




// Backend to fetch and dsiplay all data from the 
// database and edit the data.

require_once 'config.php';

// This is a SQL query to select all users and
// ORDER BY id DESC shows newest users first.




$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>User List</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        // Here below we defined the edit and delete option at the end of
        // each row and link it to edit.php with ?id="$row['id']"
        // so the id of respective row is sent to edit.php or and delete.php 
        // file thruogh GET method or thrrough the link.  
        echo "<td>
            <a href='edit.php?id=" . $row["id"] . "'>Edit</a> | 
            <a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
            </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No users found.";
}

if (isset($_GET['message'])) {
    echo "<p  style='color: green;' >" . htmlspecialchars($_GET['message']) . "</p>";
}

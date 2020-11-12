<?php

$conn = mysqli_connect("127.0.0.1", "root", "", "Xmastree");
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// echo "Success: A proper connection to MySQL was made! ";
session_start();

// get the user id
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user_id = $_SESSION["id"];
}
// get the current time
$created_at = date('Y-m-d H:i:s', time());

// initialize and save a new order with user id
$query = "INSERT INTO orders (user_id, created_at) VALUES ('$user_id','$created_at')";
$result = mysqli_query($conn, $query);
$order_id = mysqli_insert_id($conn);
// get the cart items

if (isset($_SESSION['cart'])) {
    // loop through cart items
    foreach ($_SESSION['cart'] as $key => $value) {
        // get the quantity
        $quantity = $value['p_quantity'];
        // get the product id
        $product_id = $value['p_id'];
        // loop through item quantity
        for ($i = 0; $i < $quantity; $i++) {
            // initialize and save a new order item with order id and product id

            $query = "INSERT INTO order_items (order_id, product_id) VALUES ('$order_id','$product_id')";
            $result = mysqli_query($conn, $query);
        }

        // $outputTable .= "<tr><td>" . $value['p_id'] . "</td><td>" . $value['p_name'] . "</td><td>" . ($value['p_price'] * $value['p_quantity']) . "</td><td>" . $value['p_quantity'] . "</td><td><button id=" . $value['p_id'] . " class='btn btn-danger delete'>Delete</button></td></tr>";
        // $total = $total + ($value['p_price'] * $value['p_quantity']);
    }
}


// send a success message
$success = "<div>Success!</div>";
echo json_encode($success);

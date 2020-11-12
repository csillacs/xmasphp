<?php


$conn = mysqli_connect("127.0.0.1", "root", "", "Xmastree");
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// echo "Success: A proper connection to MySQL was made! ";
// echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "<br>";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Rent a Christmas tree | Care Instructions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie-edge">
    <link rel="stylesheet" href="styles/styles.css" />

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</head>

<body>
    <header class="main-header">
        <nav class="main-nav nav">
            <ul>
                <li><a href="index.html">HOME</a></li>
                <li><a href="selection.html">SELECTION</a></li>
                <li><a href="careinstructions.html">CARE INSTRUCTIONS</a></li>
                <li><a href="register.php">RENT A TREE</a></li>
                <li><a href="contact.html">CONTACT</a></li>
            </ul>
        </nav>
        <h1 class="shop-name shop-name-large"> Rent a Christmas Tree </h1>
    </header>

    <div class="page-header">
        <h1><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, welcome to our store.</h1>
    </div>
    <fieldset class=links>

        <p>
            <a href="reset_password.php" class="btn btn-primary">Reset Your Password</a>
            <br>
            <br>
            <a href="logout.php" class="btn btn-primary">Sign Out of Your Account</a>
        </p>
    </fieldset>
    </div>

    <section class="container content-section">
        <h2 class="section-header">Selection</h2>
        <div class="shop-items">
            <style>
                img {
                    width: 100% !important;
                    height: 100px !important;
                    object-fit: contain
                }

                h3 {
                    text-align: center;
                    white-space: nowrap
                }

                h6 {
                    text-align: center
                }
            </style>
            </head>





            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">

                                <?php

                                $sql = "SELECT * FROM products";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // echo $row['id'] ." ". $row['name'] ." ". $row['image'] ." ". $row['price']."<br>";
                                ?>
                                    <div class="col-md-3 text-center mt-5">
                                        <img src="images/<?php echo $row['image'] ?>" alt="">
                                        <h3><?php echo $row['name'] ?></h3>
                                        <h6>Price: <?php echo $row['price'] ?></h6>
                                        <div class="form-group">
                                            <label>Select list:</label>
                                            <select class="form-control" id="quantity<?php echo $row['id'] ?>">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                            <input type="hidden" id="name<?php echo $row['id'] ?>" value='<?php echo $row['name'] ?>'>
                                            <input type="hidden" id="price<?php echo $row['id'] ?>" value='<?php echo $row['price'] ?>'>
                                            <button class='btn btn-primary add' data-id="<?php echo $row['id'] ?>">Add to Cart</button>
                                        </div>
                                    </div>

                                <?php
                                }

                                ?>

                            </div>
                        </div>
                        <div class="col-md-1">

                        </div>
                        <div class="col-md-4">
                            <h3 class='text-center'> Checkout</h3>
                            <div id="displayCheckout">
                                <?php
                                if (!empty($_SESSION['cart'])) {
                                    $outputTable = '';
                                    $total = 0;
                                    $outputTable .= "<table id='cartTable' class='table table-bordered'><thead><tr><td>ID</td><td>Name</td><td>Price</td><td>Quantity</td><td>Action</td> </tr></thead><tbody>";
                                    foreach ($_SESSION['cart'] as $key => $value) {
                                        $outputTable .= "<tr><td>" . $value['p_id'] . "</td><td>" . $value['p_name'] . "</td><td>" . ($value['p_price'] * $value['p_quantity']) . "</td><td>" . $value['p_quantity'] . "</td><td><button id=" . $value['p_id'] . " class='btn btn-danger delete'>Delete</button></td></tr>";
                                        $total = $total + ($value['p_price'] * $value['p_quantity']);
                                    }
                                    $outputTable .= "</tbody></table>";
                                    $outputTable .= "<div class='text-center'><b>Total: " . $total . "</b></div>";


                                    echo $outputTable;
                                }
                                ?>



                            </div>
                            <button id='checkoutButton' class='btn btn-primary checkout'>Purchase</button>

                        </div>
                    </div>



                </div>


                <script>
                    $(document).ready(function() {
                        alldeleteBtn = document.querySelectorAll('.delete')
                        alldeleteBtn.forEach(onebyone => {
                            onebyone.addEventListener('click', deleteINsession)
                        })

                        function deleteINsession() {
                            removable_id = this.id;
                            $.ajax({
                                url: 'cart.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_to_remove: removable_id,
                                    action: 'remove'
                                },
                                success: function(data) {
                                    $('#displayCheckout').html(data);
                                    alldeleteBtn = document.querySelectorAll('.delete')
                                    alldeleteBtn.forEach(onebyone => {
                                        onebyone.addEventListener('click', deleteINsession)
                                    })
                                }
                            }).fail(function(xhr, textStatus, errorThrown) {
                                alert(xhr.responseText);
                            });

                        }


                        $('.add').click(function() {
                            id = $(this).data('id');
                            name = $('#name' + id).val();
                            price = $('#price' + id).val();
                            quantity = $('#quantity' + id).val();
                            $.ajax({
                                url: 'cart.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    cart_id: id,
                                    cart_name: name,
                                    cart_price: price,
                                    cart_quantity: quantity,
                                    action: 'add'
                                },
                                success: function(data) {
                                    $('#displayCheckout').html(data);
                                    alldeleteBtn = document.querySelectorAll('.delete')
                                    alldeleteBtn.forEach(onebyone => {
                                        onebyone.addEventListener('click', deleteINsession)
                                    })
                                }
                            }).fail(function(xhr, textStatus, errorThrown) {
                                alert(xhr.responseText);
                            });

                        })
                        // $('.checkout').click(function() {
                        //     // find the cart table

                        //     var cartRows = $('#cartTable tbody tr')
                        //     var checkoutArr = [];

                        //     // loop through each cart table row
                        //     cartRows.each(function() {
                        //         var productId
                        //         var productQuantity
                        //         // for each row, 
                        //         // find the product id
                        //         // find the product quantity
                        //         $(this).find('td').each(function(index) {
                        //             if (index === 0) {
                        //                 productId = $(this).text()
                        //             }
                        //             if (index === 3) {
                        //                 productQuantity = $(this).text()
                        //             }
                        //         })
                        //         // add the product id and quantity to checkout array
                        //         var productOrder = {
                        //             id: productId,
                        //             quantity: productQuantity
                        //         }
                        //         checkoutArr.push(productOrder);

                        //     })

                        //     // send checkout array with ajax to checkout.php
                        //     $.ajax({
                        //         url: 'checkout.php',
                        //         method: 'POST',
                        //         dataType: 'json',
                        //         data: checkoutArr,
                        //         success: function() {
                        //             alert("Purchase succesful");
                        //         }
                        //     }).fail(function(xhr, textStatus, errorThrown) {
                        //         alert(xhr.responseText);
                        //     })
                        // })
                    })
                </script>




                <?php


                mysqli_close($conn);

                ?>
            </body>

            <footer>
                @Rent a Christmas tree tel: +44 (0)1455 840 600 email: hello@loveachristmastree.co.uk
            </footer>


</html>
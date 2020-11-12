<!DOCTYPE html>
<html>

<head>
    <title>Rent a Christmas tree | Rent a Christmas tree</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie-edge">
    <link rel="stylesheet" href="styles/styles.css" />
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
    <div class="content-section container">

        <h1>In order to reserve the Christmas tree of your dreams, please register your personal account</h1>

        <?php

        $link = mysqli_connect("127.0.0.1", "root", "", "Xmastree");
        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        // echo "Success: A proper connection to MySQL was made! ";
        // echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "<br>";

        // Define variables and initialize with empty values
        $username = $password = $confirm_password = $email = $address = "";
        $username_err = $password_err = $confirm_password_err = $email_err = $address_err =  "";

        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Validate email
            if (empty(trim($_POST["email"]))) {
                $email_err = "Please enter a valid email address.";
            } else {
                // Prepare a select statement
                $sql = "SELECT id FROM users WHERE email = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_email);

                    // Set parameters
                    $param_email = trim($_POST["email"]);

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        /* store result */
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            $email_err = "This email address is already taken.";
                        } else {
                            $email = trim($_POST["email"]);
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
                // Validate username
                if (empty(trim($_POST["username"]))) {
                    $username_err = "Please enter a username.";
                } else {
                    // Prepare a select statement
                    $sql = "SELECT id FROM users WHERE username = ?";

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "s", $param_username);

                        // Set parameters
                        $param_username = trim($_POST["username"]);

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            /* store result */
                            mysqli_stmt_store_result($stmt);

                            if (mysqli_stmt_num_rows($stmt) == 1) {
                                $username_err = "This username is already taken.";
                            } else {
                                $username = trim($_POST["username"]);
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                    }

                    // Validate address
                    if (empty(trim($_POST["address"]))) {
                        $address_err = "Please enter a valid address.";
                    } else {
                        // Prepare a select statement
                        $sql = "SELECT id FROM users WHERE address = ?";

                        if ($stmt = mysqli_prepare($link, $sql)) {
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "s", $param_address);

                            // Set parameters
                            $param_email = trim($_POST["address"]);

                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt)) {
                                /* store result */
                                mysqli_stmt_store_result($stmt);

                                if (mysqli_stmt_num_rows($stmt) == 1) {
                                    $address_err = "This email address is already taken.";
                                } else {
                                    $address = trim($_POST["address"]);
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }

                // Validate password
                if (empty(trim($_POST["password"]))) {
                    $password_err = "Please enter a password.";
                } elseif (strlen(trim($_POST["password"])) < 6) {
                    $password_err = "Password must have atleast 6 characters.";
                } else {
                    $password = trim($_POST["password"]);
                }

                // Validate confirm password
                if (empty(trim($_POST["confirm_password"]))) {
                    $confirm_password_err = "Please confirm password.";
                } else {
                    $confirm_password = trim($_POST["confirm_password"]);
                    if (empty($password_err) && ($password != $confirm_password)) {
                        $confirm_password_err = "Password did not match.";
                    }
                }

                // Check input errors before inserting in database
                if (
                    empty($email_err) && empty($username_err) && empty($address_err) && empty($password_err) && empty($confirm_password_err)
                ) {

                    // Prepare an insert statement
                    $sql = "INSERT INTO users (email, username, address, password ) VALUES (?, ?, ?, ?)";




                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_username, $param_address, $param_password);

                        // Set parameters
                        $param_email = $email;
                        $param_username = $username;
                        $param_address = $address;

                        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Redirect to login page
                            header("location: login.php");
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }

                // Close connection
                mysqli_close($link);
            }
        }
        ?>





        <!--login and reg form-->

        <fieldset>
            <h1>Register now</h1>
            <p>Please fill this form to create an account.</p>
            <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                    <span class="help-block"><?php echo $address_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-default" value="Reset">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </fieldset>




    </div>
</body>
<footer>
    @Rent a Christmas tree tel: +44 (0)1455 840 600 email: hello@loveachristmastree.co.uk
</footer>

</html>
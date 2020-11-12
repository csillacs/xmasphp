<?php



$link = mysqli_connect("127.0.0.1", "root", "", "Xmastree");
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// echo "Success: A proper connection to MySQL was made! ";
// echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "<br>";


use PHPMailer\PHPMailer\PHPMailer;


if (isset($_POST['name']) && isset($_POST['email'])) {


    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();

    //smtp settings

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "omniakurssi@gmail.com";
    $mail->Password = "kurssi12345";
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";

    //email settings

    $mail->isHTML(true);
    $mail->setFrom($email, $name);
    $mail->addAddress("omniakurssi@gmail.com");
    $mail->Subject = ("$email, ($subject)");
    $mail->Body = $body;

    if ($mail->send()) {
        $status = "success";
        $response = "Email is sent!";
    } else {
        $status = "failed";
        $response = "Something is wrong: <br>" . $mail->ErrorInfo;
    }

    exit(json_encode(array("status" => $status, "response" => $response)));
}

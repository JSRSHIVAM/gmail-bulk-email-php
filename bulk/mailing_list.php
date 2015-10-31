<?php

error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

require '/lib/phpmailer/PHPMailerAutoload.php';
require '/config.php';

if ((isset($_POST['emailSubject']) && !empty($_POST['emailSubject'])) && (isset($_POST['emailText']) && !empty($_POST['emailText']))) {
$mail = new PHPMailer;

$subject = $_POST['emailSubject'];
$body    = $_POST['emailText'];

$mail->isSMTP();
$mail->Host = SMTPHOST;
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
$mail->Port = 25;
$mail->Username = SMTPUSERNAME;
$mail->Password = SMTPPASSWORD;
$mail->setFrom(SMTPUSERNAME, SENDERTITLE);
$mail->addReplyTo(SMTPUSERNAME, SENDERTITLE);

$mail->Subject = $subject;

//Same body for all messages, so set this before the sending loop
//If you generate a different body for each recipient (e.g. you're using a templating system),
//set it inside the loop
$mail->msgHTML($body);
//msgHTML also sets AltBody, but if you want a custom one, set it afterwards
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

//Connect to the database and select the recipients from your mailing list that have not yet been sent to
//You'll need to alter this to match your database
$mysql = mysqli_connect(DBHOST, DBUSER, DBPASSWORD);
mysqli_select_db($mysql, DBNAME);
$result = mysqli_query($mysql, 'SELECT full_name, email, photo FROM mailinglist WHERE sent = false');

foreach ($result as $row) { //This iterator syntax only works in PHP 5.4+
    $mail->addAddress($row['email'], $row['full_name']);
    if (!empty($row['photo'])) {
        $mail->addStringAttachment($row['photo'], 'YourPhoto.jpg'); //Assumes the image data is stored in the DB
    }

    if (!$mail->send()) {
        echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
        break; //Abandon sending
    } else {
        echo "Message sent to :" . $row['full_name'] . ' (' . str_replace("@", "&#64;", $row['email']) . ')<br />';
        //Mark it as sent in the DB
        mysqli_query(
            $mysql,
            "UPDATE mailinglist SET sent = true WHERE email = '" .
            mysqli_real_escape_string($mysql, $row['email']) . "'"
        );
    }
    // Clear all addresses and attachments for next loop
    $mail->clearAddresses();
    $mail->clearAttachments();
}
}else
	echo "please follow by link";

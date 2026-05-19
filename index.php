<?php

    session_start();

    require 'vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    include 'db_connector.php';
    $connection = openConnection();

    $success = '';
    $errors = [];

    $fname = $lname = $email = $subject = $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

        $fname = htmlspecialchars($fname, ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars($lname, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        if (empty($fname)) {
            $errors[] = "First name is required";
        }

        if (empty($lname)) {
            $errors[] = "Last name is required";
        }

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid";
        }

        if (empty($subject)) {
            $errors[] = "Subject is required";
        }

        if (empty($message)) {
            $errors[] = "Message is required";
        } 


        if (empty($errors)) {
            $stmt = $connection->prepare(
                "INSERT INTO contacts (fname, lname, email, subject, message)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $fname,
                $lname,
                $email,
                $subject,
                $message
            );

            $dbSuccess = $stmt->execute();
            $emailSuccess = false;

            if ($dbSuccess) {

                $mail = new PHPMailer(true);

                try {

                    $mail->isSMTP();
                    $mail->Host = $_ENV['smtp_host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['smtp_user'];
                    $mail->Password = $_ENV['smtp_pass'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = $_ENV['smtp_port'];

                    $mail->setFrom($_ENV['smtp_from'], 'Portfolio Contact Form');
                    $mail->addAddress($_ENV['smtp_to']);

                    $mail->isHTML(true);
                    $mail->Subject = "New Contact Form Submission: $subject";

                    $mail->Body = "
                        <h2>New Contact Form Submission</h2>
                        <p><strong>First Name:</strong> $fname</p>
                        <p><strong>Last Name:</strong> $lname</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong><br>$message</p>
                    ";

                    $mail->send();
                    $emailSuccess = true;

                } catch (Exception $e) {
                    $emailSuccess = false;
                }
            }

            if ($dbSuccess && $emailSuccess) {
                $_SESSION['success'] = "Message sent successfully!";
                header("Location: index.php#contact");
                exit;
                
                
            } elseif ($dbSuccess && !$emailSuccess) {
                $_SESSION['errors'] = ["Message saved but email failed to send."];
            } else {
                $_SESSION['errors'] = ["Something went wrong. Please try again."];
            }

            $stmt->close();
        }
    }

    closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/application.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>My Portfolio</title>
    <meta name="description" content="Portfolio website showcasing my web development projects, created using HTML, CSS, and JavaScript">
</head>
<body>
    <header>
        <div class="header-wrapper" id="top">
            <img src="Assets/header-bg.png" alt="Background image for header">

            <div class="header-text">
                <h1 id="typing-text"></h1>
                <h4>I'm a Web Developer</h4>

                <a href="#projects" class="scroll">Scroll Down 
                    <br> <i class="fa-solid fa-angle-down"></i>
                </a>
            </div>
        </div>
    </header>

    <?php include 'php/sidebar.php'; ?>

    <div class="page-content">
        <div class="projects" id="projects">
            <div class="project-item">
                <img src="Assets/netmatters-recreation.png" alt="Netmatters recreation home page">
                <h2>Netmatters Website Recreation</h2>
                <p>My attempt on recreating the Netmatters home page</p>
                <a href="https://github.com/TorinMk?tab=repositories" target="_blank">View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="project-item">
                <img src="Assets/JSArray.png" alt="Js Array Project Image">
                <h2>JS Array</h2>
                <p>A simple image generator to assign to a email</p>
                <a href="https://github.com/TorinMk/Image-Assigner" target="_blank">View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="project-item">
                <img src="Assets/Placeholder.png" alt="Placeholder">
                <h2>Project Three</h2>
                <p>Description</p>
                <a>View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="project-item">
                <img src="Assets/Placeholder.png" alt="Placeholder">
                <h2>Project Four</h2>
                <p>Description</p>
                <a>View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="project-item">
                <img src="Assets/Placeholder.png" alt="Placeholder">
                <h2>Project Five</h2>
                <p>Description</p>
                <a>View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="project-item">
                <img src="Assets/Placeholder.png" alt="Placeholder">
                <h2>Project Six</h2>
                <p>Description</p>
                <a>View Project <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="contact" id="contact">
            <div class="contact-items">
                <div class="contact-text">
                    <h2>Get In Touch</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>

                    <h2>00000 000000<br></h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>
                </div>
                <div class="contact-Form">
                    <form id="contactForm" method="POST" action="index.php">

                        <?php if (!empty($errors)): ?>
                            <div class="error-message">
                                <?php foreach ($errors as $err): ?>
                                    <p><?= $err ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="success-message">
                                <?= $_SESSION['success']; ?>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <div class="gap">
                            <input type="text" name="fname" placeholder="First Name *" value="<?= $fname ?>">
                            <input type="text"  name="lname" placeholder="Last Name *" value="<?= $lname ?>">
                        </div>
                        <input class="large" type="email" name="email" placeholder="Email Address *" value="<?= $email ?>">
                        <input class="large" type="text" name="subject"  placeholder="Subject *" value="<?= $subject ?>">
                        <textarea class="large message" name="message" placeholder="Message *"><?= $message ?></textarea>

                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="back-to-top">
            <a href="#top"> Back To Top <br> <i class="fa-solid fa-angle-up"></i> </a>
        </div>
    </div>
    <script src="js/sidebar.js"></script>
    <script src="js/typing.js"></script>
    
</body>
</html>

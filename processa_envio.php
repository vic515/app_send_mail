<?php

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

require './bibliotecas/PHPMailer/Exception.php';
require './bibliotecas/PHPMailer/OAuth.php';
require './bibliotecas/PHPMailer/PHPMailer.php';
require './bibliotecas/PHPMailer/POP3.php';
require './bibliotecas/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem {
    private $email = null;
    private $subject = null;
    private $mensagem = null;
    public $status = array('codigo_status' => null, 'descricao_status' => '');
    // function __construct($email, $subject, $mensagem) {
    //     $email = $_POST['email'];
    //     $subject = $_POST['subject'];
    //     $mensagem = $_POST['mensagem'];
    // }
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function mensagemValida() {
        if(empty($this->email) || empty($this->subject) || empty($this->mensagem)) {
            return false;
        } else {
            return true;
        }
    }
}

$msg = new Mensagem();

$msg->__set('email',$_POST['email']);
$msg->__set('subject',$_POST['subject']);
$msg->__set('mensagem',$_POST['mensagem']);
// $msg->__set('arquivo',$_POST['attachment']);
// echo '<pre>';
// print_r($msg);
// echo '</pre>';

if(!$msg->mensagemValida()) {
    echo 'Mensagem não é válida';
    header('Location: index.php?email=erro');
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'cursophp22d@gmail.com';                     // SMTP username
    $mail->Password   = '!Bolodecacau123';                               // SMTP password
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('cursophp22d@gmail.com', 'Curso PHP');
    $mail->addAddress($msg->__get('email'), 'Victor Lemos');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment($msg->__get('arquivo'));         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $msg->__get('subject');
    $mail->Body    = $msg->__get('mensagem');
    $mail->AltBody = $msg->__get('mensagem');

    $mail->send();
    $msg->status['codigo_status'] = 1;
    $msg->status['descricao_status'] = 'E-mail enviado com sucesso';
} catch (Exception $e) {
    $msg->status['codigo_status'] = 2;
    $msg->status['descricao_status'] = 'Não foi possível enviar o E-mail. Por favor tente novamente mais tarde. Detalhes do erro: '. $mail->ErrorInfo ;
}


?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocumApp ent</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 d-flex align-self-center">

                    <?php  if ($msg->status['codigo_status'] === 1) { ?>

                        <div class="container"><h1 class='display-4 text-success'>Sucesso!</h1>
                        <p><?= $msg->status['descricao_status']?></p>
                        <a href="index.php" class='btn btn-success btn-lg mt-5 text-white'>Voltar</a>
                        </div>

                    <?php } ?>


                    <?php  if ($msg->status['codigo_status'] === 2) { ?>

                        <div class="container"><h1 class='display-4 text-danger'>Ops!</h1>
                        <p><?= $msg->status['descricao_status']?></p>
                        <a href="index.php" class='btn btn-success btn-lg mt-5 text-white'>Voltar</a>
                        </div>

                    <?php } ?>

                </div>
            </div>
    </div>
    
</body>
</html>
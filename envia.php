<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Configurações do smtp
    $mail->SetLanguage('br');
    $mail->CharSet = "utf8";
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Debug
    $mail->isSMTP();                                           
    $mail->Host       = 'URL DE DISPARO';                   // >> Adicionar url smtp
    $mail->SMTPAuth   = true;                                   //  habilitar seguranca
    $mail->Username   = 'seu@dominio.com.br';              // >> SMTP seu email/usuario
    $mail->Password   = 'SUA SENHA';                       // >> SENHA SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 2525;                              // >> PORTA a definir conforme especificações do seu email

    //Email a definir
    $mail->setFrom($_POST['email']);         // Remetente
    $mail->addAddress('seu@dominio.com.br', 'Seu Nome');     // >> Destinario 
    $mail->addReplyTo($_POST['email'], $_POST['name']); 
    // $mail->addCC('cc@example.com');    // = adicionar copia 

    // Anexo (caso não nao utlize anexo remova toda essa parte)
    $arquivo = $_FILES["arquivo"]; // nome salvo no campo name - de arquivos
    // envia com o sem anexo
    if(isset($_FILES['arquivo']['tmp_name']) && $_FILES['arquivo']['tmp_name'] != "") {
        $mail->AddAttachment($_FILES['arquivo']['tmp_name'],
        $_FILES['arquivo']['name']);
      }
        
    // Informaçoes do conteudo
    $mail->isHTML(true);                           
    $mail->Subject = ($_POST['assunto']);        
      
    // Conteudo q ira aparecer no email
    $mail->Body .= " <b>Nome:</b> ".$_POST['name']."<br>"; 
    $mail->Body .= " <b>E-mail:</b> ".$_POST['email']."<br>"; 
    $mail->Body .= " <b>Assunto:</b> ".$_POST['assunto']."<br>";
    $mail->Body .= " <b>Selecionar:</b> ".$_POST['selecionar']."<br>"; 
    $mail->Body .= " <b>Radio:</b> ".$_POST['opcao']."<br>"; 
    $mail->Body .= " <b>Mensagem:</b> ".nl2br($_POST['message'])."<br>"; 


    // Recaptcha   ( caso nao deseje utilizar remova essa parte)
    require_once(dirname(__FILE__) . '/assets/recaptchalib.php');
    $secret = "SENHA"; // definir a chave secreta
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);}
    if ($response != null && $response->success) { 
    }
   
    // Ação apos clicar em enviar ( 2 opçoes abaixo - mensagem ou redirecionamento)
        $mail->send();
       // echo 'Mensagem enviada com sucesso'; // opçao de texto
       header('Location: msgenviada.html');
    } catch (Exception $e) {
        echo "Não foi possível enviar a mensagem. Erro: {$mail->ErrorInfo}";
    }





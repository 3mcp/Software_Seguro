<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; 

class EmailHelper
{
    public static function enviarEmailConsulta($email, $nomePaciente, $nomeMedico, $especialidade, $dataHora)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'natimolini.softwareseguro@gmail.com';       // substitua pelo seu email
            $mail->Password = 'mndbdytytbvprbbt';              // senha de aplicativo
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';


            $mail->setFrom('natimolini.softwareseguro@gmail.com', 'Clínica XYZ');
            $mail->addAddress($email, $nomePaciente);

            $mail->isHTML(true);
            $mail->Subject = 'Confirmação de Consulta';
            $mail->Body = "
                <p>Olá, <strong>{$nomePaciente}</strong>!</p>
                <p>Sua consulta foi agendada com sucesso com:</p>
                <ul>
                    <li><strong>Médico:</strong> {$nomeMedico}</li>
                    <li><strong>Especialidade:</strong> {$especialidade}</li>
                    <li><strong>Data e Hora:</strong> {$dataHora}</li>
                </ul>
                <p>Obrigado por escolher nossa clínica!</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $mail->ErrorInfo);
            return false;
        }
    }
}

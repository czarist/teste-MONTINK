<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public static function enviarConfirmacaoPedido(string $emailDestino, array $dadosPedido): void
    {
        $assunto = "Confirmação do seu Pedido #" . $dadosPedido['pedido_id'];

        $mensagem = "Oi,\n\n";
        $mensagem .= "Seu pedido foi finalizado com sucesso!\n\n";
        $mensagem .= "Resumo do Pedido:\n";
        $mensagem .= "Subtotal: R$ " . number_format($dadosPedido['subtotal'], 2, ',', '.') . "\n";
        $mensagem .= "Frete: R$ " . number_format($dadosPedido['frete'], 2, ',', '.') . "\n";
        if ($dadosPedido['desconto'] > 0) {
            $mensagem .= "Desconto ({$dadosPedido['percentual']}%): -R$ " . number_format($dadosPedido['desconto'], 2, ',', '.') . "\n";
        }
        $mensagem .= "Total: R$ " . number_format($dadosPedido['total'], 2, ',', '.') . "\n";

        if (!empty($dadosPedido['cupom'])) {
            $mensagem .= "\nCupom aplicado: {$dadosPedido['cupom']}\n";
        }

        $mensagem .= "\nEndereço de entrega:\n";
        $mensagem .= $dadosPedido['endereco'];

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_ENCRYPTION');
            $mail->Port = getenv('MAIL_PORT');

            $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ]
            ];
            $mail->addAddress($emailDestino);

            $mail->isHTML(false);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem;

            $mail->send();
        } catch (Exception $e) {
            $logPath = __DIR__ . '/../../logs/email_error.log';
            $logDir = dirname($logPath);

            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }

            file_put_contents($logPath, $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}

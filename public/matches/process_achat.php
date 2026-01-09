<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../../repositories/BilletRepository.php';
require_once '../../repositories/MatchRepository.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Acheteur') {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchId = (int)$_POST['match_id'];
    $userId = (int)$_SESSION['user_id'];
    $categorieId = isset($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : null; 
    $quantity = (int)$_POST['quantite'];
    $userEmail = $_SESSION['user_email'];

    if($quantity < 1 || $quantity > 4) $quantity = 1; 

    if(!$categorieId) {
        header('Location: acheter.php?id=' . $matchId . '&error=missing_category');
        exit();
    }

    try {
        $billetRepo = new BilletRepository();
        $dateAchat = date('Y-m-d H:i:s');
        $lastInsertedId = null;

        for($i = 0; $i < $quantity; $i++) {
            $numeroPlace = "R" . rand(1, 20) . "-P" . rand(1, 100);
            $qrCode = bin2hex(random_bytes(10));

            $lastInsertedId = $billetRepo->create([
                'numero_place' => $numeroPlace,
                'qr_code'      => $qrCode,
                'match_id'     => $matchId,
                'categorie_id' => $categorieId,
                'acheteur_id'  => $userId,
                'date_achat'   => $dateAchat
            ]);
        }

        $b = $billetRepo->findDetailById($lastInsertedId);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

       $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Helvetica', sans-serif; background-color: #f8fafc; padding: 20px; }
                .ticket {
                    width: 100%;
                    max-width: 700px;
                    margin: 0 auto;
                    background-color: #1e293b; /* Slate-800 */
                    border-radius: 15px;
                    color: white;
                    overflow: hidden;
                    border: 1px solid #334155;
                }
                .header {
                    background-color: #10b981; /* Emerald-500 */
                    padding: 15px 30px;
                    text-align: left;
                }
                .header-title {
                    font-size: 24px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    color: #ffffff;
                }
                .main-content {
                    padding: 30px;
                }
                .match-title {
                    font-size: 28px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    color: #f1f5f9;
                }
                .info-table {
                    width: 100%;
                    margin-top: 20px;
                    border-top: 1px solid #334155;
                    padding-top: 20px;
                }
                .label {
                    color: #94a3b8;
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                .value {
                    font-size: 16px;
                    font-weight: bold;
                    color: #ffffff;
                    padding-bottom: 15px;
                }
                .qr-section {
                    background-color: #ffffff;
                    padding: 20px;
                    text-align: center;
                    border-top: 3px dashed #1e293b;
                }
                .footer {
                    background-color: #0f172a;
                    padding: 10px;
                    text-align: center;
                    font-size: 10px;
                    color: #64748b;
                }
            </style>
        </head>
        <body>
            <div class='ticket'>
                <div class='header'>
                    <span class='header-title'>BuyMatch</span>
                </div>

                <div class='main-content'>
                    <div class='match-title'>{$b['e1_nom']} <span style='color:#10b981;'>VS</span> {$b['e2_nom']}</div>
                    
                    <table class='info-table'>
                        <tr>
                            <td width='50%'>
                                <div class='label'>Location</div>
                                <div class='value'>{$b['lieu']}</div>
                            </td>
                            <td width='50%'>
                                <div class='label'>Date & Time</div>
                                <div class='value'>" . date('D, M d, Y', strtotime($b['date_heure'])) . " - " . date('H:i', strtotime($b['date_heure'])) . "</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class='label'>Category</div>
                                <div class='value' style='color:#10b981;'>{$b['cat_nom']}</div>
                            </td>
                            <td>
                                <div class='label'>Seat Number</div>
                                <div class='value'>{$b['numero_place']}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class='qr-section'>
                    <img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($b['qr_code']) . "' width='120'>
                    <div style='color: #1e293b; font-weight: bold; margin-top: 5px; font-size: 12px;'>Official Entry Pass #{$b['id']}</div>
                </div>

                <div class='footer'>
                    This ticket is unique and strictly personal. Total amount: " . ($quantity * 1) . " ticket(s) issued.
                </div>
            </div>
        </body>
        </html>";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        // 4. Envoi du Mail
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Serveur Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aitjamaayouness1@gmail.com'; 
        $mail->Password   = 'dits dquw dpkh oxbw'; // MOT DE PASSE D'APPLICATION (16 caractères)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@buymatch.com', 'BuyMatch Tickets');
        $mail->addAddress($userEmail);
        
        $mail->addStringAttachment($pdfContent, "Confirmation_Achat_{$lastInsertedId}.pdf");

        $mail->isHTML(true);
        $mail->Subject = "Vos billets pour le match {$b['e1_nom']} vs {$b['e2_nom']}";
        $mail->Body    = "Merci pour votre confiance. Votre confirmation est en pièce jointe.";

        $mail->send();

        header('Location: ../acheteur/dashboard.php?order=success');
        exit;

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
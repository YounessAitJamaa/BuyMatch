<?php
    session_start();
    require_once '../../vendor/autoload.php';
    require_once '../../repositories/BilletRepository.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $billetId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $userId = $_SESSION['user_id'] ?? null;

    if (!$billetId || !$userId) {
        die("Accès refusé");
    }

    $billetRepo = new BilletRepository();
    $b = $billetRepo->findDetailById($billetId);

    if ($b['acheteur_id'] != $userId) {
        die("Ce billet ne vous appartient pas.");
    }

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $html = "
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .ticket { border: 2px solid #333; padding: 20px; width: 500px; margin: auto; }
        .header { border-bottom: 2px dashed #ccc; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .teams { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .details { margin-bottom: 20px; font-size: 14px; }
        .qr-code { text-align: center; }
    </style>
    <div class='ticket'>
        <div class='header'>
            <h1>BILLET OFFICIEL</h1>
            <p>BuyMatch - Référence: #{$b['id']}</p>
        </div>
        <div class='teams'>
            {$b['e1_nom']} vs {$b['e2_nom']}
        </div>
        <div class='details'>
            <p><strong>Lieu :</strong> {$b['lieu']}</p>
            <p><strong>Date :</strong> " . date('d/m/Y', strtotime($b['date_heure'])) . " à " . date('H:i', strtotime($b['date_heure'])) . "</p>
            <p><strong>Catégorie :</strong> {$b['cat_nom']}</p>
            <p><strong>Place :</strong> {$b['numero_place']}</p>
        </div>
        <div class='qr-code'>
            <img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$b['qr_code']}' width='150'>
            <p style='font-size: 10px;'>Scannez ce code à l'entrée du stade</p>
        </div>
    </div>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("Billet_{$b['e1_nom']}_vs_{$b['e2_nom']}.pdf", ["Attachment" => true]);
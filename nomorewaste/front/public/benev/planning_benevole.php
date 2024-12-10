<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../back/includes/db.php'; // Connexion à la base de données
require '../../../vendor/autoload.php'; // Inclusion de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Vérifier si l'utilisateur est connecté et s'il est un bénévole
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Bénévole') {
    die('Accès refusé. Vous devez être bénévole pour accéder à cette page.');
}

$id_benevole = $_SESSION['user_id'];

// Récupérer les paniers sélectionnés depuis le formulaire
if (!empty($_POST['paniers'])) {
    $paniersSelectionnes = $_POST['paniers'];
    $dateRecuperation = $_POST['date_recuperation'];
    $creneauHoraire = $_POST['creneau_horaire'];

    // Pour chaque panier sélectionné, assigner le bénévole et mettre à jour
    foreach ($paniersSelectionnes as $id_collecte) {
        $stmt = $conn->prepare("UPDATE collectes SET id_utilisateur = :id_benevole, etat = 'en cours', date_collecte = :date_collecte WHERE id_collecte = :id_collecte");
        $stmt->bindParam(':id_benevole', $id_benevole);
        $stmt->bindParam(':date_collecte', $dateRecuperation);
        $stmt->bindParam(':id_collecte', $id_collecte);
        $stmt->execute();
    }
}

// Récupérer tous les paniers assignés au bénévole pour son planning
$stmt = $conn->prepare("
    SELECT c.id_collecte, c.description, c.quantite, c.poids, c.valeur_estimée, c.code_barre, c.date_collecte, c.etat, m.adresse
    FROM collectes c
    JOIN commercants m ON c.id_commercant = m.id_commercant
    WHERE c.id_utilisateur = :id_benevole
");

$stmt->bindParam(':id_benevole', $id_benevole);
$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Création du fichier Excel avec PhpSpreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Planning Bénévole');

// En-têtes du fichier Excel
$headers = ['ID Collecte', 'Description', 'Quantité', 'Poids (kg)', 'Valeur estimée (€)', 'Code Barre', 'Date de Collecte', 'État', 'Adresse (Google Maps)', 'QR Code'];

// Ajouter les en-têtes dans la première ligne
$sheet->fromArray($headers, null, 'A1');

// Styliser les en-têtes
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 12,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF00',
        ],
    ],
];

// Appliquer le style aux en-têtes
$sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

// Ajouter les collectes au fichier Excel avec liens vers Google Maps et QR Code sous forme de formule Excel
$row = 2;
foreach ($collectes as $collecte) {
    $adresse = htmlspecialchars($collecte['adresse']);
    $google_maps_link = "https://www.google.com/maps/search/?api=1&query=" . urlencode($adresse);

    // Formule Excel pour générer le QR code pour chaque commande
    $qr_code_formula = "=IMAGE(\"https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode("http://localhost:8080/PA%20RATT/nomorewaste/front/public/benev/generate_pdf.php?id=" . $collecte['id_collecte']) . "\")";

    $collecte_data = [
        $collecte['id_collecte'],
        $collecte['description'],
        $collecte['quantite'],
        $collecte['poids'],
        $collecte['valeur_estimée'],
        $collecte['code_barre'],
        $collecte['date_collecte'],
        $collecte['etat'],
        $google_maps_link,
        $qr_code_formula  // Ajouter la formule QR code dans la dernière colonne
    ];
    $sheet->fromArray($collecte_data, null, "A{$row}");

    // Créer un lien hypertexte vers Google Maps
    $sheet->getCell("I{$row}")->getHyperlink()->setUrl($google_maps_link);
    $sheet->setCellValue("I{$row}", "Ouvrir l'adresse sur Google Maps");

    // Appliquer la formule QR code dans la colonne J
    $sheet->setCellValue("J{$row}", $qr_code_formula);

    $row++;
}

// Ajuster automatiquement la largeur des colonnes
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Styliser les bordures des données
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
$sheet->getStyle("A2:J{$row}")->applyFromArray($dataStyle);

// Générer et télécharger le fichier Excel
$date_du_jour = date('Y-m-d');
$filename = "planning_benevole_{$id_benevole}_{$date_du_jour}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

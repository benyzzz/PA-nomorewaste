<?php
require '../../../back/includes/fpdf/fpdf.php';
require '../../../back/includes/db.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier que l'ID de la collecte est passé dans l'URL
if (!isset($_GET['id'])) {
    die('ID de collecte manquant.');
}

$id_collecte = $_GET['id'];

// Récupérer les informations de la collecte et du commerçant depuis la base de données
$stmt = $conn->prepare("
    SELECT c.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur, u.adresse AS adresse_utilisateur, u.ville, u.pays, u.code_postal,
           m.nom_entreprise AS nom_commercant, m.adresse AS adresse_commercant, m.ville AS ville_commercant, m.telephone AS telephone_commercant, m.email AS email_commercant
    FROM collectes c
    JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
    JOIN commercants m ON c.id_commercant = m.id_commercant
    WHERE c.id_collecte = :id_collecte
");
$stmt->bindParam(':id_collecte', $id_collecte);
$stmt->execute();
$collecte = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$collecte) {
    die('Commande non trouvée.');
}

// Vérification et traitement des valeurs nulles pour éviter l'avertissement de dépréciation
function safe_utf8_decode($value) {
    return $value !== null ? utf8_decode($value) : '';
}

// Créer un PDF avec FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Charger les polices DejaVu
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.php');
$pdf->AddFont('DejaVu-Bold','','DejaVuSansCondensed-Bold.php');
$pdf->AddFont('DejaVu-Italic','','DejaVuSansCondensed-Oblique.php');

// Titre du document
$pdf->SetFont('DejaVu-Bold', '', 20);
$pdf->Cell(190, 10, safe_utf8_decode('Bon de Livraison'), 0, 1, 'C');
$pdf->Ln(10);

// Informations sur l'expéditeur (commerçant)
$pdf->SetFont('DejaVu-Bold', '', 12);
$pdf->Cell(100, 10, safe_utf8_decode('Expéditeur :'), 0, 1);
$pdf->SetFont('DejaVu', '', 12);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['nom_commercant']), 0, 1);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['adresse_commercant']), 0, 1);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['ville_commercant']), 0, 1);
$pdf->Cell(100, 8, safe_utf8_decode('Téléphone : ' . $collecte['telephone_commercant']), 0, 1);
$pdf->Cell(100, 8, safe_utf8_decode('Email : ' . $collecte['email_commercant']), 0, 1);
$pdf->Ln(10);

// Informations sur le bénéficiaire (utilisateur)
$pdf->SetFont('DejaVu-Bold', '', 12);
$pdf->Cell(100, 10, safe_utf8_decode('Informations du Bénéficiaire :'), 0, 1);
$pdf->SetFont('DejaVu', '', 12);
$pdf->Cell(50, 8, 'Nom :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['prenom_utilisateur'] . ' ' . $collecte['nom_utilisateur']), 0, 1);

$pdf->Cell(50, 8, 'Adresse :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['adresse_utilisateur']), 0, 1);

$pdf->Cell(50, 8, 'Ville :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['ville']), 0, 1);

$pdf->Cell(50, 8, 'Pays :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['pays']), 0, 1);

$pdf->Cell(50, 8, 'Code postal :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['code_postal']), 0, 1);
$pdf->Ln(10);

// Détails de la commande
$pdf->SetFont('DejaVu-Bold', '', 12);
$pdf->Cell(100, 10, safe_utf8_decode('Détails du Panier :'), 0, 1);
$pdf->SetFont('DejaVu', '', 12);

$pdf->Cell(50, 8, 'Description :', 0, 0);
$pdf->MultiCell(130, 8, safe_utf8_decode($collecte['description']), 0, 1);

$pdf->Cell(50, 8, 'Quantite :', 0, 0);
$pdf->Cell(100, 8, $collecte['quantite'], 0, 1);

$pdf->Cell(50, 8, 'Poids (kg) :', 0, 0);
$pdf->Cell(100, 8, $collecte['poids'], 0, 1);

$pdf->Cell(50, 8, 'Valeur estimee (€) :', 0, 0);
$pdf->Cell(100, 8, $collecte['valeur_estimée'], 0, 1);

$pdf->Cell(50, 8, 'Date de Collecte :', 0, 0);
$pdf->Cell(100, 8, $collecte['date_collecte'], 0, 1);

$pdf->Cell(50, 8, 'Etat :', 0, 0);
$pdf->Cell(100, 8, safe_utf8_decode($collecte['etat']), 0, 1);
$pdf->Ln(10);

// Ajouter l'image du code-barres si disponible
$pdf->SetFont('DejaVu', '', 12);
$pdf->Cell(100, 10, 'Code Barre :', 0, 1);
$pdf->Image('barcode.png', $pdf->GetX(), $pdf->GetY(), 50, 20);
$pdf->Ln(30);

// Signature
$pdf->SetFont('DejaVu-Italic', '', 12);
$pdf->Cell(100, 10, safe_utf8_decode('Signature du livreur :'), 0, 1);
$pdf->Ln(20);

$pdf->Cell(100, 10, '_________________________', 0, 1);
$pdf->Cell(100, 10, safe_utf8_decode('Nom et Prénom du Livreur'), 0, 1);

// Générer le fichier PDF sans aucune sortie préalable
$filename = "Bon_de_Livraison_{$id_collecte}.pdf";
$pdf->Output('D', $filename);
exit();

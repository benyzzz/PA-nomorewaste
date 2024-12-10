<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'includes/fpdf/fpdf.php';
include 'includes/db.php';

// Load DejaVu font if it exists
define('FPDF_FONTPATH', 'includes/fpdf/font/');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE id_utilisateur = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.php');
        $pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.php');
        $pdf->AddFont('DejaVu', 'I', 'DejaVuSansCondensed-Oblique.php'); // or 'Italic.php'
        $pdf->SetFont('DejaVu', 'B', 16);

        // Header
        $pdf->Cell(0, 10, 'User Details', 0, 1, 'C');
        $pdf->Ln(10);

        // User Information
        $pdf->SetFont('DejaVu', 'B', 12);
        $pdf->Cell(50, 10, 'Field', 1, 0, 'C');
        $pdf->Cell(0, 10, 'Details', 1, 1, 'C');
        $pdf->SetFont('DejaVu', '', 12);

        foreach ($utilisateur as $key => $value) {
            // Mask the password with ****
            if ($key == 'mot_de_passe') {
                $value = str_repeat('*', strlen($value));
            }
            // Properly decode UTF-8 values for accented characters
            $pdf->Cell(50, 10, ucfirst($key) . ':', 1);
            $pdf->Cell(0, 10, utf8_decode($value), 1, 1);
        }

        // Footer
        $pdf->SetY(-15);
        $pdf->SetFont('DejaVu', 'I', 8); // or 'Oblique'
        $pdf->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, 0, 'C');

        $pdf->Output();
    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID provided.";
}
?>

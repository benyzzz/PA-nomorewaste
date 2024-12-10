<?php
require('includes/fpdf/fpdf.php');
require('includes/barcode.php');

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Code-barres', 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Générer le code-barres
    $barcode = new Barcode();
    $barcode->generate($code, 'barcode.png');

    // Afficher le code-barres
    $pdf->Image('barcode.png', 10, 30, 100, 30);
    $pdf->Ln(40);

    // Afficher le numéro du code-barres
    $pdf->Cell(0, 10, 'Code-barres: ' . $code, 0, 1, 'C');

    // Générer le PDF
    $pdf->Output();
    // Supprimer l'image du code-barres après génération du PDF
    unlink('barcode.png');
}
?>

<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté et s'il est un bénévole ou un salarié
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Bénévole' && $_SESSION['role'] !== 'Salarié')) {
    die('Accès refusé. Vous devez être bénévole ou salarié pour accéder à cette page.');
}

// Récupérer les paniers "en cours" assignés au bénévole/salarié
$id_utilisateur = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM collectes WHERE etat = 'en cours' AND id_utilisateur = :id_utilisateur");
$stmt->bindParam(':id_utilisateur', $id_utilisateur);
$stmt->execute();
$paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser un message d'erreur vide
$error_message = '';


// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_collecte = $_POST['id_collecte'];

    // Vérification de l'ID dans le nom du fichier PDF
    $pdfName = $_FILES['bon_livraison']['name'];
    $fileId = pathinfo($pdfName, PATHINFO_FILENAME);
    $fileId = substr($fileId, strrpos($fileId, '_') + 1);

    if ($fileId != $id_collecte) {
        $error_message = "L'ID du fichier PDF ne correspond pas à l'ID du panier sélectionné.";
    } elseif (empty($_POST['signature'])) {
        $error_message = "Veuillez signer avant de valider.";
    } else {
        // Vérifier que le répertoire de destination existe, sinon le créer
        $uploadDir = '../../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Gérer le téléchargement du PDF
        $pdfTmpName = $_FILES['bon_livraison']['tmp_name'];
        $pdfDestination = $uploadDir . $pdfName;
        if (!move_uploaded_file($pdfTmpName, $pdfDestination)) {
            $error_message = "Erreur lors du téléchargement du fichier PDF.";
        } else {
            // Mettre à jour l'état de la collecte à "vendu"
            $stmt = $conn->prepare("UPDATE collectes SET etat = 'vendu' WHERE id_collecte = :id_collecte");
            $stmt->bindParam(':id_collecte', $id_collecte);
            if ($stmt->execute()) {
                echo "<script>alert('Le panier a été déposé avec succès.'); window.location.href='../index_bene.php';</script>";
                exit();
            } else {
                $error_message = "Erreur lors de la mise à jour de la base de données.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer un Panier</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #signature-pad {
            border: 2px dashed #ccc;
            border-radius: 4px;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .buttons {
            text-align: center;
            margin-top: 1.5rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: #fff;
            margin-right: 1rem;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #f44336;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #e53935;
        }
        .btn-back {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            display: inline-block;
            border-radius: 4px;
            margin-top: 1rem;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        canvas {
            background-color: #fff;
        }
    </style>
    <script>
        function showError(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Déposer un Panier</h1>
        <h4>⚠️ Vérifié à rentrer le fichier correspondant à la bonne commande sinon le dépôt ne sera pas validé ⚠️</h4>

        <?php if (!empty($error_message)): ?>
            <script>
                showError('<?php echo $error_message; ?>');
            </script>
        <?php endif; ?>

        <?php if (empty($paniers)): ?>
            <p>Aucun panier à déposer pour le moment.</p>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="id_collecte">Sélectionnez le panier à déposer :</label>
                    <select name="id_collecte" id="id_collecte" required>
                        <?php foreach ($paniers as $panier): ?>
                            <option value="<?php echo $panier['id_collecte']; ?>">
                                <?php echo htmlspecialchars($panier['description']); ?> (ID: <?php echo $panier['id_collecte']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bon_livraison">Télécharger le PDF du bon de livraison : </label>
                    <input type="file" name="bon_livraison" id="bon_livraison" accept="application/pdf" required>
                </div>

                <div class="form-group">
                    <label for="signature">Signer le certificat de dépôt :</label>
                    <canvas id="signature-pad" width="400" height="200"></canvas>
                    <button type="button" class="btn btn-secondary" onclick="clearSignature()">Effacer</button>
                    <textarea name="signature" id="signature" style="display:none;"></textarea>
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Valider le dépôt</button>
                    <a href="../index_bene.php" class="btn-back">Retour à l'accueil</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        var canvas = document.getElementById('signature-pad');
        var ctx = canvas.getContext('2d');
        var drawing = false;

        canvas.addEventListener('mousedown', function(event) {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(event.offsetX, event.offsetY);
        });

        canvas.addEventListener('mousemove', function(event) {
            if (drawing) {
                ctx.lineTo(event.offsetX, event.offsetY);
                ctx.stroke();
            }
        });

        canvas.addEventListener('mouseup', function() {
            drawing = false;
        });

        canvas.addEventListener('mouseleave', function() {
            drawing = false;
        });

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function validateForm() {
            var signaturePad = document.getElementById('signature-pad');
            var signature = signaturePad.toDataURL();

            if (signaturePad.getContext('2d').getImageData(0, 0, canvas.width, canvas.height).data.some(channel => channel !== 0)) {
                document.getElementById('signature').value = signature;
            } else {
                alert('Veuillez signer avant de valider.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté et s'il est un commerçant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Commerçant') {
    die('Accès refusé. Vous devez être commerçant pour accéder à cette page.');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $quantite = $_POST['quantite'];
    $poids = $_POST['poids'];
    $valeur_estimee = $_POST['valeur_estimee'];
    $date_collecte = date('Y-m-d'); // Date actuelle
    $code_barre = $_POST['code_barre']; // Code-barre

    // ID du commerçant connecté
    $id_commercant = $_SESSION['user_id'];

    // Insérer la nouvelle collecte dans la base de données
    try {
        $stmt = $conn->prepare("
            INSERT INTO collectes (date_collecte, id_commercant, description, poids, quantite, valeur_estimée, code_barre, etat)
            VALUES (:date_collecte, :id_commercant, :description, :poids, :quantite, :valeur_estimee, :code_barre, 'en cours')
        ");
        $stmt->bindParam(':date_collecte', $date_collecte);
        $stmt->bindParam(':id_commercant', $id_commercant);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':poids', $poids);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':valeur_estimee', $valeur_estimee);
        $stmt->bindParam(':code_barre', $code_barre);
        $stmt->execute();

        // Pop-up de confirmation + redirection après 3 secondes
        echo "<script>
            alert('Merci pour votre don ! Votre panier a été déposé avec succès.');
            setTimeout(function() {
                window.location.href = '../index_comm.php';
            }, 3000);
        </script>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer un Panier Repas</title>
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #6f42c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #563d7c;
        }

        #videoElement {
            width: 100%;
            max-height: 300px;
        }

        #canvas {
            display: none;
        }

        .info-message {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Déposer un Panier Repas</h1>

        <form method="POST" action="deposer_panier.php">
            <div class="form-group">
                <label for="description">Description du panier</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" name="quantite" id="quantite" required>
            </div>
            <div class="form-group">
                <label for="poids">Poids (kg)</label>
                <input type="number" step="0.01" name="poids" id="poids" required>
            </div>
            <div class="form-group">
                <label for="valeur_estimee">Valeur Estimée (€)</label>
                <input type="number" step="0.01" name="valeur_estimee" id="valeur_estimee" required>
            </div>

            <div class="form-group">
                <label for="code_barre">Code Barre / numéro du produit</label>
                <input type="text" id="code_barre" name="code_barre" placeholder="Saisir manuellement le code-barres">
                <p class="info-message">Si la reconnaissance ne fonctionne pas, veuillez saisir manuellement le code-barres.</p>
            </div>

            <div class="form-group">
                <button type="button" class="btn" id="start-camera">Scanner le code-barres</button>
            </div>

            <video id="videoElement" autoplay></video>
            <canvas id="canvas"></canvas>

            <div class="form-group">
                <button type="button" class="btn" id="capture">Prendre une photo</button>
            </div>

            <div id="results"></div>

            <button type="submit" class="btn">Déposer le Panier</button>
        </form>

        <!-- Lien vers mes paniers -->
        <div class="form-group">
            <a href="mes_paniers2.php" class="btn">Voir mes paniers</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.4/dist/tesseract.min.js"></script>
    <script>
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const startCameraBtn = document.getElementById('start-camera');
        const captureBtn = document.getElementById('capture');
        const resultsDiv = document.getElementById('results');
        const codeBarreInput = document.getElementById('code_barre');
        let stream = null; // Pour garder la trace du flux vidéo

        // Fonction pour activer ou désactiver la caméra
        startCameraBtn.addEventListener('click', async () => {
            if (!stream) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = stream;
                    startCameraBtn.textContent = "Arrêter la caméra";
                } catch (err) {
                    console.error("Erreur lors de l'accès à la caméra : ", err);
                }
            } else {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
                stream = null;
                startCameraBtn.textContent = "Scanner le code-barres";
            }
        });

        // Capture de la photo
        captureBtn.addEventListener('click', () => {
            if (stream) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Utilisation de Tesseract.js pour la reconnaissance des caractères
                Tesseract.recognize(canvas, 'eng', {
                    logger: (m) => console.log(m)
                }).then(({ data: { text } }) => {
                    resultsDiv.innerText = `Texte détecté : ${text}`;
                    codeBarreInput.value = text.trim(); // Mettre le texte détecté dans l'input
                }).catch(err => {
                    console.error("Erreur de reconnaissance : ", err);
                });
            }
        });
    </script>
</body>
</html>

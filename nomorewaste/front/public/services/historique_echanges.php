<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../../back/includes/db.php'; // Connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../connex/connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Récupérer les échanges de l'utilisateur connecté
$stmt_user = $conn->prepare("
    SELECT s.nom_service AS service_demandeur, s2.nom_service AS service_autre, s.etat, u.nom AS accepte_par, u.email
    FROM services s
    JOIN services s2 ON s.id_service_autre = s2.id_service
    LEFT JOIN utilisateurs u ON s.id_utilisateur_beneficiaire = u.id_utilisateur
    WHERE s.id_utilisateur = :id_utilisateur AND s.type_service = 'echange'
");
$stmt_user->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
$stmt_user->execute();
$echanges_user = $stmt_user->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les échanges de tous les utilisateurs sauf ceux de l'utilisateur connecté
$stmt_all = $conn->prepare("
    SELECT s.id_service, s.nom_service AS service_demandeur, s2.nom_service AS service_autre, s.etat, u.nom AS demandeur, s.id_utilisateur
    FROM services s
    JOIN services s2 ON s.id_service_autre = s2.id_service
    JOIN utilisateurs u ON s.id_utilisateur = u.id_utilisateur
    WHERE s.type_service = 'echange' AND s.id_utilisateur != :id_utilisateur
");
$stmt_all->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
$stmt_all->execute();
$echanges_all = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Échanges</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .menu {
            margin-bottom: 20px;
        }
        .menu button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            background-color: #6f42c1;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .menu button:hover {
            background-color: #563d7c;
        }
        .section {
            display: none;
        }
        .active {
            display: block;
        }
        .echange {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        .echange h3 {
            margin-top: 0;
        }
        .echange p {
            margin: 5px 0;
        }
        .actions button {
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .actions button.refuse {
            background-color: #e74c3c;
        }
    </style>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        function handleExchange(id_service, action) {
            fetch('process_echange_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_service=${id_service}&action=${action}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="menu">
            <button onclick="showSection('myExchanges')">Mes Demandes d'Échange</button>
            <button onclick="showSection('allExchanges')">Tous les Échanges</button>
            <button onclick="window.location.href='les_services.php'">Retour à la page Services</button>
        </div>

        <!-- Section: Mes Demandes d'Échange -->
        <div id="myExchanges" class="section active">
            <h1>Mes Demandes d'Échange</h1>
            <?php if (empty($echanges_user)): ?>
                <p>Aucun échange trouvé.</p>
            <?php else: ?>
                <?php foreach ($echanges_user as $echange): ?>
                    <div class="echange">
                        <h3>Service demandé : <?php echo htmlspecialchars($echange['service_demandeur']); ?></h3>
                        <p>Service proposé en échange : <?php echo htmlspecialchars($echange['service_autre']); ?></p>
                        <p>État de l'échange : <?php echo htmlspecialchars($echange['etat']); ?></p>
                        <?php if ($echange['etat'] == 'accepté'): ?>
                            <p>Accepté par : <?php echo htmlspecialchars($echange['accepte_par']); ?></p>
                            <p>Email : <a href="mailto:<?php echo htmlspecialchars($echange['email']); ?>"><?php echo htmlspecialchars($echange['email']); ?></a></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Section: Tous les Échanges -->
        <div id="allExchanges" class="section">
            <h1>Tous les Échanges</h1>
            <?php if (empty($echanges_all)): ?>
                <p>Aucun échange trouvé.</p>
            <?php else: ?>
                <?php foreach ($echanges_all as $echange): ?>
                    <div class="echange">
                        <h3>Service demandé par : <?php echo htmlspecialchars($echange['demandeur']); ?></h3>
                        <p>Service demandé : <?php echo htmlspecialchars($echange['service_demandeur']); ?></p>
                        <p>Service proposé en échange : <?php echo htmlspecialchars($echange['service_autre']); ?></p>
                        <p>État de l'échange : <?php echo htmlspecialchars($echange['etat']); ?></p>
                        <?php if ($echange['etat'] == 'en attente'): ?>
                            <div class="actions">
                                <button onclick="handleExchange(<?php echo $echange['id_service']; ?>, 'accept')">Accepter</button>
                                <button class="refuse" onclick="handleExchange(<?php echo $echange['id_service']; ?>, 'refuse')">Refuser</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

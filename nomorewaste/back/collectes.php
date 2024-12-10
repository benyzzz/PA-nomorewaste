<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin vers le fichier db.php
include 'includes/db.php';
// Chemin vers le fichier header.php
include 'templates/header.php';

// Fetch the data for the collections
$etat_filter = isset($_GET['etat']) ? $_GET['etat'] : '';

$query = "
    SELECT
        co.id_collecte,
        co.date_collecte,
        co.poids,
        co.quantite,
        co.code_barre,
        co.valeur_estimée,
        co.etat,
        c.nom_entreprise,
        u.nom AS utilisateur_nom,
        u.prenom AS utilisateur_prenom
    FROM
        Collectes co
    JOIN
        Commercants c ON co.id_commercant = c.id_commercant
    JOIN
        Utilisateurs u ON co.id_utilisateur = u.id_utilisateur
";

if ($etat_filter) {
    $query .= " WHERE co.etat = :etat";
}

$stmt = $conn->prepare($query);

if ($etat_filter) {
    $stmt->bindParam(':etat', $etat_filter);
}

$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des Collectes</h2>

<form method="get" action="collectes.php">
    <label for="etat">Filtrer par état:</label>
    <select name="etat" id="etat" onchange="this.form.submit()">
        <option value="">Tous</option>
        <option value="vendu" <?php if ($etat_filter == 'vendu') echo 'selected'; ?>>Vendu</option>
        <option value="en cours" <?php if ($etat_filter == 'en cours') echo 'selected'; ?>>En cours</option>
        <option value="à la vente" <?php if ($etat_filter == 'à la vente') echo 'selected'; ?>>À la vente</option>
    </select>
</form>

<table id="collectesTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Poids (kg)</th>
            <th>Quantité</th>
            <th>Code Barre</th>
            <th>Valeur Estimée (€)</th>
            <th>État</th>
            <th>Nom du Commerçant</th>
            <th>Nom de l'Utilisateur</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($collectes as $collecte): ?>
        <tr>
            <td><?php echo htmlspecialchars($collecte['id_collecte']); ?></td>
            <td><?php echo htmlspecialchars($collecte['date_collecte']); ?></td>
            <td><?php echo htmlspecialchars($collecte['poids']); ?></td>
            <td><?php echo htmlspecialchars($collecte['quantite']); ?></td>
            <td><a href="generate_barcode.php?code=<?php echo htmlspecialchars($collecte['code_barre']); ?>" target="_blank"><?php echo htmlspecialchars($collecte['code_barre']); ?></a></td>
            <td><?php echo htmlspecialchars($collecte['valeur_estimée']); ?></td>
            <td><?php echo htmlspecialchars($collecte['etat']); ?></td>
            <td><?php echo htmlspecialchars($collecte['nom_entreprise']); ?></td>
            <td><?php echo htmlspecialchars($collecte['utilisateur_nom'] . ' ' . $collecte['utilisateur_prenom']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>

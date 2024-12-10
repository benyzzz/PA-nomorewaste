<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';
include 'templates/header.php';

// Limites pour les alertes
$limite_poids = 500;
$limite_paniers = 200;

// Récupérer les données des collectes, en les agrégant par site avec une jointure gauche
$query = "
    SELECT
        s.nom_site,
        COALESCE(SUM(c.poids), 0) AS poids_total,
        COALESCE(COUNT(c.id_collecte), 0) AS nombre_paniers,
        COALESCE(SUM(c.valeur_estimée), 0) AS valeur_totale
    FROM
        Sites s
    LEFT JOIN
        Commercants co ON s.id_site = co.id_site
    LEFT JOIN
        Collectes c ON co.id_commercant = c.id_commercant AND c.etat IN ('à la vente', 'en cours')
    GROUP BY
        s.nom_site
";
$stmt = $conn->prepare($query);
$stmt->execute();
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier s'il y a des données récupérées
if (empty($stocks)) {
    echo '<p>Aucune donnée de stockage trouvée.</p>';
    exit;
}

?>

<h2>Gestion des Stockages</h2>

<div class="stocks-container">
    <?php foreach ($stocks as $stock): ?>
        <div class="stock-item">
            <h3><?php echo htmlspecialchars($stock['nom_site']); ?></h3>
            <div class="progress-bar-container">
                <label>Poids Total (kg): <?php echo htmlspecialchars($stock['poids_total']); ?></label>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo min(100, ($stock['poids_total'] / $limite_poids) * 100); ?>%; background-color: <?php echo ($stock['poids_total'] >= $limite_poids) ? 'red' : 'green'; ?>"></div>
                </div>
                <?php if ($stock['poids_total'] >= $limite_poids): ?>
                    <div class="alert">Alerte: Poids dépassé!</div>
                <?php endif; ?>
            </div>
            <div class="progress-bar-container">
                <label>Nombre de Paniers: <?php echo htmlspecialchars($stock['nombre_paniers']); ?></label>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo min(100, ($stock['nombre_paniers'] / $limite_paniers) * 100); ?>%; background-color: <?php echo ($stock['nombre_paniers'] >= $limite_paniers) ? 'red' : 'green'; ?>"></div>
                </div>
                <?php if ($stock['nombre_paniers'] >= $limite_paniers): ?>
                    <div class="alert">Alerte: Nombre de paniers dépassé!</div>
                <?php endif; ?>
            </div>
            <div class="progress-bar-container">
                <label>Valeur Totale (€): <?php echo htmlspecialchars($stock['valeur_totale']); ?></label>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
include 'templates/footer.php';
?>

<style>
.stocks-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.stock-item {
    border: 1px solid #ddd;
    padding: 20px;
    width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.progress-bar-container {
    margin-bottom: 20px;
}

.progress-bar {
    background-color: #f3f3f3;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.progress {
    height: 20px;
    transition: width 0.3s;
}

.alert {
    color: red;
    font-weight: bold;
}
</style>

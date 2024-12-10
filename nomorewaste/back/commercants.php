<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';
include 'templates/header.php';

// Fetch the data for the scoreboard
$stmt = $conn->prepare("
    SELECT
        c.id_commercant,
        c.nom_entreprise,
        c.ville,
        COUNT(co.id_collecte) AS nombre_paniers,
        SUM(co.poids) AS poids_total,
        SUM(co.valeur_estimée) AS valeur_totale
    FROM
        Commercants c
    LEFT JOIN
        Collectes co ON c.id_commercant = co.id_commercant
    GROUP BY
        c.id_commercant, c.nom_entreprise, c.ville
");

$stmt->execute();
$commercants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Tableau de Bord des Commerçants</h2>

<style>
th {
    cursor: pointer;
    position: relative;
}

th .sort {
    font-size: 12px;
    margin-left: 5px;
    display: inline-block;
}

th .sort::after {
    content: " ";
    display: inline-block;
    border: 4px solid transparent;
    border-top-color: black;
    margin-left: 5px;
}

th .sort.asc::after {
    content: "▲";
}

th .sort.desc::after {
    content: "▼";
}
</style>

<table id="commercantsTable">
    <thead>
        <tr>
            <th>Nom de l'entreprise</th>
            <th>Ville</th>
            <th>Nombre de Paniers <span class="sort" data-sort="nombre_paniers"></span></th>
            <th>Poids Total (kg) <span class="sort" data-sort="poids_total"></span></th>
            <th>Valeur Totale (€) <span class="sort" data-sort="valeur_totale"></span></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($commercants as $commercant): ?>
        <tr>
    <td><?php echo htmlspecialchars($commercant['nom_entreprise'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($commercant['ville'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($commercant['nombre_paniers'] ?? 0); ?></td>
    <td><?php echo htmlspecialchars($commercant['poids_total'] ?? 0); ?></td>
    <td><?php echo htmlspecialchars($commercant['valeur_totale'] ?? 0); ?></td>
</tr>

    <?php endforeach; ?>
    </tbody>
</table>

<script>
document.querySelectorAll('.sort').forEach(header => {
    header.addEventListener('click', function() {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const index = Array.from(header.closest('tr').children).indexOf(header.closest('th'));
        const currentOrder = header.classList.contains('asc') ? 'asc' : header.classList.contains('desc') ? 'desc' : '';
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

        document.querySelectorAll('.sort').forEach(span => {
            span.classList.remove('asc', 'desc');
        });
        header.classList.add(newOrder);

        rows.sort((a, b) => {
            const aText = a.children[index].innerText;
            const bText = b.children[index].innerText;
            const aValue = isNaN(aText) ? aText : parseFloat(aText);
            const bValue = isNaN(bText) ? bText : parseFloat(bText);

            return newOrder === 'asc' ? (aValue > bValue ? 1 : -1) : (aValue > bValue ? -1 : 1);
        });

        rows.forEach(row => tbody.appendChild(row));
    });
});
</script>

<?php
include 'templates/footer.php';
?>

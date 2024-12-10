<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';
include 'templates/header.php';

// Fetch services from the database
$stmt = $conn->prepare("SELECT s.id_service, s.nom_service, s.description, s.date_service, s.quantite, u.ville, u.nom, u.prenom
                        FROM Services s
                        JOIN Utilisateurs u ON s.id_utilisateur = u.id_utilisateur");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des services</h2>
<table id="servicesTable">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Date</th>
        <th>Quantité</th>
        <th>Ville</th>
        <th>Utilisateur</th>
        <th>Action</th>
    </tr>
    <?php foreach ($services as $service): ?>
    <tr data-id="<?php echo htmlspecialchars($service['id_service'] ?? ''); ?>">
        <td><?php echo htmlspecialchars($service['id_service'] ?? ''); ?></td>
        <td contenteditable="true"><?php echo htmlspecialchars($service['nom_service'] ?? ''); ?></td>
        <td contenteditable="true"><?php echo htmlspecialchars($service['description'] ?? ''); ?></td>
        <td contenteditable="true"><?php echo htmlspecialchars($service['date_service'] ?? ''); ?></td>
        <td contenteditable="true"><?php echo htmlspecialchars($service['quantite'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($service['ville'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars(($service['nom'] ?? '') . ' ' . ($service['prenom'] ?? '')); ?></td>
        <td>
            <button class="saveButton">Enregistrer</button>
            <button class="deleteButton">Supprimer</button>
        </td>
    </tr>
<?php endforeach; ?>

</table>

<script>
document.querySelectorAll('.saveButton').forEach(button => {
    button.addEventListener('click', function() {
        var row = this.closest('tr');
        var id = row.getAttribute('data-id');
        var nom_service = row.cells[1].innerText;
        var description = row.cells[2].innerText;
        var date_service = row.cells[3].innerText;
        var quantite = row.cells[4].innerText;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_service.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Service mis à jour avec succès.');
            } else {
                alert('Erreur lors de la mise à jour du service.');
            }
        };
        xhr.send('id=' + id + '&nom_service=' + nom_service + '&description=' + description + '&date_service=' + date_service + '&quantite=' + quantite);
    });
});

document.querySelectorAll('.deleteButton').forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
            var row = this.closest('tr');
            var id = row.getAttribute('data-id');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_service.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Service supprimé avec succès.');
                    row.remove();
                } else {
                    alert('Erreur lors de la suppression du service.');
                }
            };
            xhr.send('id=' + id);
        }
    });
});
</script>

<?php
include 'templates/footer.php';
?>

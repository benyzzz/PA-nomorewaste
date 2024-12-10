<?php
include 'includes/db.php';
include 'templates/header.php';

// Fetch users from the database
$stmt = $conn->prepare("SELECT * FROM Utilisateurs");
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="css/styles2.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>
<body>
    <main>
        <div id="table-section">
            <form class="formaction">
                <span class="searchicon"><i class="fas fa-search"></i></span>
                <input type="text" placeholder="Enter something" name="search-box" id="search-box" value="" />
            </form>
            <div id="table-wrapper">
                <div id="table-headers">
                    <table>
                        <thead>
                            <tr>
                                <th class="column1">ID</th>
                                <th class="column2">Nom</th>
                                <th class="column3">Prénom</th>
                                <th class="column4">Email</th>
                                <th class="column5">Rôle</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="table-data">
                    <table>
                        <tbody>
                            <?php foreach ($utilisateurs as $index => $utilisateur): ?>
                                <tr class="data-row <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <td class="column1"><?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
                                    <td class="column2"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                                    <td class="column3"><?php echo htmlspecialchars($utilisateur['prenom']); ?></td>
                                    <td class="column4"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                                    <td class="column5"><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                                    <td class="column6" style="display: none;"><?php echo htmlspecialchars($utilisateur['description']); ?></td>
                                    <td class="column7" style="display: none;"><?php echo htmlspecialchars($utilisateur['adresse']); ?></td>
                                    <td class="column8" style="display: none;"><?php echo htmlspecialchars($utilisateur['ville']); ?></td>
                                    <td class="column9" style="display: none;"><?php echo htmlspecialchars($utilisateur['pays']); ?></td>
                                    <td class="column10" style="display: none;"><?php echo htmlspecialchars($utilisateur['code_postal']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Details box -->
        <div id="info-wrapper">
            <h1>Detailes</h1>
            <p>Click sur les users pour plus d'informations</p>
            <div id="info-content">
                <div class="info-name"><b>User selected:</b> </div>
                <div>
                    <b>Description: </b>
                    <textarea cols="50" rows="5" readonly></textarea>
                </div>
                <div class="adress"><b>Addresse:</b></div>
                <div class="city"><b>Ville:</b></div>
                <div class="state"><b>Pays:</b></div>
                <div class="zipcode"><b>Code Postale:</b></div>
                <button id="delete-user" class="action-button">Supprimer</button>
                <button id="generate-pdf" class="action-button">Générer PDF</button>
            </div>
        </div>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
include 'templates/footer.php';
?>

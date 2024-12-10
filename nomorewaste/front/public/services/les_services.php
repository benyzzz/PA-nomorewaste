<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Services</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            margin: 20px;
            background-color: #6f42c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #563d7c;
        }

        .btn-back {
            background-color: #007bff;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des services</h1>
        <button class="btn" onclick="window.location.href='chercher_service.php'">Chercher un service</button>
        <button class="btn" onclick="window.location.href='proposer_service.php'">Proposer un service</button>
        <button class="btn" onclick="window.location.href='echanger_service.php'">Échanger un service</button>
        <br>
        <button class="btn btn-back" onclick="window.location.href='../index_user.php'">Retour à l'Accueil</button>
    </div>
</body>
</html>

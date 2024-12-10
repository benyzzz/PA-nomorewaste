<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseils Anti-Gaspi | NO MORE WASTE</title>
    <link rel="stylesheet" href="styles.css">


    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            line-height: 1.6;
        }

        header {
            background: #6f42c1;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        section p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .tips {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .tip {
            background: #e7e7e7;
            border-radius: 10px;
            padding: 20px;
            flex: 1;
            min-width: 280px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .tip h3 {
            color: #6f42c1;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .tip img {
            width: 80px;
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background: #6f42c1;
            color: white;
            margin-top: 40px;
        }

        footer p {
            margin: 0;
        }

        .back-arrow {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            font-size: 24px;
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s;
        }

        .back-arrow:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>

    <header>
        <h1 data-translate="headerTitle">Conseils Anti-Gaspi</h1>
        <p data-translate="headerSubtitle">Apprenez comment réduire le gaspillage alimentaire chez vous.</p>
    </header>

    <div class="container">
        <section>
            <h2 data-translate="section1Title">Pourquoi réduire le gaspillage alimentaire ?</h2>
            <p data-translate="section1Content">Chaque année, des tonnes de nourriture sont jetées...</p>
        </section>

        <section>
            <h2 data-translate="section2Title">Nos astuces Anti-Gaspi</h2>
            <div class="tips">
                <div class="tip">
                    <img src="https://img.icons8.com/color/96/000000/fridge.png" alt="Astuce Réfrigérateur">
                    <h3 data-translate="tip1Title">Optimisez votre réfrigérateur</h3>
                    <p data-translate="tip1Content">Rangez correctement vos aliments...</p>
                </div>
                <div class="tip">
                    <img src="https://thumbs.dreamstime.com/b/ic%C3%B4ne-de-portion-nourriture-120821817.jpg" alt="Astuce Portion">
                    <h3 data-translate="tip2Title">Contrôlez vos portions</h3>
                    <p data-translate="tip2Content">Cuisinez des portions adaptées...</p>
                </div>
                <div class="tip">
                    <img src="https://img.mobigama.net/app/9373-time_freeze/1_time_freeze.png" alt="Astuce Congélation">
                    <h3 data-translate="tip3Title">Congelez vos aliments</h3>
                    <p data-translate="tip3Content">Les aliments proches de la date...</p>
                </div>
                <div class="tip">
                    <img src="https://img.icons8.com/color/96/000000/shopping-basket.png" alt="Astuce Liste de Courses">
                    <h3 data-translate="tip4Title">Faites une liste de courses</h3>
                    <p data-translate="tip4Content">Planifiez vos repas et faites une liste...</p>
                </div>
            </div>
        </section>

        <section>
            <h2 data-translate="section3Title">Conseils supplémentaires</h2>
            <p data-translate="section3Content">En plus des astuces ci-dessus, pensez à :</p>
            <ul>
                <li>Faire attention aux dates de péremption...</li>
                <li>Composter vos déchets organiques...</li>
                <li>Participer à des initiatives locales...</li>
                <li>Utiliser les restes pour créer de nouvelles recettes...</li>
            </ul>
        </section>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
    <footer>
    <nav>
    <select id="languageSelector">
        <option value="fr">Français</option>
        <option value="en">English</option>
        <option value="it">Italiano</option>
        <option value="pt">Português</option>
    </select>
</nav>

        <p data-translate="footerText">&copy; 2024 NO MORE WASTE. Tous droits réservés.</p>
    </footer>

    <script src="../../translation/translate.js"></script>


</body>
</html>

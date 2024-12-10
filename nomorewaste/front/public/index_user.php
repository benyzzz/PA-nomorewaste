<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title data-translate="pageTitle1">NO MORE WASTE - Utilisateur</title>
</head>
<body>
  <nav>
    <div class="container">
      <img class="logomenu" src="../../assets/images/Nomorewaste.png" alt="Logo">
      <ul class="listadelinks">
        <li class="menulink"><a href="conseil_anti.php" data-translate="navConseilAntiGaspi">Conseil anti-gaspi</a></li>
        <li class="menulink"><a href="recette.html" data-translate="navRecette">Cours de cuisine</a></li>
        <li class="menulink"><a href="#" data-translate="navPartageVehicule">Partage de véhicule</a></li>
        <li class="menulink"><a href="#" data-translate="navAutresServices">Autres Services</a></li>
        <li class="menulink"><a href="mon_profil.php" data-translate="navMonProfil">Mon profil</a></li>
      </ul>
    </div>
  </nav>

  <div class="container">

</h1>
    <header>
        <div>
        <div class="grid2col">
        <div class="col paddingbottom2rem">
        <h1>Bienvenue
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'Utilisateurs') {
            echo htmlspecialchars($_SESSION['username']); // Affiche le nom de l'entreprise
        } else {
            echo htmlspecialchars($_SESSION['username']); // Affiche le prénom de l'utilisateur
        }
        ?>
          <p data-translate="welcomeContent">NO MORE WASTE vous accompagne dans votre quotidien pour consommer de manière responsable. Accédez à vos paniers alimentaires, échangez des services et découvrez des conseils anti-gaspillage.</p>
          <a href="panier_disponibles.php" class="CTA" data-translate="btnRecupererPaniers">Récupérer des paniers</a>
          <a href="services/les_services.php" class="CTA" data-translate="btnEchangerService">Échanger ou choisir un service</a>
        </div>
        <div class="colimg">
          <img class="gridimg" src="https://images.pexels.com/photos/1300972/pexels-photo-1300972.jpeg?cs=srgb&dl=pexels-magda-ehlers-1300972.jpg&fm=jpg" alt="Image de fruits et légumes">
        </div>
      </div>
    </header>

    <main>
      <section class="col services paddingbottom2rem">
        <h2 data-translate="sectionTitleBeneficiaries">À qui profite NO MORE WASTE ?</h2>
        <ul>
          <li>
            <div class="centrar">
              <img class="iconos" src="../../assets/images/0index.jpg" alt="Icône environnement">
            </div>
            <h3 data-translate="beneficiaryEnvironmentTitle">À l'environnement</h3>
            <p data-translate="beneficiaryEnvironmentContent">NO MORE WASTE soutient l'environnement en réduisant le gaspillage alimentaire...</p>
          </li>
          <li>
            <div class="centrar">
              <img class="iconos" src="../../assets/images/1index.jpg" alt="Icône producteur">
            </div>
            <h3 data-translate="beneficiaryProducersTitle">Aux producteurs locaux</h3>
            <p data-translate="beneficiaryProducersContent">Les producteurs locaux jouent un rôle clé dans notre projet...</p>
          </li>
          <li>
            <div class="centrar">
              <img class="iconos" src="../../assets/images/2index.jpg" alt="Icône consommateur">
            </div>
            <h3 data-translate="beneficiaryConsumersTitle">Aux consommateurs</h3>
            <p data-translate="beneficiaryConsumersContent">En rejoignant NO MORE WASTE, vous avez accès à des produits frais, locaux et écologiques...</p>
          </li>
        </ul>
      </section>

      <section class="col paddingbottom2rem">
        <h2 data-translate="sectionWhyMemberTitle">Pourquoi devenir membre de NO MORE WASTE ?</h2>
        <p data-translate="sectionWhyMemberContent">En tant que membre, vous bénéficiez d'un accès exclusif à plusieurs services uniques...</p>
      </section>

      <section class="grid3col">
        <div class="col1 paddingbottom2rem"><img src="https://images.pexels.com/photos/5529015/pexels-photo-5529015.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="Producteurs locaux" class="gridimg"></div>
        <div class="col col2 paddingbottom2rem">
          <h2 data-translate="sectionImpactTitle">Nous connectons les producteurs locaux avec les consommateurs finaux</h2>
          <p data-translate="sectionImpactContent1">Pour avoir un impact et réaliser un changement réel contre le gaspillage alimentaire...</p>
          <button class="CTA" data-translate="btnEnSavoirPlus">En savoir plus</button>
        </div>
        <div class="col3 paddingbottom2rem"><img src="https://images.pexels.com/photos/5941841/pexels-photo-5941841.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="Paniers périodiques" class="gridimg"></div>
        <div class="col col4">
          <h2 data-translate="sectionConsumptionTitle">Paniers périodiques et groupes de consommation</h2>
          <p data-translate="sectionConsumptionContent">Nous proposons aussi des paniers périodiques et des groupes de consommation...</p>
          <button class="CTA" data-translate="btnEnSavoirPlus">En savoir plus</button>
        </div>
      </section>
    </main>
  </div>

  <footer>
    <div class="containerft">
      <section class="containerftlogo">
        <img class="ftlogo" src="../../assets/images/Nomorewaste.png" alt="Logo">
      </section>
      <section class="ftsection">
        <div class="ft-dos">
          <h4 class="ft-title" data-translate="footerAbout">À propos de nous</h4>
          <ul>
            <li><a href="#" data-translate="footerManifest">Manifeste</a></li>
            <li><a href="#" data-translate="footerProducers">Producteurs</a></li>
            <li><a href="#" data-translate="footerGreenPoints">Points verts</a></li>
            <li><a href="#" data-translate="footerGroups">Groupes de consommation</a></li>
            <li><a href="#" data-translate="footerBlog">Blog</a></li>
          </ul>
        </div>
        <div class="ft-dos">
          <h4 class="ft-title" data-translate="footerSocial">Réseaux sociaux</h4>
          <ul>
            <li><a href="#">Instagram</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">Facebook</a></li>
          </ul>
        </div>
        <div class="ft-dos">
          <h4 class="ft-title" data-translate="footerLegal">Légal</h4>
          <ul>
            <li><a href="#" data-translate="footerTerms">Termes et conditions</a></li>
            <li><a href="#" data-translate="footerPrivacy">Politique de confidentialité</a></li>
            <li><a href="#" data-translate="footerCookies">Politique de cookies</a></li>
            <li><a href="#" data-translate="footerLegalNotice">Mentions légales</a></li>
          </ul>
        </div>
      </section>
    </div>
    <section class="creditos centrar">
      <p data-translate="footerCredits">Benjamin et Didrit Verdier</p>
    </section>

    <!-- Sélecteur de langue -->
    <select id="language-selector">
      <option value="fr">Français</option>
      <option value="en">English</option>
      <option value="pt">Português</option>
      <option value="it">Italiano</option>
    </select>
  </footer>

  <script src="../../translation/translate.js"></script>
</body>
</html>

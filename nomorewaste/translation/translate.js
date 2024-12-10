function loadTranslations(language) {
    console.log(`Chargement des traductions pour la langue: ${language}`);
    fetch(`../../translation/${language}.json`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur: Impossible de charger le fichier de traduction pour ${language}`);
            }
            return response.json();
        })
        .then(data => {
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (data[key]) {
                    element.innerText = data[key];
                } else {
                    console.warn(`Clé manquante dans les traductions: ${key}`);
                }
            });
        })
        .catch(error => {
            console.error(error);
        });
}

document.getElementById('language-selector').addEventListener('change', (event) => {
    const language = event.target.value;
    localStorage.setItem('selectedLanguage', language);
    loadTranslations(language);
});

// Charger la langue sauvegardée lors du dernier choix
const savedLanguage = localStorage.getItem('selectedLanguage') || 'fr';
document.getElementById('language-selector').value = savedLanguage;
loadTranslations(savedLanguage);

console.log("Le fichier translate.js est chargé correctement.");

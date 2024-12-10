var mealList = document.querySelector(".meals-container"),
    searchInput = document.querySelector(".search-field"),
    searchBtn = document.querySelector(".search-btn"),
    recipe = document.querySelector(".recipe"),
    recipeCloseBtn = document.querySelector(".recipe-close-btn"),
    addRecipeForm = document.getElementById("add-recipe-form");

function getMeals(){
  let searchText = searchInput.value.trim().toLowerCase();
  let filteredMeals = recipes.meals.filter(meal => 
    meal.strMeal.toLowerCase().includes(searchText) || 
    meal.strCategory.toLowerCase().includes(searchText)
  );
  
  let html = "";
  if (filteredMeals.length > 0) {
    filteredMeals.forEach(meal => {
      html += `
          <div class="meal-item" data-id="${meal.idMeal}">
            <div class="meal-img">
             <img src="${meal.strMealThumb}" alt="Image de ${meal.strMeal}">
            </div>
             <div class="meal-name">
              <h3>${meal.strMeal}</h3>
              <button class="recipe-btn">
                Obtenir la recette
              </button>
            </div>
          </div>
      `;
    });
  } else {
    html = `<p class="not-found">
    Désolé, nous n'avons trouvé aucun repas!
    </p>`;
  }
  mealList.innerHTML = html;
}

getMeals();

searchBtn.addEventListener('click', getMeals);

function getMealRecipe(e){
  e.preventDefault();
  if(e.target.classList.contains('recipe-btn')){
    let mealItem = e.target.parentElement.parentElement;
    let mealId = mealItem.dataset.id;
    let meal = recipes.meals.find(m => m.idMeal === mealId);
    mealRecipeModal(meal);
  }
}

function mealRecipeModal(meal){
  let html = `
      <h2 class="recipe-title">${meal.strMeal}</h2>
      <p class="recipe-category">${meal.strCategory}</p>
      <div class="recipe-instruct">
        <h3> Instructions: </h3>
        <p> ${meal.strInstructions} </p>
      </div>
      <div class="recipe-meal-img">
        <img src="${meal.strMealThumb}" alt="Image de ${meal.strMeal}">
      </div>
      <div class="recipe-link">
        <a href="${meal.strYoutube}" target="_blank">
          Regarder la vidéo
        </a>
      </div>      
  `;
  recipe.innerHTML = html;
  recipe.parentElement.classList.add('show-recipe');
}

mealList.addEventListener('click', getMealRecipe);

recipeCloseBtn.onclick = () => {
 recipe.parentElement.classList.remove('show-recipe');
};

addRecipeForm.addEventListener('submit', function(e) {
  e.preventDefault();

  const recipeName = document.getElementById('recipe-name').value;
  const recipeIngredients = document.getElementById('recipe-ingredients').value;
  const recipeInstructions = document.getElementById('recipe-instructions').value;
  const recipeCategory = document.getElementById('recipe-category').value;
  const recipeImage = document.getElementById('recipe-image').value;

  let newRecipe = {
    strMeal: recipeName,
    strIngredients: recipeIngredients,
    strInstructions: recipeInstructions,
    strCategory: recipeCategory,
    strMealThumb: recipeImage,
    idMeal: Date.now().toString() // Use a timestamp as a temporary unique ID
  };

  recipes.meals.push(newRecipe);

  let newRecipeHtml = `
    <div class="meal-item" data-id="${newRecipe.idMeal}">
      <div class="meal-img">
       <img src="${newRecipe.strMealThumb}" alt="Image de ${newRecipe.strMeal}">
      </div>
       <div class="meal-name">
        <h3>${newRecipe.strMeal}</h3>
        <button class="recipe-btn">
          Obtenir la recette
        </button>
      </div>
    </div>
  `;
  mealList.insertAdjacentHTML('beforeend', newRecipeHtml);

  addRecipeForm.reset();
});

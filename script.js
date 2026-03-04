function openTab(evt, tabName) {
  // Déclarer les variables
  var i, tabcontent, tablinks;

  // Cacher tous les éléments avec la classe "tabcontent"
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Enlever la classe "active" de tous les boutons d'onglets
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Afficher l'onglet actuel et ajouter la classe "active" au bouton cliqué
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Optionnel : simuler un clic sur le premier onglet au chargement pour qu'il soit affiché par défaut
document.addEventListener("DOMContentLoaded", function() {
    document.getElementsByClassName("tablinks")[0].click();
});
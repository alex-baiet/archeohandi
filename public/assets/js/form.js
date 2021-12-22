/*
Contient des scripts d'édition de formulaire.
*/

/** @type {Map<string, int>} Contient les dernier numéro des champs de persones créé. */
var lastPersonNum = new Map();

/**
 * Ajoute un champ texte pour une liste d'input.
 * 
 * @param {string} id nom de l'attribut "name".
 * @param {string} nom Juste pour avoir un beau label.
 */
function addPerson(id) {
	// Maj numéro
	if (!lastPersonNum.has(id)) lastPersonNum.set(id, 1);
	else lastPersonNum.set(id, lastPersonNum.get(id) + 1);
	let num = lastPersonNum.get(id);

	// Récupération de l'input original et copie
	let originalInput = document.getElementById(`${id}_0`);
	let original = originalInput.parentNode;
	let originalLabel = document.getElementById(`${id}_label_0`);
	let copy = original.cloneNode(false);
	let copyInput = originalInput.cloneNode(false);
	let copyLabel = originalLabel.cloneNode(true);
	copy.appendChild(copyInput);
	copy.appendChild(copyLabel);

	// Modidification de la copie
	copyInput.id = `${id}_${num}`;
	copyInput.value = "";

	copyLabel.id = `${id}_label_${num}`;
	copyLabel.htmlFor = `${id}_${num}`;
	
	// Ajout de la copie
	original.parentNode.appendChild(copy);
	addAutocomplete(`form_id_anthropologue_${num}`, "personne");
}

/**
 * Supprime un champ d'une liste d'input.
 * 
 * @param {string} id
 */
function removePerson(id) {
	// Récupération de l'élément à supprimer
	let toRemove = document.getElementById(`${id}_0`).parentElement.parentElement.lastElementChild;

	// Test pour savoir si on peur le supprimer
	for (const child of toRemove.children) {
		if (child.id === `${id}_0`) {
			console.log("Vous essayer de retirer le dernier champ... Action annulé.");
			return;
		}
	}

	// Suppression de l'élément
	toRemove.remove();
}

/**
 * Ajoute un champ texte pour une liste d'input.
 * 
 * @param {string} name nom de l'attribut "name".
 * @param {string} nom Juste pour avoir un beau label.
 */
function addPersonOld(name, nom) {
	var newDiv = document.createElement('div');
	newDiv.classList.add('col-md-12');

	var newDivfloat = document.createElement('div');
	newDivfloat.classList.add('form-floating');

	var newInput = document.createElement('input');
	newInput.type = 'text';
	newInput.classList.add('form-control');
	newInput.classList.add('my-2');
	newInput.name = name + '[]';
	newInput.placeholder = nom + ' (Nom Prénom)';

	var newLabel = document.createElement('label');
	newLabel.textContent = nom + " (Nom Prénom)";

	newDivfloat.appendChild(newInput);
	newDivfloat.appendChild(newLabel);
	newDiv.appendChild(newDivfloat);

	var monCnt = document.getElementById('block_' + name);
	monCnt.appendChild(newDiv);
}

/**
 * Supprime un champ d'une liste d'input.
 * 
 * @param {string} name 
 */
function removePersonOld(name) {
	$('#block_' + name).children().last().remove();
}

/**
 * Ajoute l'autocomplétion à l'input donné en utilisant la base de données.
 * 
 * @param {string} id 
 * @param {string} type 
 */
function addAutocomplete(id, type) {
	// Récupération du champ
	/** @type {HTMLFormElement} */
	let input = document.getElementById(id);
	if (input === null) {
		console.error(`Le champ ${id} n'existe pas.`);
		return;
	}

	// Création de la zone d'autocomplétion
	let showList = document.createElement("div");
	showList.className = "list-group";
	showList.id = `${id}_list`;
	showList.style.position = "absolute";
	showList.style.zIndex = 1000;
	input.parentNode.appendChild(showList);

	// Assignation de l'action à faire à chaque modification du champ
	input.onkeyup = function() {
		let currentValue = input.value;
    
		if (currentValue != "") {
			// Accès à la BDD via une autre page
      $.ajax({
        url: "https://archeohandi.huma-num.fr/public/fonction/action.php",
        method: "POST",
        data: {
					id: id,
					input: currentValue,
					type: type
				},
        success: function (data) {
					// Action effectué lors du retour de la reponse
					showList.innerHTML = data;
				}
      });
    }
		else {
			// Rien d'entré...
      showList.innerHTML = "";
    }
	}

	// Ajout action en cas de sélection d'une des autocomplétion
	$(document).on("click", `.${id}-auto-complete`, function() {
    input.value = $(this).text();
    showList.innerHTML = "";
  });
}

// function recherche_commune_depot(id){
  
//     $("#commune_depot_"+id).keyup(function () {
//     var query = $(this).val();
//     if (query != "") {
//       $.ajax({
//         url: "https://archeohandi.huma-num.fr/public/fonction/action.php",
//         method: "POST",
//         data: {query:query},
//         success: function (data) { $("#show-list-depot_"+id).html(data); }
//       });
//     } else {
//       $("#show-list-depot_"+id).html("");
//     }
//   });


//   $(document).on("click", "a", function () {
//     $("#commune_depot_"+id).val($(this).text());
//     $("#show-list-depot_"+id).html("");
//   });
// }

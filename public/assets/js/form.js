/*
Contient des scripts d'édition de formulaire.
*/

/** @type {Map<string, int>} Contient les dernier numéro des champs de persones créé. */
var lastPersonNum = new Map();

/** @type {HTMLElement[]} Contient tous les champs d'autocomplétion. */
var autocompleteField = [];

/**
 * Ajoute dans la BDD une personne ayant
 * comme valeurs les champs des inputs indiqués.
 * 
 * @param {string} idFirstNameInput 
 * @param {string} idLastNameInput 
 */
function addPersonDB(idFirstNameInput, idLastNameInput) {
	const firstNameInput = document.getElementById(idFirstNameInput);
	const lastNameInput = document.getElementById(idLastNameInput);
	/** @type {string} */
	let firstName = firstNameInput.value;
	/** @type {string} */
	let lastName = lastNameInput.value;

	$.ajax({
		url: "https://archeohandi.huma-num.fr/public/fonction/add_person.php",
		method: "POST",
		data: {
			first_name: firstName,
			last_name: lastName
		},
		success: function (data) {
			if (data == true) console.log("Personne ajouté !");
			else console.warn("Echec d'ajout de la personne...");
		}
	});
}

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
	addAutocomplete(`${id}_${num}`, "personne");
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
 * Active les checkbox de localisation lors du clic sur le switch du diagnostic concerné.
 * 
 * @param {int} idDiagnosis Id du diagnostic.
 */
function updateCheckboxOnSwitch(idDiagnosis) {
	let switchElem = document.getElementById(`form_diagnostic_${idDiagnosis}`);
	let checkboxs = document.getElementsByName(`diagnostics[${idDiagnosis}][]`);

	switchElem.onclick = function () {
		if (switchElem.checked) {
			// Sélection du diagnostic, activation des checkbox
			for (const checkbox of checkboxs) {
				if (!checkbox.classList.contains("always-disabled")) checkbox.disabled = false;
				if (checkbox.classList.contains("auto-check")) checkbox.checked = true;
			}
		}
		else {
			// Déselection du diagnostic, délection des checkbox
			for (const checkbox of checkboxs) {
				checkbox.disabled = true;
				checkbox.checked = false;
			}
		}
	};
}

/**
 * Réactive tous les champs disabled pour permettre l'envoie des données via POST ou GET.
 */
function prepareFormSend() {
	let allDisabled = document.getElementsByClassName("always-disabled");
	for (const disabled of allDisabled) {
		disabled.disabled = false;
	}
}

/**
 * Ajoute l'autocomplétion à l'input donné en utilisant la base de données.
 * 
 * @param {string} id Identifiant de l'input
 * @param {string} type Type de recherche.
 * Type possible : commune, personne
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
	autocompleteField.push(showList);

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

/** Vide toutes les listes d'autocomplétion. */
function emptyAutocompletes() {
	for (const autocomp of autocompleteField) {
		autocomp.innerHTML = "";
	}
}

// Prépare le document pour pouvoir fermer les autocomplétions automatiquement.
$(document).ready(function () {
	$(document).on("click", function () { emptyAutocompletes(); });
	$(document).on("select", function () { emptyAutocompletes(); });
});


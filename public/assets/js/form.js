/*
Contient des scripts d'édition de formulaire.
*/

/** @type {Map<string, int>} Contient les dernier numéro des champs de persones créé. */
var lastPersonNum = new Map();

/** @type {HTMLElement[]} Contient tous les champs d'autocomplétion. */
var autocompleteField = [];

//#region CopyInput
/** @type {Map<string, number>} Contient le dernier numéro ajouté pour chaque nom. */
var numCounter = new Map();
/** @type {Map<string, HTMLElement} Contient les elements original */
var originals = new Map();

/**
 * Récupère la copie originale.
 * @param {string} name
 */
function getOriginalCopy(name) {
	if (!originals.has(name)) {
		origin = document.getElementById(`form_${name}_copy_0`);
		originals.set(name, origin);
	}

	return originals.get(name);
}

/**
 * 
 * @param {string} name 
 * @returns number
 */
function getNumNewCopy(name) {
	if (!numCounter.has(name)) {
		numCounter.set(name, document.getElementById(`form_${name}_parent`).childElementCount);
	} else {
		numCounter.set(name, numCounter.get(name) +1);
	}

	return numCounter.get(name);
}

/**
 * Copy un champ input.
 * @param {string} name Nom de l'input. L'id du parent à copier doit être au format `form_${name}`.
 * @param {string} autoComplete Type de l'autocomplétion à ajouter. Laissez null pour ne pas ajouter d'autocomplétion.
 * @returns {number} Numéro de la nouvelle copy.
 */
function addCopy(name, autoComplete = null) {
	let id = `form_${name}`;
	/** Element original */
	let elem = getOriginalCopy(name);

	/** Nouveau numéro */
	let num = getNumNewCopy(name);

	// Copie
	let copyNode = elem.cloneNode(true);
	const idCopy = `${id}_copy_${num}`;
	copyNode.id = idCopy;
	elem.parentElement.appendChild(copyNode);
	let copy = document.getElementById(idCopy);

	// Modification des champs
	let inputCopy = copy.getElementsByTagName("input")[0];
	inputCopy.id = `${id}_${num}`;
	inputCopy.value = "";
	let labelCopy = copy.getElementsByTagName("label")[0];
	labelCopy.id = `${id}_label_${num}`;
	labelCopy.htmlFor = `${id}_${num}`;
	let btnRemoveCopy = copy.getElementsByTagName("button")[0];
	btnRemoveCopy.onclick = () => { removeCopy(name, num); }

	// Maj autocomplétion
	if (autoComplete !== null) {
		// Suppression ancienne auto-complétion
		let listCopy = copy.getElementsByClassName("list-group");
		if (listCopy !== null) listCopy[0].remove();

		// Ajout nouvelle auto complétion
		addAutocompleteOld(`${id}_${num}`, autoComplete);
	}

	return num;
}

/**
 * Supprime le champ de la liste de champs correspondant au name et au num.
 * 
 * @param {string} name Nom du champ input.
 * @param {number} num Numéro de la copie.
 */
function removeCopy(name, num) {
	const parent = document.getElementById(`form_${name}_parent`);
	const id = `form_${name}_copy_${num}`;
	let elem = document.getElementById(id);
	if (parent.childElementCount > 1) {
		let isOriginal = getOriginalCopy(name).id == id;
		elem.remove();
		// Remplacement de la node original de copie.
		if (isOriginal) {
			originals.set(name, parent.children[0]);
		}
	}
}

/**
 * 
 * @param {string} id id de la balise img.
 * @param {string} value Source cible de l'image.
 */
function changeImgSrc(idInput, idImg) {
	let input = document.getElementById(idInput);
	let img = document.getElementById(idImg);

	if (input.value.startsWith("10.34847")) input.value = "https://api.nakala.fr/data/" + input.value;
	img.src = input.value;
	img.style.display = input.value == "" ? "none" : "block";
}

function addCopyImg(name) {
	let num = addCopy(name);
	let id = `form_${name}`;

	let copy = document.getElementById(`${id}_copy_${num}`);
	let inputCopy = copy.getElementsByTagName("input")[0];
	inputCopy.onkeyup = function () { changeImgSrc(`${id}_${num}`, `${name}_preview_${num}`); }
	let imgCopy = copy.getElementsByTagName("img")[0];
	imgCopy.id = `${name}_preview_${num}`;
	imgCopy.src = "";
	imgCopy.style.display = "none";
}
//#endregion

//#region CheckboxHandler
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
//#endregion

//#region Autocompletion

/**
 * Récupère une liste selon les conditions indiquées, utilisable dans onResult.
 * 
 * @param {string} id Identifiant de l'input
 * @param {string} select Expression de selection.
 * @param {string} table Table cible de recherche.
 * @param {string[][]} where array de condition aux format ["champs", "=", "input"].
 * @param {(result: string) => void} onResult Action a exécuté lors de la réception du résultat.
 */
function autocomplete(id, select, table, where, onResult) {
	$.ajax({
		url: "https://archeohandi.huma-num.fr/public/fonction/autocomplete.php",
		method: "POST",
		data: {
			id: id,
			select: select,
			table: table,
			where: where
		},
		success: onResult
	});
}

/**
 * Ajoute l'autocomplétion pour le champ des communes uniquement.
 */
 function addAutocompleteCommune() {
	const id = "form_commune";
	// Récupération des champ
	let inputCom = document.getElementById(id);
	let inputDep = document.getElementById(`form_departement`);
	if (inputCom === null) {
		console.error(`Le champ ${id} n'existe pas.`);
		return;
	}

	// Création de la zone d'autocomplétion
	// TODO: vérifier qu'un champ n'existe pas déjà
	let showList = document.createElement("div");
	showList.className = "list-group";
	showList.id = `${id}_list`;
	inputCom.parentNode.appendChild(showList);
	autocompleteField.push(showList);

	// Assignation de l'action à faire à chaque modification du champ
	inputCom.onkeyup = function() {
		if (inputCom.value != "") {
			let where = [["nom", "LIKE", `${inputCom.value}%`]];
			if (inputDep.value != "" && inputDep.validity.valid) {
				where.push(["departement", "=", inputDep.value, "and"])
			}
			// Autocompletion
			autocomplete(
				`form_commune`, `CONCAT(nom, ', ', departement)`, `commune`, where,
				(data) => { showList.innerHTML = data; }
			);
    }
		else {
			// Rien d'entré...
      showList.innerHTML = "";
    }
	}

	// Ajout action en cas de sélection d'une des autocomplétion
	$(document).on("click", `.${id}-auto-complete`, function() {
		/** @type {string} */
		const txt = $(this).text();
		const splited = txt.split(", ");
    inputCom.value = splited[0];
    inputDep.value = splited[1];
    showList.innerHTML = "";
		inputCom.oninput();
		inputDep.oninput();
  });
}

/**
 * Ajoute l'autocomplétion à l'input donné en utilisant la base de données.
 * 
 * @param {string} id Identifiant de l'input
 * @param {string} select Expression de selection.
 * @param {string} table Table cible de recherche.
 * @param {string[][]} where array de condition aux format ["champs", "=", "input", "and"].
 * Les "?" sont remplacé par la valeur du champ input appartenant à l'id.
 */
function addAutocomplete(id, select, table, where) {
	// Récupération du champ
	/** @type {HTMLFormElement} */
	let input = document.getElementById(id);
	if (input === null) {
		console.error(`Le champ ${id} n'existe pas.`);
		return;
	}

	// Création de la zone d'autocomplétion
	// TODO: vérifier qu'un champ n'existe pas déjà
	let showList = document.createElement("div");
	showList.className = "list-group";
	showList.id = `${id}_list`;
	input.parentNode.appendChild(showList);
	autocompleteField.push(showList);

	// Assignation de l'action à faire à chaque modification du champ
	input.onkeyup = function() {
		let currentValue = input.value;

		if (currentValue != "") {

			// Copie du where
			let whereCopy = JSON.parse(JSON.stringify(where));
			for (let i = 0; i<whereCopy.length; i++) {
				whereCopy[i][2] = whereCopy[i][2].replace(/\?/g, currentValue)
			}

			// Autocompletion
			autocomplete(
				id, select, table, whereCopy,
				(data) => { showList.innerHTML = data; }
			);

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
		if (input.oninput !== null) input.oninput();
  });
}

/**
 * Ajoute l'autocomplétion à l'input donné en utilisant la base de données.
 * 
 * @param {string} id Identifiant de l'input
 * @param {string} type Type de recherche.
 * Type possible : commune, compte
 * @deprecated Utilisez addAutocomplete qui est plus générique.
 */
function addAutocompleteOld(id, type) {
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
//#endregion

//#region FieldCheck
/**
 * Vérifie qu'une valeur validant la condition existe dans la BDD.
 * @param {string} table Table dans la BDD où rechercher.
 * @param {string[]} where ["field", "LIKE", "input"] par exemple.
 * @param {() => void} onExist Action à exécuter si une valeur existe.
 * @param {() => void} onNotExist Action à exécuter si aucune valeur n'existe.
 */
function checkValueExist(table, where, onExist, onNotExist) {
	$.ajax({
		method: "POST",
		url: "https://archeohandi.huma-num.fr/public/fonction/check_exist",
		data: {
			table: table,
			where: where
		},
		success: function (response) {
			if (response === "1") onExist();
			else onNotExist();
		}
	});
}
//#endregion

/**
 * Permet d'activer un élément en fonction de l'état du checkbox donné.
 * @param {string} checkboxId Checkbox concerné
 * @param {string} targetId Elément à activer/désactiver
 */
function checkboxActivator(checkboxId, targetId) {
	/** @type {HTMLInputElement} */
	const checkbox = document.getElementById(checkboxId);
	const target = document.getElementById(targetId);

	target.style.display = checkbox.checked ? "block" : "none";
	// console.log(`ouee ! ${checkbox.checked}`);
}

// Prépare le document pour pouvoir fermer les autocomplétions automatiquement.
$(document).ready(function () {
	$(document).on("click", function () { emptyAutocompletes(); });
	$(document).on("select", function () { emptyAutocompletes(); });
});


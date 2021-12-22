/*
Contient des scripts d'édition de formulaire.
*/

/**
 * Ajoute un champ texte pour une liste d'input.
 * 
 * @param {string} name nom de l'attribut "name".
 * @param {string} nom Juste pour avoir un beau label.
 */
function addPerson(name, nom) {
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
function removePerson(name) {
	$('#block_' + name).children().last().remove();
}

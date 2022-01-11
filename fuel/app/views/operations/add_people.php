<?php

use Fuel\Core\Form;
?>
<!--
Popup d'ajout d'une personne dans la BDD.
Les personnes n'étant plus gérer, ce popup est devenu inutile.
-->
<div class="modal" id="addPersonPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPersonPopupLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPersonPopupLabel">Ajouter une personne</h5>
			</div>
			<div class="modal-body">
				<div class="form-floating my-2">
					<?= Form::input("first_name", null, $defaultAttr); ?>
					<?= Form::label("Prénom", "first_name"); ?>
				</div>
				<div class="form-floating my-2">
					<?= Form::input("last_name", null, $defaultAttr); ?>
					<?= Form::label("NOM", "last_name"); ?>
				</div>
				<div class="alert alert-danger text-center my-2" id="alert_add_person" style="visibility: hidden;"></div>
			</div>
			<div class="modal-footer">
				<script>
					/** Ajoute une personne à la BDD. */
					function addPersonDBAction() {
						let alertElem = document.getElementById("alert_add_person");
						addPersonDB(
							`form_first_name`,
							`form_last_name`,
							() => 
								document.getElementById("btn_add_person_back").click(),
							() => {
								alertElem.style.visibility = "visible";
								alertElem.innerHTML = "Un problème est survenu lors de l'ajout de la personne.";
							}
						);
					}

					/** Vide les champs du popup d'ajout de personne. */
					function resetPopup() {
						document.getElementById(`alert_add_person`).style.visibility = `hidden`;
						document.getElementById("form_first_name").value = "";
						document.getElementById("form_last_name").value = "";
					}
				</script>
				<button type="button" id="btn_add_person_back" class="btn btn-secondary"
					data-bs-toggle="modal" data-bs-target="#addPersonPopup" onclick="resetPopup()">
					Retour
				</button>
				<button type="button" class="btn btn-success" onclick="addPersonDBAction();">Ajouter</button>
			</div>
		</div>
	</div>
</div>
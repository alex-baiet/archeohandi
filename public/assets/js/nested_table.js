/** Permet la gestion de tables imbriquées. */
class NestedTable {
	static opened = null;

	/** Chemin de l'icone indiquant qu'un tableau n'est pas affiché. */
	static FOLD_CLOSE_PATH = "/public/assets/img/icon/arrow-right-low.png";
	/** Chemin de l'icone indiquant qu'un tableau est affiché. */
	static FOLD_OPEN_PATH = "/public/assets/img/icon/arrow-down-low.png";

	/**
	 * Affiche/cache la table correspondant en fonction de sa visibilité actuelle.
	 * @param {string} idRow Id de la ligne a afficher/cacher.
	 * @param {string} idBtn Id du bouton cliqué pour changer son icône.
	 */
	static switchTableView(idRow, idBtn) {
		const row = document.getElementById(idRow);
		const btn = document.getElementById(idBtn);
		if (getComputedStyle(row).display === "table-row") {
			// Masquage de la ligne
			row.style.display = "none";
			btn.style.backgroundImage = `url("${this.FOLD_CLOSE_PATH}")`;
		} else {
			row.style.display = "table-row";
			btn.style.backgroundImage = `url("${this.FOLD_OPEN_PATH}")`;
		}
	}

}
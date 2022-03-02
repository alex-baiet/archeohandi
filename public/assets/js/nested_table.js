/** Permet la gestion de tables imbriquées. */
class NestedTable {
	static opened = null;

	/** Affiche/cache la table correspondant en fonction de sa visibilité actuelle. */
	static switchTableView(idRow) {
		const row = document.getElementById(idRow);
		if (getComputedStyle(row).display === "table-row") row.style.display = "none";
		else row.style.display = "table-row";
	}

}
/** Class contenant des fonctions diverses. */
class Helper {

	/**
	 * Export le tableau au format CSV.
	 * @param {string} content Contenu du fichier.
	 * @param {string} title Titre du fichier.
	 */
	static exportCSV(content, title="file.csv") {
		// Création d'une nouvelle page
		let csvFile = new Blob([content], { type: "text/csv" });
		const aLink = document.createElement("a");

		// Création du lien vers la page
		aLink.href = window.URL.createObjectURL(csvFile);
		aLink.download = title;
		aLink.style.display = "none";
		document.body.appendChild(aLink);
		aLink.click();
	}

}

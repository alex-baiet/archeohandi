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

	static romanize(num) {
		var lookup = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},roman = '',i;
		for ( i in lookup ) {
			while ( num >= lookup[i] ) {
				roman += i;
				num -= lookup[i];
			}
		}
		return roman;
	}
}

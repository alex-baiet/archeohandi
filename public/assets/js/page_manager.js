/**
 * Permet de changer de page sans en restant sur la même page web.
 */
class PageManager {
	static _pageIds = [];
	static _btnIds = [];

	/**
	 * Initialize le script en renseignant toutes les informations nécessaires.
	 * @param {string[]} pageIds Liste des id des pages concernées.
	 * @param {string[]} buttonIds Liste des id des boutons concernés.
	 */
	static init(pageIds, buttonIds) {
		this._pageIds = pageIds;
		this._btnIds = buttonIds;
	}

	/**
	 * Change la page à celle indiqué.
	 * @param {string} pageId Id de la page cible.
	 */
	static switchPage(pageId, btnId) {
		// Changement de la page
		for (const id of this._pageIds) {
			const page = document.getElementById(id);
			page.style.display = "none";
		}
		const page = document.getElementById(pageId);
		page.style.display = "block";

		// Changement du bouton
		for (const id of this._btnIds) {
			const but = document.getElementById(id);
			but.classList.remove("active");
		}
		const but = document.getElementById(btnId);
		but.classList.add("active");
	}

}
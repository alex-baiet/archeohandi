class Search {
	static _ids = [];

	/**
	 * 
	 * @param {string[]} pagesId Liste des id des pages. 
	 */
	static addPages(pagesId) {
		for (const id of pagesId) {
			this._ids.push(id);
		}
	}

	/**
	 * Change la page à celle indiqué.
	 * @param {string} pageId Id de la page cible.
	 */
	static switchPage(pageId) {
		for (const id of _ids) {
			const page = document.getElementById(id);
			elem.style.display = "none";
		}
		const page = document.getElementById(pageId);
		page.style.display = "block";
	}
}
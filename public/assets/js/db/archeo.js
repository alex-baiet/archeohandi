class Archeo {
	static STRING=0;
	static NUMBER=1;

	/**
	 * Assigne à la valeur indiqué la valeur de data si elle existe, sinon ne fait rien.
	 * @param {object} data Objet contenant toute les données.
	 * @param {string} key Nom de la donnée à récupérer.
	 * @param {string} destination Nom de la variable recevant la donnée.
	 * @param {int|null} type Type de la valeur.
	 */
	mergeValue(data, key, destination, type=null) {
		if (key in data) {
			switch (type) {
				case null:
					this[destination] = data[key];
					break;
				case Archeo.STRING:
					this[destination] = String(data[key]);
					break;
				case Archeo.NUMBER:
					this[destination] = Number(data[key]);
					break;
				default:
					console.error(`Le type "${type}" n'est pas permis`)
					break;
			}
			this[destination] = data[key];
		}
	}
}

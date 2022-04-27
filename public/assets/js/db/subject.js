class Subject extends Archeo {

	static _fields = [
		"id",
		"id_sujet_handicape",
		"age_min",
		"age_max",
		"age_methode",
		"sexe",
		"sexe_methode",
		"date_min",
		"date_max",
		"milieu_vie",
		"contexte",
		"contexte_normatif",
		"comment_contexte",
		"comment_diagnostic",
		"mobiliers",
		"description_mobilier",
		"type_depot",
		"type_sepulture",
		"num_inventaire",
		"adresse",
		"chronologie",
		"date_ajout",
		"genetique",
		"pathologies_sujet",
		"appareils_compensatoires",
		"diagnostics_sujet",
		"complet",
	];

	id = null;
	id_sujet_handicape = null;
	age_min = null;
	age_max = null;
	age_methode = null;
	sexe = null;
	sexe_methode = null;
	date_min = null;
	date_max = null;
	milieu_vie = null;
	contexte = null;
	contexte_normatif = null;
	comment_contexte = null;
	comment_diagnostic = null;
	mobiliers = null;
	description_mobilier = null;
	id_type_depot = null;
	type_depot = null;
	id_sepulture = null;
	type_sepulture = null;
	id_depot = null;
	adresse = null;
	num_inventaire = null;
	id_groupe = null;
	chronologie = null;
	date_ajout = null;
	genetique = null;
	pathologies_sujet = null;
	appareils_compensatoires = null;
	diagnostics_sujet = null;
	complet = null;

	/**
	 * Construit un sujet handicapé en fonction des données indiqués.
	 * @param {any[]} data 
	 */
	constructor(data) {
		super();

		this.mergeValue(data, "id", "id", Archeo.NUMBER);
		this.mergeValue(data, "id_sujet_handicape", "id_sujet_handicape", Archeo.STRING);
		this.mergeValue(data, "age_min", "age_min", Archeo.NUMBER);
		this.mergeValue(data, "age_max", "age_max", Archeo.NUMBER);
		this.mergeValue(data, "age_methode", "age_methode", Archeo.STRING);
		this.mergeValue(data, "sexe", "sexe", Archeo.NUMBER);
		this.mergeValue(data, "sexe_methode", "sexe_methode", Archeo.STRING);
		this.mergeValue(data, "date_min", "date_min", Archeo.NUMBER);
		this.mergeValue(data, "date_max", "date_max", Archeo.NUMBER);
		this.mergeValue(data, "milieu_vie", "milieu_vie", Archeo.STRING);
		this.mergeValue(data, "contexte", "contexte", Archeo.STRING);
		this.mergeValue(data, "contexte_normatif", "contexte_normatif", Archeo.STRING);
		this.mergeValue(data, "comment_contexte", "comment_contexte", Archeo.STRING);
		this.mergeValue(data, "comment_diagnostic", "comment_diagnostic", Archeo.STRING);
		this.mergeValue(data, "mobiliers", "mobiliers", Archeo.STRING);
		this.mergeValue(data, "description_mobilier", "description_mobilier", Archeo.STRING);
		this.mergeValue(data, "id_type_depot", "id_type_depot", Archeo.NUMBER);
		this.mergeValue(data, "type_depot", "type_depot", Archeo.NUMBER);
		this.mergeValue(data, "id_sepulture", "id_sepulture", Archeo.NUMBER);
		this.mergeValue(data, "type_sepulture", "type_sepulture", Archeo.NUMBER);
		this.mergeValue(data, "id_depot", "id_depot", Archeo.NUMBER);
		this.mergeValue(data, "adresse", "adresse", Archeo.NUMBER);
		this.mergeValue(data, "num_inventaire", "num_inventaire", Archeo.NUMBER);
		this.mergeValue(data, "id_groupe", "id_groupe", Archeo.NUMBER);
		this.mergeValue(data, "chronologie", "chronologie", Archeo.STRING);
		this.mergeValue(data, "date_ajout", "date_ajout", Archeo.STRING);
		this.mergeValue(data, "genetique", "genetique", Archeo.STRING);
		this.mergeValue(data, "pathologies_sujet", "pathologies_sujet", Archeo.STRING);
		this.mergeValue(data, "appareils_compensatoires", "appareils_compensatoires", Archeo.STRING);
		this.mergeValue(data, "diagnostics_sujet", "diagnostics_sujet", Archeo.STRING);
		this.mergeValue(data, "complet", "complet", Archeo.NUMBER);
	}

	/** Renvoie la liste des champs ordonnés disponible. */
	static getFields() { return [...this._fields]; }

}
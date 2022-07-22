/**
 * Génère des graph en fonction des données de sujets récupérés.
 * Les données de sujets doivent être dans un élément #data.
 * Le div du graph doit avoir comme id #container.
 */
class Charts {
	/** @type {Map<number, SearchResult>|null} Toutes les données des opérations et sujets. */
	static _data = null;

	/** Créer un graph sur le les pathologies des sujets. */
	static accessoryGraph() {
		const idList = this._gatherSubjectIds();

		DB.query(
			// Requete récupérant le nombre de sujet par pathologie
			`SELECT mobilier.nom, COUNT(acc.id_sujet) AS count
			FROM mobilier
			JOIN accessoire_sujet AS acc ON acc.id_mobilier = mobilier.id
			WHERE acc.id_sujet IN (${idList.join(', ')})
			GROUP BY mobilier.nom
			ORDER BY mobilier.nom
			;`,
			function (json) {

				// Mise en forme des données à passer à Hichcharts
				const categories = [];
				const content = [];

				for (const value of json) {
					categories.push(value.nom);
					content.push(Number(value.count) / idList.length * 100);
				}

				// Création graph
				Highcharts.chart('container', {
					chart: { type: 'column' },
					title: { text: "Taux de sujets accompagnés d'un accessoire" },
					xAxis: {
						categories: categories,
						crosshair: true
					},
					yAxis: {
						min: 0,
						title: { text: "Taux de sujets avec un accessoire (%)" }
					},
					legend: { enabled: false },
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><br>',
						pointFormat: '<span style="color:{series.color};">{series.name} :</span> <b>{point.y:.1f}%</b>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [{
						name: 'Taux',
						data: content
					}]
				});
			}
		);
	}

	/** Créer un camembert sur le champ "contexte" des sujets. */
	static contextGraph() {
		this._generatePie(
			"Répartition des contextes",
			function (subject) { return subject.contexte; }
		);
	}

	/** Créer un camembert sur le champ "contexte normatif" des sujets. */
	static contextPrescriptiveGraph() {
		this._generatePie(
			"Répartition des contextes normatif",
			function (subject) { return subject.contexte_normatif; }
		);
	}

	/** Créer un graph à colonnes sur la datation des sujets. */
	static dateGraph() {
		const data = this._loadData();
		const dates = new Map();

		// Compte des sujets par siècle
		for (const [id, res] of data) {
			for (const subject of res.subjects) {
				// Calcul de l'année
				let year = null;
				if (subject.date_min === null && subject.date_max === null) continue;
				if (subject.date_min === null) year = Number(subject.date_max);
				else if (subject.date_max === null) year = Number(subject.date_min);
				else year = Number(subject.date_min) + Number(subject.date_max);

				// Calcul du siècle
				const century = Math.ceil(year / 200);
				if (century <= 0 || century > 21) continue;
				if (!dates.has(century)) dates.set(century, 0);
				dates.set(century, dates.get(century) + 1);
			}
		}

		// Définition des siècles min et max à afficher
		let minCentury = 21;
		let maxCentury = 1;
		for (const century of dates.keys()) {
			if (century < minCentury) minCentury = century;
			if (century > maxCentury) maxCentury = century;
		}

		// Mise en forme de la donnée pour la passer a Highcharts
		const centuries = [];
		const content = [];
		for (let century = minCentury; century <= maxCentury; century++) {
			centuries.push(Helper.romanize(century));
			if (dates.has(century)) content.push(dates.get(century));
			else content.push(0);
		}

		// Initialisation du graph
		Highcharts.chart('container', {
			chart: { type: 'column' },
			title: { text: 'Nombre de sujets par siècle' },
			// subtitle: { text: 'Source: WorldClimate.com' },
			xAxis: {
				categories: centuries,
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: { text: 'Nombre de sujet' }
			},
			legend: { enabled: false },
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}e siècle</span><br>',
				pointFormat: '<span style="color:{series.color};">{series.name} :</span> <b>{point.y:.0f}</b>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [{
				name: 'Nombre de sujet',
				data: content
			}]
		});
	}

	/** Créer un graph de répartition des diagnostics des sujets. */
	static diagnosticGraph() {
		const data = this._loadData();

		// Récupération id de tous les sujets
		const idList = [];
		for (const [idOp, res] of data) {
			for (const subject of res.subjects) {
				idList.push(subject.id);
			}
		}

		DB.query(
			// Requete récupérant le nombre de sujet par diagnostic
			`SELECT diagnostic.nom, COUNT(DISTINCT sujet_handicape.id) AS count
			FROM diagnostic
			JOIN localisation_sujet ON localisation_sujet.id_diagnostic = diagnostic.id
			JOIN sujet_handicape ON localisation_sujet.id_sujet = sujet_handicape.id
			WHERE sujet_handicape.id IN (${idList.join(',')})
			GROUP BY diagnostic.nom;`,
			function (json) {

				// Mise en forme de la donnée pour Highcharts
				const categories = [];
				const content = [];
				for (const value of json) {
					categories.push(value.nom);
					content.push(Number(value.count) / idList.length * 100);
				}

				// Affichage du graph
				Highcharts.chart('container', {
					chart: { type: 'column' },
					title: { text: 'Taux de sujets handicapés par diagnostics' },
					// subtitle: { text: 'Source: WorldClimate.com' },
					xAxis: {
						categories: categories,
						crosshair: true
					},
					yAxis: {
						min: 0,
						title: { text: 'Taux de sujet atteints (%)' }
					},
					legend: { enabled: false },
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><br>',
						pointFormat: '<span style="color:{series.color};">{series.name} :</span> <b>{point.y:.1f}%</b>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [{
						name: 'Taux',
						data: content
					}]
				});
			}
		);
	}

	/** Créer un camembert sur le champ "milieu vie" des sujets. */
	static environmentLifeGraph() {
		this._generatePie(
			"Répartition des milieux de vie",
			function (subject) { return subject.milieu_vie; }
		);
	}

	/** Créer un graph sur le les pathologies des sujets. */
	static pathologyGraph() {
		const idList = this._gatherSubjectIds();

		DB.query(
			// Requete récupérant le nombre de sujet par pathologie
			`SELECT pathologie.nom, COUNT(DISTINCT sujet.id) AS count
			FROM pathologie
			JOIN atteinte_pathologie AS ap ON ap.id_pathologie = pathologie.id
			JOIN sujet_handicape AS sujet ON ap.id_sujet = sujet.id
			WHERE sujet.id IN (${idList.join(',')})
			GROUP BY pathologie.nom
			ORDER BY pathologie.nom;`,
			function (json) {

				// Mise en forme des données à passer à Hichcharts
				const categories = [];
				const content = [];

				for (const value of json) {
					categories.push(value.nom);
					content.push(Number(value.count) / idList.length * 100);
				}

				// Création graph
				Highcharts.chart('container', {
					chart: { type: 'column' },
					title: { text: 'Taux de sujets malades par pathologie' },
					xAxis: {
						categories: categories,
						crosshair: true
					},
					yAxis: {
						min: 0,
						title: { text: 'Taux de sujet malades (%)' }
					},
					legend: { enabled: false },
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><br>',
						pointFormat: '<span style="color:{series.color};">{series.name} :</span> <b>{point.y:.1f}%</b>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [{
						name: 'Taux',
						data: content
					}]
				});
			}
		);
	}

	/** Créer un camembert sur la chronologie des sujets. */
	static periodGraph() {
		this._generatePie(
			"Répartition des sujets par période",
			function (subject) { return subject.chronologie; }
		);
	}

	/** Créer un camembert sur la chronologie par groupe de sujets. */
	static periodGroupGraph() {
		const data = this._loadData();
		
		// Récupération id de tous les sujets
		const idList = [];
		for (const [idOp, res] of data) {
			for (const subject of res.subjects) {
				idList.push(subject.id);
			}
		}

		DB.query(
			`SELECT DISTINCT chronologie.nom, groupe.id_operation, groupe.nmi
			FROM groupe
			JOIN chronologie
			ON chronologie.id = groupe.id_chronologie
			JOIN sujet_handicape AS sujet ON groupe.id = sujet.id_groupe
			WHERE sujet.id IN (${idList.join(',')})
			;`,
			json => {
				// Récupération des données et compte des données
				const contentMap = new Map();
				for (const item of json) {
					const data = item["nom"];
					// Comptage de la donnée
					if (!contentMap.has(data)) contentMap.set(data, 0);
					contentMap.set(data, contentMap.get(data) + 1);
				}

				// Mise en forme données pour Highcharts
				const content = [];
				for (const [name, count] of contentMap) {
					content.push({ name: name, y: count });
				}

				// Affichage du highchart
				Highcharts.chart('container', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: "Répartition des groupes de sujets par période"
					},
					tooltip: {
						pointFormat: '{series.name} : <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
								distance: -50,
								filter: {
									property: 'percentage',
									operator: '>',
									value: 4
								}
							}
						}
					},
					series: [{
						name: ' Taux',
						data: content
					}]
				});
			}
		)

	}

	/** Créer un camembert sur le sexe des sujets. */
	static sexGraph() {
		this._generatePie(
			"Répartition des sexes",
			function (subject) { return subject.sexe; }
		);
	}

	/** Créer un camembert sur le champ "type_depot" des sujets. */
	static typeDepotGraph() {
		this._generatePie(
			"Répartition des types des dépôts",
			function (subject) { return subject.type_depot; }
		);
	}

	/** Créer un camembert sur le champ "type_sepulture" des sujets. */
	static typeSepultureGraph() {
		this._generatePie(
			"Répartition des types des sépultures",
			function (subject) { return subject.type_sepulture; }
		);
	}

	/**
	 * Créer un camembert selon les données des sujets de la recherches.
	 * @param {string} title Titre du graph
	 * @param {(subject: Subject) => any} dataHandler Fonction renvoyant une donnée du sujet
	 */
	static _generatePie(title, dataHandler) {
		const data = this._loadData();

		// Récupération des données et compte des données
		const contentMap = new Map();
		for (const [id, res] of data) {
			for (const subject of res.subjects) {
				const data = dataHandler(subject);
				if (data === null) continue;
				// Comptage de la donnée
				if (!contentMap.has(data)) contentMap.set(data, 0);
				contentMap.set(data, contentMap.get(data) + 1);
			}
		}

		// Mise en forme données pour Highcharts
		const content = [];
		for (const [name, count] of contentMap) {
			content.push({ name: name, y: count });
		}

		// Affichage du highchart
		Highcharts.chart('container', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: title
			},
			tooltip: {
				pointFormat: '{series.name} : <b>{point.percentage:.1f}%</b>'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
						distance: -50,
						filter: {
							property: 'percentage',
							operator: '>',
							value: 4
						}
					}
				}
			},
			series: [{
				name: ' Taux',
				data: content
			}]
		});
	}

	/** Charge les données contenu dans la div #data. */
	static _loadData() {
		if (this._data === null) {
			Search.setDataId("data");
			this._data = Search.loadData();
		}
		return this._data;
	}

	/**
	 * Renvoie les ids de tous les sujets.
	 * @returns {number[]}
	 */
	static _gatherSubjectIds() {
		const data = this._loadData();

		// Récupération id de tous les sujets
		const idList = [];
		for (const [idOp, res] of data) {
			for (const subject of res.subjects) {
				idList.push(subject.id);
			}
		}

		return idList;
	}
}
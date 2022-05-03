class Charts {
	/** @type {Map<number, SearchResult>|null} Toutes les données des opérations et sujets. */
	static _data = null;

	static contextGraph() {
		this._generatePie(
			"Répartition des contextes",
			function (subject) { return subject.contexte; }
		);
	}

	static contextPrescriptiveGraph() {
		this._generatePie(
			"Répartition des contextes normatif",
			function (subject) { return subject.contexte_normatif; }
		);
	}

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

		// Mise en forme de la donnée
		const centuries = [];
		const content = [];
		let minCentury = 21;
		let maxCentury = 1;
		for (const century of dates.keys()) {
			// Définition des siècles min et max
			if (century < minCentury) minCentury = century;
			if (century > maxCentury) maxCentury = century;
		}

		for (let century = minCentury; century <= maxCentury; century++) {
			centuries.push(Helper.romanize(century));
			if (dates.has(century)) content.push(dates.get(century));
			else content.push(0);
		}

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
				headerFormat: '<span style="font-size:10px">{point.key}e siècle</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} : </td>' +
					'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
				footerFormat: '</table>',
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

	static diagnosticGraph() {
		const data = this._loadData();

		const idList = [];
		for (const [idOp, res] of data) {
			for (const subject of res.subjects) {
				idList.push(subject.id);
			}
		}

		DB.query(
			`SELECT diagnostic.nom, COUNT(DISTINCT sujet_handicape.id) AS count
			FROM diagnostic
			JOIN localisation_sujet ON localisation_sujet.id_diagnostic = diagnostic.id
			JOIN sujet_handicape ON localisation_sujet.id_sujet = sujet_handicape.id
			WHERE sujet_handicape.id IN (${idList.join(',')})
			GROUP BY diagnostic.nom;`,
			function (json) {
				const categories = [];
				const content = [];
				for (const value of json) {
					categories.push(value.nom);
					content.push(Number(value.count) / idList.length * 100);
				}

				// Affichage du highchart
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
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} : </td>' +
							'<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
						footerFormat: '</table>',
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

	static environmentLifeGraph() {
		this._generatePie(
			"Répartition des milieux de vie",
			function (subject) { return subject.milieu_vie; }
		);
	}

	static pathologyGraph() {
		const data = this._loadData();
		
		const idList = [];
		for (const [idOp, res] of data) {
			for (const subject of res.subjects) {
				idList.push(subject.id);
			}
		}

		DB.query(
			`SELECT pathologie.nom, COUNT(DISTINCT sujet.id) AS count
			FROM pathologie
			JOIN atteinte_pathologie AS ap ON ap.id_pathologie = pathologie.id
			JOIN sujet_handicape AS sujet ON ap.id_sujet = sujet.id
			WHERE sujet.id IN (${idList.join(',')})
			GROUP BY pathologie.nom
			ORDER BY pathologie.nom;`,
			function (json) {
				const categories = [];
				const content = [];
				
				for (const value of json) {
					categories.push(value.nom);
					content.push(Number(value.count) / idList.length * 100);
				}

				Highcharts.chart('container', {
					chart: { type: 'column' },
					title: { text: 'Taux de sujets malades par pathologie' },
					// subtitle: { text: 'Source: WorldClimate.com' },
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
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name} : </td>' +
							'<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
						footerFormat: '</table>',
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

	static periodGraph() {
		this._generatePie(
			"Répartition des sujets par période",
			function (subject) { return subject.chronologie; }
		);
	}

	static sexGraph() {
		this._generatePie(
			"Répartition des sexes",
			function (subject) { return subject.sexe; }
		);
	}

	static typeDepotGraph() {
		this._generatePie(
			"Répartition des types des dépôts",
			function (subject) { return subject.type_depot; }
		);
	}

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

		const contentMap = new Map();
		for (const [id, res] of data) {
			for (const subject of res.subjects) {
				const data = dataHandler(subject);
				if (data === null) continue;
				if (!contentMap.has(data)) contentMap.set(data, 0);
				contentMap.set(data, contentMap.get(data) + 1);
			}
		}

		const content = [];
		for (const [name, count] of contentMap) {
			content.push({ name: name, y: count});
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

	static _loadData() {
		Search.setDataId("data");
		if (this._data === null) this._data = Search.loadData();
		return this._data;
	}
}
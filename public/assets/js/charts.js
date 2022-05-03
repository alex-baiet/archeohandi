class Charts {
	/** @type {Map<number, SearchResult>|null} Toutes les données des opérations et sujets. */
	static _data = null;

	static contextGraph() {
		this._generatePie(
			"Répartition des sexes",
			function (subject) { return subject.contexte; }
		);
	}

	static contextPrescriptiveGraph() {
		this._generatePie(
			"Répartition des sexes",
			function (subject) { return subject.contexte_normatif; }
		);
	}

	static dateGraph() {
		const data = this._loadData();
		const dates = new Map();

		// Compte des sujets par siècle
		for (const [id, res] of data) {
			for (const subject of res.subjects) {
				// console.log(`${subject.date_min} ${subject.date_max}`);
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

		console.log(minCentury);
		console.log(maxCentury);
		for (let century = minCentury; century <= maxCentury; century++) {
			centuries.push(Helper.romanize(century));
			if (dates.has(century)) content.push(dates.get(century));
			else content.push(0);
		}
		console.log(dates);
		console.log(content);

		Highcharts.chart('container', {
			chart: { type: 'column' },
			title: { text: 'Sujets par siècle' },
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
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
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
				const content = []
				for (const value of json) {
					content.push({ name: value["nom"], y: Number(value["count"]) });
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
						text: 'Diagnostics des sujets'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
						name: 'Ratio',
						data: content
					}]
				});
			}
		);
	}

	static environmentLifeGraph() {
		this._generatePie(
			"Répartition des sexes",
			function (subject) { return subject.milieu_vie; }
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
			"Répartition des sexes",
			function (subject) { return subject.type_depot; }
		);
	}

	static typeSepultureGraph() {
		this._generatePie(
			"Répartition des sexes",
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
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
				name: 'Ratio',
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
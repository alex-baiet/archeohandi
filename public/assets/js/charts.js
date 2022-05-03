class Charts {
	/** @type {Map<number, SearchResult>|null} Toutes les données des opérations et sujets. */
	static _data = null;

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

	static sexGraph() {
		const data = this._loadData();

		content = [
			{ name: "Homme", y: 0 },
			{ name: "Femme", y: 0 },
			{ name: "Indéterminé", y: 0 },
		];
		for (const [id, res] of data) {
			for (const subject of res.subjects) {
				switch (subject.sexe) {
					case "Homme":
						content[0].y++;
						break;
					case "Femme":
						content[1].y++;
						break;
					default:
						content[2].y++;
						break;
				}
			}
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
				text: 'Répartition des sexes'
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
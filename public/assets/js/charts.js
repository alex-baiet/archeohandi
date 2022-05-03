class Charts {
	static diagnosticPie() {
		Highcharts.chart('container', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Browser market shares at a specific website, 2014'
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
				name: 'Share',
				data: [
					{ name: 'Chrome', y: 61.41 },
					{ name: 'Internet Explorer', y: 11.84 },
					{ name: 'Firefox', y: 10.85 },
					{ name: 'Edge', y: 4.67 },
					{ name: 'Safari', y: 4.18 },
					{ name: 'Other', y: 7.05 }
				]
			}]
		});
	}
}
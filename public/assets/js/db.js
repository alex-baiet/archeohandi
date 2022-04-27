class DB {
	/**
	 * Execute la requete SQL
	 * @param {string} sql Requête SQL
	 * @param {([]) => {}} onSuccess A exécuter lors de la réception du résultat
	 */
	static query(sql, onSuccess) {
		$.ajax({
			type: "POST",
			url: "https://archeohandi.huma-num.fr/public/fonction/query",
			data: { query: sql },
			success: function (response) {
				console.log(response)
				const json = JSON.parse(response);
				onSuccess(json);
			}
		});
	}

}
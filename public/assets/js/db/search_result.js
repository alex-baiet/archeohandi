class SearchResult {
	/** @type {Operation} */
	operation = null;
	/** @type {Map<number, any[]>} */
	subjects = [];

	constructor(operation, subjects) {
		this.operation = operation;
		this.subjects = subjects;
	}
}
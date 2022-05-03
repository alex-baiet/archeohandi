class SearchResult {
	/** @type {Operation} */
	operation = null;
	/** @type {Subject[]} */
	subjects = [];

	constructor(operation, subjects) {
		this.operation = operation;
		this.subjects = subjects;
	}
}
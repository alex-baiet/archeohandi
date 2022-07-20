/** Script pour la page "autre/referents" uniquement. */
const MAP_WIDTH = 1939;
const MAP_HEIGHT = 1806;

/** Change la taille de la carte pour entrer dans son parent. */
function resizeMap() {
	const map = document.getElementById(`map_ref`);
	const mapParent = document.getElementById(`map_container`);
	const parWidth = mapParent.clientWidth;
	const parHeight = mapParent.clientHeight;

	// Zone très large : pas de redimensionnement
	if (parWidth > MAP_WIDTH && parHeight > MAP_HEIGHT) return;

	if (parWidth / MAP_WIDTH < parHeight / MAP_HEIGHT) {
		// Redimensionnement selon la largeur
		map.style.transform = `translate(-50%, -50%) scale(${parWidth/MAP_WIDTH*100}%)`;
	} else {
		// Redimensionnement selon la hauteur
		map.style.transform = `translate(-50%, -50%) scale(${parHeight/MAP_HEIGHT*100}%)`;
	}
}

window.onresize = () => resizeMap();
window.onload = () => resizeMap();

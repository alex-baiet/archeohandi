
/** Permet de faciliter l'utilisation de fonction concernant "window". */
class Window {
  static _onload = []

  /**
   * Ajoute une fonction a exécuter lorsque que la page est prête.
   * @param {function()} fun
   */
  static addOnLoad(fun) {
    this._onload.push(fun)
  }
}

window.onload = function () {
  for (const fun of Window._onload) {
    fun()
  }
}
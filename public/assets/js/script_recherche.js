// Ancient code inutilisé

$(document).ready(function (id) {

  $("#commune").keyup(function () {
    var query = $(this).val();
    if (query != "") {
      $.ajax({
        url: "https://archeohandi.huma-num.fr/public/fonction/action.php",
        method: "POST",
        data: {query:query},
        success: function (data) { $("#show-list").html(data); }
      });
    } else {
      $("#show-list").html("");
    }
  });


  $(document).on("click", "a", function () {
    $("#commune").val($(this).text());
    $("#show-list").html("");
  });
});


function recherche_commune_depot(id){
  
    $("#commune_depot_"+id).keyup(function () {
    var query = $(this).val();
    if (query != "") {
      $.ajax({
        url: "https://archeohandi.huma-num.fr/public/fonction/action.php",
        method: "POST",
        data: {query:query},
        success: function (data) { $("#show-list-depot_"+id).html(data); }
      });
    } else {
      $("#show-list-depot_"+id).html("");
    }
  });


  $(document).on("click", "a", function () {
    $("#commune_depot_"+id).val($(this).text());
    $("#show-list-depot_"+id).html("");
  });
}
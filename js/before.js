function loginbarrier() {

    var alert = confirm("U moet ingelogd zijn om deze pagina weer te geven");

    if (alert) {

        location.href = "index.php?page=login";

    } else {

        location.href = "index.php?page=login";

    }

}

function goto(url) {

    location.href = url;

}

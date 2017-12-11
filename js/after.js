(function menu_highlight() {

    url = window.location.href;

    url = url.substr(url.indexOf('=') + 1, url.length);

    element = document.getElementById(url);

    if (typeof (Storage) !== "undefined") {

        console.log("localStorage/sessionStorage Support");

        if (element) {

            element = document.getElementById(url);

            sessionStorage.pagetitle = url;

            element.setAttribute("class", "active");

        } else {

            element = document.getElementById(sessionStorage.pagetitle);

            if (element) {

            document.getElementById(sessionStorage.pagetitle).setAttribute("class", "active");

            }

        }

    } else {

        console.log("Sorry! No Web Storage support, Menu might not highlight properly...");

        if (element) {

            element = document.getElementById(url);

            element.setAttribute("class", "active");

        }

    }

    console.log(url);

    console.log(sessionStorage.pagetitle);

    document.title = "ProjectSync - " + url;

})();

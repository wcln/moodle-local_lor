

function next(currentPage) {
  navigate(currentPage + 1);
}

function back(currentPage) {
  navigate(currentPage - 1);
}

function navigate(page) {
  var loc = window.location;
  if (loc.search === "") {
    window.location = loc.protocol + '//' + loc.host + loc.pathname + "?page=" + page;
  } else {
    window.location = (loc.protocol + '//' + loc.host + loc.pathname + loc.search).replace(/&page=[0-9]+/, "") + "&page=" + page;
  }

}

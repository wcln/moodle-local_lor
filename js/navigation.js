

function next(currentPage) {
  navigate(currentPage + 1);
}

function back(currentPage) {
  navigate(currentPage - 1);
}

function navigate(page) {
  var loc = window.location;
  console.log(loc.pathname);
  if (loc.search === "") {
    window.location = loc.protocol + '//' + loc.host + loc.pathname + "?page=" + page;
  } else {
    // Check if URL includes an ID. If so, remove it before navigating to prevent re-opening a "linked to" LOR item.
    window.location = (loc.protocol + '//' + loc.host + loc.pathname + loc.search.replace(/id=([^&]+)/, "")).replace(/&page=[0-9]+/, "") + "&page=" + page;
  }
}

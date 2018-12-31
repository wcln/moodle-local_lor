

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
    // Check if URL includes a page already.
    if (loc.search.match(/[?&]page=[0-9]+/)) {
      // If it does, replace the page value.
      window.location = (loc.protocol + '//' + loc.host + loc.pathname + loc.search.replace(/id=([^&]+)/, "")).replace(/page=[0-9]+/,  "page=" + page);
    } else {
      // If it does not, append a new page value.
      window.location = (loc.protocol + '//' + loc.host + loc.pathname + loc.search.replace(/id=([^&]+)/, "")) + "&page=" + page;
    }
  }
}

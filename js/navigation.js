function next(currentPage) {
    navigate(currentPage + 1);
}

function back(currentPage) {
    navigate(currentPage - 1);
}

function navigate(page) {

    // Update the page hidden field in the search form.
    $('input[name="page"]').val(page);

    // Submit the search form.
    $("#id_submitbutton").click();

}

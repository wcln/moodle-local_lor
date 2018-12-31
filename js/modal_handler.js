/*
 * Modal handling script.
 */
$( document ).ready(function() {

  // Check if there is an ID to load in the URL.
  var id = /[?&]id=([^&]+)/.exec(window.location.href);

  // If an ID exists, open the corresponding item modal.
  if (typeof id !== 'undefined' && id !== null) {
    loadModal("modals/details.php?id=" + id[1]);
  }

  // Ensure the modal is not showing.
  $('#itemModal').modal({ show: false});

  function loadModal(url) {

    // Add listener to modal to run when it is hidden.
    $('#itemModal').on('hidden.bs.modal', function () {
      // Empty the modal contents when it is closed.
      $('.lor-modal-body').empty();

      // Add scroll bar to body and remove right margin.
      $('body')
        .css('margin-right', '')
        .removeClass('no-scroll');
    });

    // Add listeners to modal which run when it is shown.
    $('#itemModal').on('shown', function () {
      // Ensure the modal opens with the content scrolled to the top.
      $(".lor-modal").scrollTop(0);
    });

    // Load the modal content.
    $('.lor-modal-content').load(url);

    // Remove scroll bar from body and add right margin for smoother opening/closing.
    var scrollBarWidth = window.innerWidth - document.body.offsetWidth;
    $('body')
      .css('margin-right', scrollBarWidth)
      .addClass('no-scroll');

    // Show the modal.
    $('#itemModal').modal({
      show: true,
      backdrop: true,
      keyboard: true
    });
  }

  // When a modal launcher is clicked.
  $('.modallink').click(function(e) {
   e.preventDefault(); // Prevent loading the href URL.
   // Call a function the load the modal content given the <a> href attribute.
   loadModal(e.currentTarget.href);
  });

  // Logic to handle clicking on modal backdrop to close modal.
  $(document).mouseup(function(e) {
      var container = $(".lor-modal-dialog");

      // If the target of the click isn't the container nor a descendant of the container.
      if (!container.is(e.target) && container.has(e.target).length === 0) {
          $('.modal-backdrop').click();
      }
  });
});

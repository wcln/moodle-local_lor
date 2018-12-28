/*
 * Modal handling script.
 */
 $(document).ready(function() {
   $('#itemModal').modal({ show: false});

   function loadModal(url) {

     // Add listener to empty modal contents when it is closed.
     $('#itemModal').on('hidden.bs.modal', function () {
      $('.lor-modal-body').empty();
    });

    // Load the modal content.
    $('.lor-modal-content').load(url);

    // Show the modal.
    // $('#itemModal').modal('show');
   }

   // When a modal launcher is clicked.
   $('.modallink').click(function(e) { loadModal(e.currentTarget.href); });
 });


  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-hapus-level");
    const modalHapusLevel = new bootstrap.Modal(document.getElementById("modalHapusLevel"));
    const namaLevelHapus = document.getElementById("namaLevelHapus");
    const formHapusLevel = document.getElementById("formHapusLevel");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const levelId = this.getAttribute("data-id");
            const levelNama = this.getAttribute("data-nama");

            namaLevelHapus.textContent = levelNama;
            formHapusLevel.action = `/admin/levels/${levelId}`;

            modalHapusLevel.show();
        });
    });
});

</script>
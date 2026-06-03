// Interaksi global: loading state, konfirmasi hapus, lightbox galeri, dan search realtime.
document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.querySelector(".loading-overlay");

  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", () => overlay?.classList.add("show"));
  });

  document.querySelectorAll("[data-confirm]").forEach((button) => {
    button.addEventListener("click", (event) => {
      if (!confirm(button.dataset.confirm || "Yakin ingin melanjutkan?")) {
        event.preventDefault();
      }
    });
  });

  document.querySelectorAll("[data-lightbox]").forEach((item) => {
    item.addEventListener("click", () => {
      const modalImage = document.getElementById("lightboxImage");
      if (modalImage) {
        modalImage.src = item.dataset.lightbox;
        bootstrap.Modal.getOrCreateInstance(document.getElementById("lightboxModal")).show();
      }
    });
  });

  const searchInput = document.querySelector("[data-table-search]");
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const value = searchInput.value.toLowerCase();
      document.querySelectorAll(searchInput.dataset.tableSearch + " tbody tr").forEach((row) => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
      });
    });
  }
});

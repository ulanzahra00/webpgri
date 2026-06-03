// Interaksi global: loading state, konfirmasi hapus, lightbox galeri, dan search realtime.
document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.querySelector(".loading-overlay");

  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", (event) => {
      const oversizedFile = Array.from(form.querySelectorAll("input[type='file'][data-max-size]")).find((input) => {
        const file = input.files?.[0];
        return file && file.size > Number(input.dataset.maxSize || 0);
      });

      if (oversizedFile) {
        event.preventDefault();
        const maxMegabytes = Math.ceil(Number(oversizedFile.dataset.maxSize || 0) / 1024 / 1024);
        alert(`Ukuran file terlalu besar. Maksimal ${maxMegabytes}MB.`);
        oversizedFile.focus();
        return;
      }

      overlay?.classList.add("show");
    });
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

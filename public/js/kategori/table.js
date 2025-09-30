document.addEventListener("DOMContentLoaded", function () {
  // Kelas untuk sorting tabel kategori
  class KategoriTableSorter {
    constructor() {
      this.table = document.getElementById("category-table");
      if (!this.table) return;

      this.tableBody = this.table.querySelector("tbody");
      this.headers = this.table.querySelectorAll("thead th[data-column]");
      this.currentSort = { column: null, direction: "asc" };

      this.init();
    }

    init() {
      this.headers.forEach((header) => {
        header.addEventListener("click", () => {
          const column = header.dataset.column;
          const direction =
            this.currentSort.column === column &&
            this.currentSort.direction === "asc"
              ? "desc"
              : "asc";
          this.sortRows(column, direction);
        });
      });
    }

    sortRows(column, direction) {
      this.currentSort = { column, direction };
      const allRows = Array.from(this.tableBody.querySelectorAll("tr"));

      allRows.sort((rowA, rowB) => {
        // Untuk halaman ini, semua perbandingan adalah teks biasa
        const valA = this.getCellValue(rowA, column).toLowerCase();
        const valB = this.getCellValue(rowB, column).toLowerCase();

        // localeCompare adalah cara terbaik untuk membandingkan string
        if (direction === "asc") {
          return valA.localeCompare(valB);
        } else {
          return valB.localeCompare(valA);
        }
      });

      this.tableBody.innerHTML = "";
      allRows.forEach((row) => this.tableBody.appendChild(row));

      // Perbarui nomor urut kolom '#' setelah sorting
      this.updateRowNumbers();

      this.updateHeaderUI();
      this.tableBody.dispatchEvent(
        new CustomEvent("tableSorted", { bubbles: true })
      );
    }

    // Dapatkan teks dari sel
    getCellValue(row, column) {
      const columnIndex =
        Array.from(this.headers).findIndex((h) => h.dataset.column === column) +
        1; // +1 karena ada kolom '#'
      const cell = row.children[columnIndex];
      return cell ? cell.textContent.trim() : "";
    }

    // Perbarui nomor di kolom pertama
    updateRowNumbers() {
      const allRows = this.tableBody.querySelectorAll("tr");
      allRows.forEach((row, index) => {
        row.firstElementChild.textContent = index + 1;
      });
    }

    updateHeaderUI() {
      this.headers.forEach((header) => {
        const icon = header.querySelector(".sort-icon");
        if (icon) icon.remove();
        if (header.dataset.column === this.currentSort.column) {
          const newIcon = document.createElement("span");
          newIcon.className = "sort-icon";
          newIcon.innerHTML =
            this.currentSort.direction === "asc" ? " &utrif;" : " &dtrif;";
          header.appendChild(newIcon);
        }
      });
    }
  }

  new KategoriTableSorter();
});

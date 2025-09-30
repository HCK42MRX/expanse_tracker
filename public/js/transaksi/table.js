document.addEventListener("DOMContentLoaded", function () {
  // Kelas ini khusus untuk mengelola sorting tabel
  class TableSorter {
    constructor() {
      this.table = document.querySelector(".table");
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
        const valA = this.getCellValue(rowA, column);
        const valB = this.getCellValue(rowB, column);

        if (valA < valB) return direction === "asc" ? -1 : 1;
        if (valA > valB) return direction === "asc" ? 1 : -1;
        return 0;
      });

      // Susun ulang baris di dalam tbody
      this.tableBody.innerHTML = "";
      allRows.forEach((row) => this.tableBody.appendChild(row));

      this.updateHeaderUI();

      // PENTING: Kirim "sinyal" bahwa tabel telah diurutkan
      // Event ini akan didengarkan oleh pagination.js
      this.tableBody.dispatchEvent(
        new CustomEvent("tableSorted", { bubbles: true })
      );
    }

    getCellValue(row, column) {
      const columnIndex = Array.from(this.headers).findIndex(
        (h) => h.dataset.column === column
      );
      const cell = row.children[columnIndex];
      if (!cell) return "";

      const text = cell.textContent.trim();
      switch (column) {
        case "tanggal":
          return new Date(text);
        case "jumlah":
          return parseFloat(text.replace(/[+Rp.\s]/g, ""));
        default:
          return text.toLowerCase();
      }
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

  new TableSorter();
});

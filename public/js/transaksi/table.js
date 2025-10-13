document.addEventListener("DOMContentLoaded", function () {
  class TableManager {
    constructor() {
      this.table = document.querySelector(".table");
      if (!this.table) return;

      this.tableBody = this.table.querySelector("tbody");
      this.headers = this.table.querySelectorAll("thead th[data-column]");
      this.searchInput = document.getElementById("searchInput");

      // Simpan semua baris asli sekali saja untuk performa
      this.originalRows = Array.from(this.tableBody.querySelectorAll("tr"));

      this.currentSort = { column: null, direction: "desc" };

      this.init();
    }

    init() {
      // Tampilkan panah default saat halaman dimuat
      this.updateHeaderUI();

      // Listener untuk sorting
      this.headers.forEach((header) => {
        header.addEventListener("click", () => {
          const column = header.dataset.column;
          let direction = "desc";

          if (
            this.currentSort.column === column &&
            this.currentSort.direction === "desc"
          ) {
            direction = "asc";
          }
          this.currentSort = { column, direction };
          this.updateTableDisplay();
        });
      });

      // Listener untuk search
      this.searchInput.addEventListener("input", () => {
        this.updateTableDisplay();
      });
    }

    updateTableDisplay() {
      // 1. Dapatkan kata kunci pencarian
      const searchTerm = this.searchInput.value.toLowerCase().trim();

      // 2. Filter baris berdasarkan pencarian
      const filteredRows = this.originalRows.filter((row) => {
        const rowText = row.textContent.toLowerCase();
        return rowText.includes(searchTerm);
      });

      // 3. Urutkan baris yang sudah difilter
      const sortedRows = this.sortRows(filteredRows);

      // 4. Tampilkan baris yang sudah difilter dan diurutkan
      this.tableBody.innerHTML = ""; // Kosongkan tabel
      if (sortedRows.length === 0) {
        this.showNoResultsMessage();
      } else {
        sortedRows.forEach((row) => this.tableBody.appendChild(row));
      }

      // 5. Perbarui UI header (panah)
      this.updateHeaderUI();

      // 6. Beri tahu pagination bahwa isi tabel telah berubah
      this.tableBody.dispatchEvent(
        new CustomEvent("tableSorted", { bubbles: true })
      );
    }

    sortRows(rows) {
      // Jika tidak ada kolom yang di-sort, kembalikan baris apa adanya
      if (!this.currentSort.column) {
        return rows;
      }

      const { column, direction } = this.currentSort;

      return rows.sort((rowA, rowB) => {
        const valA = this.getCellValue(rowA, column);
        const valB = this.getCellValue(rowB, column);

        if (valA < valB) return direction === "asc" ? -1 : 1;
        if (valA > valB) return direction === "asc" ? 1 : -1;
        return 0;
      });
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
          return parseFloat(text.replace(/[+Rp.,\s]/g, ""));
        default:
          return text.toLowerCase();
      }
    }

    updateHeaderUI() {
      this.headers.forEach((header) => {
        const existingIcon = header.querySelector(".sort-icon");
        if (existingIcon) existingIcon.remove();

        const newIcon = document.createElement("span");
        newIcon.className = "sort-icon";

        if (header.dataset.column === this.currentSort.column) {
          newIcon.innerHTML =
            this.currentSort.direction === "desc" ? " &dtrif;" : " &utrif;";
          newIcon.classList.add("active");
        } else {
          newIcon.innerHTML = " &dtrif;";
          newIcon.classList.remove("active");
        }
        header.appendChild(newIcon);
      });
    }

    showNoResultsMessage() {
      const columnCount = this.table.querySelector("thead tr").children.length;
      this.tableBody.innerHTML = `
            <tr>
                <td colspan="${columnCount}" class="text-center text-muted py-4">
                    Tidak ada data yang cocok dengan pencarian Anda.
                </td>
            </tr>
        `;
    }
  }

  new TableManager();
});

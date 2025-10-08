document.addEventListener("DOMContentLoaded", function () {
  // Kelas untuk mengelola pagination pada tabel transaksi
  class TransaksiPagination {
    constructor() {
      // Pengaturan dasar pagination
      this.currentPage = 1;
      this.rowsPerPage = 10; // Ubah angka ini jika ingin menampilkan jumlah data yang berbeda

      // Seleksi elemen DOM yang relevan
      this.tableBody = document.getElementById("transaction-table-body");
      if (!this.tableBody) {
        console.error(
          "Elemen dengan ID 'transaction-table-body' tidak ditemukan."
        );
        return;
      }

      this.rows = Array.from(this.tableBody.querySelectorAll("tr"));
      this.totalPages = Math.ceil(this.rows.length / this.rowsPerPage);
      this.paginationWrapper = document.getElementById("pagination-wrapper");

      // Inisialisasi
      this.init();
    }

    // Metode inisialisasi utama
    init() {
      if (this.rows.length > 0) {
        this.createPaginationControls();
        this.showPage(1);
      } else {
        // Sembunyikan wrapper jika tidak ada data
        if (this.paginationWrapper)
          this.paginationWrapper.style.display = "none";
      }
    }

    // Menampilkan baris untuk halaman tertentu
    showPage(page) {
      this.currentPage = page;
      const startIndex = (this.currentPage - 1) * this.rowsPerPage;
      const endIndex = startIndex + this.rowsPerPage;

      // Sembunyikan semua baris, lalu tampilkan yang sesuai halaman ini
      this.rows.forEach((row, index) => {
        row.style.display =
          index >= startIndex && index < endIndex ? "" : "none";
      });

      this.updatePaginationUI();
    }

    // Membuat elemen kontrol pagination (tombol Previous, Next, dan info halaman)
    createPaginationControls() {
      // Kosongkan wrapper sebelum membuat kontrol baru
      this.paginationWrapper.innerHTML = "";

      // Kontainer untuk info halaman (misal: "Menampilkan 1-5 dari 20 data")
      const pageInfo = document.createElement("div");
      pageInfo.className = "page-info text-muted me-auto"; // <--- UBAH JADI SEPERTI INI
      this.pageInfo = pageInfo;

      // Navigasi untuk tombol
      const nav = document.createElement("nav");
      const ul = document.createElement("ul");
      ul.className = "pagination mb-0";

      // Tombol "Previous"
      const prevLi = document.createElement("li");
      prevLi.className = "page-item";
      prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;

      // Tombol "Next"
      const nextLi = document.createElement("li");
      nextLi.className = "page-item";
      nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;

      // Tampilan nomor halaman (misal: "1 / 4")
      const pageNumberLi = document.createElement("li");
      pageNumberLi.className = "page-item";
      pageNumberLi.innerHTML = `<span class="page-link" style="background-color: #f8f9fa; border-color: #dee2e6;">
                                         <span class="current-page-display">1</span> / <span class="total-pages-display">${this.totalPages}</span>
                                      </span>`;

      // Tambahkan semua elemen ke dalam list
      ul.appendChild(prevLi);
      ul.appendChild(pageNumberLi);
      ul.appendChild(nextLi);
      nav.appendChild(ul);

      // Tambahkan info dan navigasi ke wrapper utama
      this.paginationWrapper.appendChild(this.pageInfo);
      this.paginationWrapper.appendChild(nav);

      // Simpan referensi tombol untuk event listener dan update UI
      this.prevButton = prevLi;
      this.nextButton = nextLi;
      this.currentPageDisplay = this.paginationWrapper.querySelector(
        ".current-page-display"
      );

      // Tambahkan event listener ke tombol
      this.addEventListeners();
    }

    // Memperbarui tampilan UI pagination (status tombol dan teks info)
    updatePaginationUI() {
      if (!this.paginationWrapper || this.rows.length === 0) return;

      // Update status tombol disabled
      this.prevButton.classList.toggle("disabled", this.currentPage === 1);
      this.nextButton.classList.toggle(
        "disabled",
        this.currentPage === this.totalPages
      );

      // Update nomor halaman yang ditampilkan
      this.currentPageDisplay.textContent = this.currentPage;

      // Update teks info halaman
      const start = (this.currentPage - 1) * this.rowsPerPage + 1;
      const end = Math.min(
        this.currentPage * this.rowsPerPage,
        this.rows.length
      );
      this.pageInfo.innerHTML = `Menampilkan <strong>${start}-${end}</strong> dari <strong>${this.rows.length}</strong> data`;
    }

    // Menambahkan event listener untuk tombol Previous dan Next
    addEventListeners() {
      this.prevButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.currentPage > 1) {
          this.showPage(this.currentPage - 1);
        }
      });

      this.nextButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.currentPage < this.totalPages) {
          this.showPage(this.currentPage + 1);
        }
      });
    }
  }

  // Inisialisasi pagination setelah halaman dimuat
  // Timeout kecil untuk memastikan semua elemen tabel sudah dirender oleh browser
  setTimeout(() => {
    new TransaksiPagination();
  }, 100);
});

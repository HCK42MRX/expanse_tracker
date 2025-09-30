// Pagination untuk tabel kategori
class KategoriPagination {
  constructor() {
    this.currentPage = 1;
    this.rowsPerPage = 5; // Jumlah baris per halaman
    this.table = document.querySelector(".table tbody");
    this.rows = Array.from(this.table.querySelectorAll("tr"));
    this.totalPages = Math.ceil(this.rows.length / this.rowsPerPage);

    this.init();
  }

  init() {
    this.createPaginationControls();
    this.showPage(1);
  }

  showPage(page) {
    this.currentPage = page;

    // Sembunyikan semua baris
    this.rows.forEach((row) => {
      row.style.display = "none";
    });

    // Tampilkan baris untuk halaman saat ini
    const start = (page - 1) * this.rowsPerPage;
    const end = start + this.rowsPerPage;

    for (let i = start; i < end && i < this.rows.length; i++) {
      this.rows[i].style.display = "";
    }

    // Update nomor urut
    this.updateRowNumbers();

    // Update tampilan pagination
    this.updatePagination();
  }

  updateRowNumbers() {
    const startNumber = (this.currentPage - 1) * this.rowsPerPage + 1;
    const visibleRows = this.rows.filter((row) => row.style.display !== "none");

    visibleRows.forEach((row, index) => {
      const th = row.querySelector("th");
      if (th) {
        th.textContent = startNumber + index;
      }
    });
  }

  createPaginationControls() {
    const cardBody = document.querySelector(".col-md-8 .card-body");

    // Cek apakah pagination sudah ada
    let paginationContainer = cardBody.querySelector(".pagination-container");

    if (!paginationContainer) {
      paginationContainer = document.createElement("div");
      paginationContainer.className =
        "pagination-container d-flex justify-content-between align-items-center mt-3";
      cardBody.appendChild(paginationContainer);
    }

    // Info halaman
    const pageInfo = document.createElement("div");
    pageInfo.className = "page-info";
    pageInfo.innerHTML = `Menampilkan <strong>${this.rowsPerPage}</strong> data per halaman`;

    // Navigation
    const paginationNav = document.createElement("nav");
    paginationNav.setAttribute("aria-label", "Page navigation");

    const paginationList = document.createElement("ul");
    paginationList.className = "pagination mb-0";

    // Previous button
    const prevLi = document.createElement("li");
    prevLi.className = "page-item";
    prevLi.innerHTML = `
            <a class="page-link prev-btn" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        `;

    // Next button
    const nextLi = document.createElement("li");
    nextLi.className = "page-item";
    nextLi.innerHTML = `
            <a class="page-link next-btn" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        `;

    // Page number container
    const pageNumbersContainer = document.createElement("li");
    pageNumbersContainer.className = "page-item";
    pageNumbersContainer.innerHTML = `
            <span class="page-link page-numbers">
                <span class="current-page">1</span> / <span class="total-pages">${this.totalPages}</span>
            </span>
        `;

    paginationList.appendChild(prevLi);
    paginationList.appendChild(pageNumbersContainer);
    paginationList.appendChild(nextLi);
    paginationNav.appendChild(paginationList);

    paginationContainer.innerHTML = "";
    paginationContainer.appendChild(pageInfo);
    paginationContainer.appendChild(paginationNav);

    // Event listeners
    this.addEventListeners();
  }

  updatePagination() {
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    const currentPageEl = document.querySelector(".current-page");
    const totalPagesEl = document.querySelector(".total-pages");

    // Update page numbers
    currentPageEl.textContent = this.currentPage;
    totalPagesEl.textContent = this.totalPages;

    // Update button states
    prevBtn.parentElement.classList.toggle("disabled", this.currentPage === 1);
    nextBtn.parentElement.classList.toggle(
      "disabled",
      this.currentPage === this.totalPages
    );

    // Update info
    const pageInfo = document.querySelector(".page-info");
    const start = (this.currentPage - 1) * this.rowsPerPage + 1;
    const end = Math.min(this.currentPage * this.rowsPerPage, this.rows.length);
    pageInfo.innerHTML = `Menampilkan data <strong>${start}-${end}</strong> dari <strong>${this.rows.length}</strong> total data`;
  }

  addEventListeners() {
    // Previous button
    document.querySelector(".prev-btn").addEventListener("click", (e) => {
      e.preventDefault();
      if (this.currentPage > 1) {
        this.showPage(this.currentPage - 1);
      }
    });

    // Next button
    document.querySelector(".next-btn").addEventListener("click", (e) => {
      e.preventDefault();
      if (this.currentPage < this.totalPages) {
        this.showPage(this.currentPage + 1);
      }
    });
  }
}

// Custom styling untuk pagination
const paginationStyles = `
    .pagination-container {
        border-top: 1px solid #dee2e6;
        padding-top: 1rem;
    }
    .page-info {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .pagination .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }
    .pagination .page-numbers {
        background-color: #fff;
        border-color: #dee2e6;
    }
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
`;

// Tambahkan styles ke document
const styleSheet = document.createElement("style");
styleSheet.textContent = paginationStyles;
document.head.appendChild(styleSheet);

// Inisialisasi pagination ketika DOM siap
document.addEventListener("DOMContentLoaded", function () {
  // Tunggu sebentar untuk memastikan tabel sudah ter-render
  setTimeout(() => {
    new KategoriPagination();
  }, 100);
});

// Optional: Function untuk update pagination jika data berubah
function refreshPagination() {
  setTimeout(() => {
    new KategoriPagination();
  }, 100);
}

// Jika menggunakan AJAX untuk menambah/menghapus data, panggil refreshPagination()

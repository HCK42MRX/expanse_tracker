// Menunggu hingga seluruh konten halaman HTML dimuat sebelum menjalankan script
document.addEventListener("DOMContentLoaded", function () {
  // ================= KONFIGURASI =================
  const rowsPerPage = 5; // Atur berapa baris data yang ingin ditampilkan per halaman

  // ================= SELEKSI ELEMEN DOM =================
  // Memilih body tabel dimana semua baris (tr) transaksi berada
  const tableBody = document.getElementById("transaction-table-body");
  // Memilih semua elemen baris (tr) yang ada di dalam tableBody
  const allRows = tableBody.querySelectorAll("tr");
  // Memilih div kosong yang akan kita isi dengan tombol pagination
  const paginationWrapper = document.getElementById("pagination-wrapper");
  // Menghitung jumlah total halaman yang dibutuhkan
  const pageCount = Math.ceil(allRows.length / rowsPerPage);
  // Menentukan halaman saat ini, defaultnya adalah halaman 1
  let currentPage = 1;

  /**
   * Fungsi untuk membuat dan menampilkan tombol-tombol pagination.
   */
  function setupPagination() {
    // Kosongkan wrapper pagination sebelum membuat yang baru
    paginationWrapper.innerHTML = "";

    // Buat elemen nav dan ul untuk membungkus tombol pagination (mengikuti struktur Bootstrap)
    const nav = document.createElement("nav");
    const ul = document.createElement("ul");
    ul.classList.add("pagination");

    // Buat tombol untuk setiap halaman
    for (let i = 1; i <= pageCount; i++) {
      const li = document.createElement("li");
      li.classList.add("page-item");

      // Tandai tombol halaman pertama sebagai 'active'
      if (i === currentPage) {
        li.classList.add("active");
      }

      const a = document.createElement("a");
      a.classList.add("page-link");
      a.href = "#";
      a.innerText = i;

      // Tambahkan event listener untuk setiap tombol halaman
      a.addEventListener("click", function (event) {
        // Mencegah browser melakukan reload halaman
        event.preventDefault();
        // Ubah halaman saat ini ke nomor yang diklik
        currentPage = i;
        // Tampilkan baris yang sesuai dengan halaman baru
        displayRowsForPage(currentPage);

        // Perbarui status 'active' pada tombol pagination
        updateActivePaginationButton();
      });

      li.appendChild(a);
      ul.appendChild(li);
    }

    nav.appendChild(ul);
    // Tambahkan navigasi pagination yang sudah jadi ke dalam wrapper
    paginationWrapper.appendChild(nav);
  }

  /**
   * Fungsi untuk memperbarui tombol mana yang memiliki kelas 'active'.
   */
  function updateActivePaginationButton() {
    // Dapatkan semua tombol pagination
    const pageItems = paginationWrapper.querySelectorAll(".page-item");
    pageItems.forEach((item, index) => {
      // Hapus kelas 'active' dari semua tombol
      item.classList.remove("active");
      // Tambahkan kelas 'active' hanya pada tombol yang sesuai dengan halaman saat ini
      if (index + 1 === currentPage) {
        item.classList.add("active");
      }
    });
  }

  /**
   * Fungsi untuk menampilkan baris data sesuai dengan halaman yang dipilih.
   * @param {number} page - Nomor halaman yang ingin ditampilkan.
   */
  function displayRowsForPage(page) {
    // Hitung indeks awal dan akhir baris yang akan ditampilkan
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;

    // Loop melalui semua baris data
    allRows.forEach((row, index) => {
      // Sembunyikan semua baris terlebih dahulu
      row.style.display = "none";
      // Tampilkan hanya baris yang berada dalam rentang indeks untuk halaman saat ini
      if (index >= startIndex && index < endIndex) {
        row.style.display = ""; // Mengembalikan ke display default (biasanya 'table-row')
      }
    });
  }

  // ================= INISIALISASI =================
  // Hanya jalankan pagination jika ada data di tabel
  if (allRows.length > 0) {
    setupPagination(); // Buat tombol pagination
    displayRowsForPage(1); // Tampilkan data untuk halaman pertama
  }
});

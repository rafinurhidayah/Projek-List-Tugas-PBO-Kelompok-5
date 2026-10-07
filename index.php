<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <header>
      <h1>Page Title</h1>
      <nav aria-label="Main navigation">
        <ul>
          <li><a href="#main">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </header>

    <main id="main">
      <section id="about">
        <h2>About</h2>
        <p>Welcome to this page.</p>
      </section>
    </main>
    <form action="actions/proses.php" method="POST">
      <!-- Input untuk kolom 'judul' (varchar) -->
      <div class="mb-3">
        <label class="form-label fw-semibold text-secondary">Judul Tugas</label>
        <input
          type="text"
          name="judul"
          class="form-control rounded-pill px-3"
          required
          placeholder="Contoh: Membuat Makalah"
        />
      </div>

      <!-- Input untuk kolom 'jenis' (enum: 'kuliah', 'pribadi') -->
      <div class="mb-3">
        <label class="form-label fw-semibold text-secondary">Jenis Tugas</label>
        <select name="jenis" class="form-select rounded-pill px-3" required>
          <option value="kuliah">Tugas Kuliah</option>
          <option value="pribadi">Tugas Pribadi</option>
        </select>
      </div>

      <!-- Input untuk kolom 'prioritas' (enum: 'penting', 'normal') -->
      <div class="mb-3">
        <label class="form-label fw-semibold text-secondary">Prioritas</label>
        <select name="prioritas" class="form-select rounded-pill px-3" required>
          <option value="normal">Normal</option>
          <option value="penting">Penting 🚨</option>
        </select>
      </div>

      <!-- Input untuk kolom 'detail_tambahan' (varchar) -->
      <div class="mb-4">
        <label class="form-label fw-semibold text-secondary"
          >Detail Tambahan (Matkul / Kategori)</label
        >
        <input
          type="text"
          name="detail_tambahan"
          class="form-control rounded-pill px-3"
          required
          placeholder="Contoh: PBO"
        />
      </div>

      <button
        type="submit"
        name="tambah"
        class="btn btn-dark w-100 rounded-pill fw-bold py-2 shadow-sm"
      >
        Simpan Tugas
      </button>
    </form>

    <footer id="contact">
      <p>&copy; 2025</p>
    </footer>
  </body>
</html>

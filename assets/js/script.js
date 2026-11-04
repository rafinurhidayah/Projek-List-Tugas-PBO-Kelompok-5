/* Perilaku halaman: menu HP, form muncul/hilang, filter + pencarian, centang tanpa reload,
   hapus dengan konfirmasi, toast. Semua fitur punya cadangan tanpa JavaScript (form biasa). */
(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  /* ---------- Menu hamburger ---------- */
  const nav = $('.nav'), toggle = $('.nav-toggle');
  const aturMenu = (buka) => {
    nav.classList.toggle('is-buka', buka);
    toggle.setAttribute('aria-expanded', String(buka));
    toggle.setAttribute('aria-label', buka ? 'Tutup menu' : 'Buka menu');
  };
  if (toggle) {
    toggle.addEventListener('click', () => aturMenu(!nav.classList.contains('is-buka')));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') aturMenu(false); });
    document.addEventListener('click', (e) => { if (!nav.contains(e.target)) aturMenu(false); });
  }

  /* ---------- Toast ---------- */
  const area = $('#toast');
  const toast = (pesan, tipe = 'sukses') => {
    if (!area || !pesan) return;
    const el = document.createElement('div');
    el.className = 'toast' + (tipe === 'error' ? ' toast-error' : '');
    el.textContent = pesan;
    area.append(el);
    setTimeout(() => { el.classList.add('keluar'); setTimeout(() => el.remove(), 300); }, 3600);
  };
  toast(document.body.dataset.flashPesan, document.body.dataset.flashTipe);

  const daftar = $('#daftar');
  const modal = $('#modal-tugas');
  if (!modal) return; // halaman beranda berhenti di sini

  /* ---------- Form tambah tugas: muncul & hilang ---------- */
  const form = $('#form-tugas');
  const judul = $('[name=judul]', form);
  const detail = $('[name=detail_tambahan]', form);
  const hitung = () => { $('#hitung').textContent = `${detail.value.length}/255`; };
  const buka = () => {
    if (modal.open) return;
    form.reset(); hitung();
    $('button[type=submit]', form).disabled = false;
    modal.showModal();
    judul.focus();
  };
  $$('[data-buka-modal], .nav-cta').forEach((el) => el.addEventListener('click', (e) => { e.preventDefault(); aturMenu(false); buka(); }));
  $$('[data-tutup]', modal).forEach((el) => el.addEventListener('click', () => modal.close()));
  let mulaiKlik = null;                                   // klik di luar kotak = tutup (kecuali sedang menyeleksi teks)
  modal.addEventListener('mousedown', (e) => { mulaiKlik = e.target; });
  modal.addEventListener('click', (e) => { if (e.target === modal && mulaiKlik === modal) modal.close(); });
  detail.addEventListener('input', hitung);
  judul.addEventListener('input', () => judul.setCustomValidity(judul.value.trim() ? '' : 'Judul tidak boleh kosong.'));
  form.addEventListener('submit', () => {                 // cegah klik ganda
    const tombol = $('button[type=submit]', form);
    tombol.disabled = true; tombol.textContent = 'Menyimpan…';
  });
  if (location.hash === '#buat') {                        // datang dari tombol "Buat Tugas" di beranda
    history.replaceState(null, '', location.pathname + location.search);
    buka();
  }

  if (!daftar) return; // database gagal: tidak ada daftar yang perlu diatur

  /* ---------- Filter jenis + pencarian ---------- */
  const semuaKartu = () => $$('.kartu', daftar);
  const chips = $$('.chip');
  const cari = $('#cari');
  let jenis = 'semua';

  const terapkan = () => {
    const kata = cari.value.trim().toLowerCase();
    let tampil = 0;
    semuaKartu().forEach((k) => {
      const cocok = (jenis === 'semua' || k.dataset.jenis === jenis) && (!kata || k.textContent.toLowerCase().includes(kata));
      k.hidden = !cocok;
      if (cocok) tampil++;
    });
    $('#kosong-filter').hidden = tampil > 0 || semuaKartu().length === 0;
  };
  const perbarui = () => {                                // hitungan chip + ringkasan selalu mengikuti DOM
    const kartu = semuaKartu();
    const sisa = kartu.filter((k) => k.dataset.selesai === '0').length;
    $('#ringkasan').textContent = kartu.length ? `${sisa} dari ${kartu.length} tugas belum selesai` : '';
    chips.forEach((c) => {
      const f = c.dataset.filter;
      $('b', c).textContent = f === 'semua' ? kartu.length : kartu.filter((k) => k.dataset.jenis === f).length;
    });
    $('#kosong').hidden = kartu.length > 0;
  };
  chips.forEach((c) => c.addEventListener('click', () => {
    jenis = c.dataset.filter;
    chips.forEach((x) => x.setAttribute('aria-pressed', String(x === c)));
    terapkan();
  }));
  cari.addEventListener('input', terapkan);
  document.addEventListener('keydown', (e) => {           // pintasan: "/" cari, "n" tugas baru
    if (e.metaKey || e.ctrlKey || e.altKey || /^(INPUT|TEXTAREA)$/.test(document.activeElement.tagName) || document.querySelector('dialog[open]')) return;
    if (e.key === '/') { e.preventDefault(); cari.focus(); }
    if (e.key === 'n') { e.preventDefault(); buka(); }
  });

  /* ---------- Dialog konfirmasi hapus ---------- */
  const dlg = $('#modal-konfirmasi');
  const konfirmasi = (teks) => new Promise((selesai) => {
    $('#teks-konfirmasi').textContent = teks;
    dlg.returnValue = '';
    dlg.addEventListener('close', () => selesai(dlg.returnValue === 'ya'), { once: true });
    dlg.showModal();
  });

  /* ---------- Centang & hapus tanpa reload halaman ---------- */
  const ubahStatusKartu = (kartu, form) => {
    const selesai = form.elements.status.value === '1';
    kartu.dataset.selesai = selesai ? '1' : '0';
    kartu.classList.toggle('is-selesai', selesai);
    $('.cek', kartu).setAttribute('aria-pressed', String(selesai));
    const badge = $('.lencana-status', kartu);
    badge.textContent = selesai ? 'Sudah' : 'Belum';
    badge.classList.toggle('lencana-sudah', selesai);
    badge.classList.toggle('lencana-belum', !selesai);
    form.elements.status.value = selesai ? '0' : '1';
    // tugas selesai turun ke bawah, yang dibuka kembali naik ke atas daftar selesai
    const pertamaSelesai = semuaKartu().find((k) => k !== kartu && k.dataset.selesai === '1');
    if (selesai) daftar.append(kartu); else daftar.insertBefore(kartu, pertamaSelesai || null);
  };

  daftar.addEventListener('submit', async (e) => {
    const f = e.target.closest('form[data-ajax]');
    if (!f) return;
    e.preventDefault();
    const kartu = f.closest('.kartu');
    const aksi = f.dataset.ajax;
    if (aksi === 'hapus' && !(await konfirmasi(f.dataset.konfirmasi))) return;

    kartu.classList.add('is-sibuk');
    try {
      const res = await fetch(f.action, { method: 'POST', body: new FormData(f), headers: { Accept: 'application/json' } });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.pesan || 'Permintaan gagal.');
      if (aksi === 'status') {
        ubahStatusKartu(kartu, f);
      } else {
        kartu.classList.add('keluar');
        setTimeout(() => { kartu.remove(); perbarui(); terapkan(); }, 250);
        toast(data.pesan);
      }
      perbarui();
    } catch (err) {
      toast(err instanceof TypeError ? 'Koneksi bermasalah. Coba lagi.' : err.message, 'error');
    } finally {
      kartu.classList.remove('is-sibuk');
    }
  });

  perbarui();
})();

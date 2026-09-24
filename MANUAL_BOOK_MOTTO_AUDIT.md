# Manual Book & Panduan Penggunaan System MOTTO Audit

Selamat datang di **Manual Book Application MOTTO Audit**. Dokumen panduan ini disusun khusus untuk membantu pengguna (baik **Auditor / Instruktur Genba** maupun **Admin QA**) dalam memahami, mengoperasikan, dan mengelola seluruh fitur pada aplikasi **MOTTO Audit**.

---

## 1. Pendahuluan

### 1.1 Tujuan Sistem
Aplikasi **MOTTO Audit** diciptakan sebagai sistem digitalisasi dan standarisasi proses pemeriksaan/audit operasional di lapangan (genba). Sistem ini dirancang untuk:
* Mempermudah pencatatan dan evaluasi kepatuhan terhadap standar **5S (Seiri, Seiton, Seiso, Seiketsu, Shitsuke)**, **Change Point Management**, dan **License System**.
* Menjamin bahwa setiap pemeriksaan dilakukan berdasarkan **Checkpoint** dan **Kriteria Judgement** yang terukur.
* Menyediakan bukti digital berupa **foto temuan** dan catatan langsung dari lapangan saat ditemukan kondisi ketidaksesuaian (**NG**).
* Menyajikan ringkasan statistik kepatuhan secara *real-time* serta rekapitulasi histori audit yang dapat diunduh ke format **Microsoft Excel**.

### 1.2 Siapa Pengguna Sistem?
Sistem MOTTO Audit memiliki 2 peran (*role*) utama pengguna:

1. **Auditor / Instruktur Genba (User Lapangan)**
   * **Tugas Utama**: Melakukan audit langsung di lapangan sesuai jadwal yang telah dialokasikan oleh Admin, memeriksa checkpoint, menentukan hasil (*Judgement* OK / NG), mengunggah foto bukti jika ditemukan NG, serta melihat dokumen pedoman (SOP).
   * **Akses**: Terbatas pada menu Dashboard, Halaman Audit (5S Standard, Change Point, License System), Pedoman SOP, dan Riwayat Audit milik sendiri.

2. **Admin QA (Super User / Pengelola System)**
   * **Tugas Utama**: Mengatur master data (Jenis Audit, Area, dan Proses Audit), mengelola akun pengguna/auditor, membuat jadwal penugasan audit (baik individual maupun massal/bulk), mengunggah dokumen pedoman SOP resmi (PDF), melihat seluruh riwayat audit seluruh auditor, serta mengunduh laporan ekspor Excel lengkap dengan lampiran foto temuan.
   * **Akses**: Memiliki akses penuh ke seluruh fitur sistem, termasuk menu Manajemen Admin (Jenis Audit, Jadwal, Akun Auditor). Namun, Admin hanya bersifat *View Only* (tidak menginput penilaian audit langsung di lapangan).

---

## 2. Panduan Login & Logout

### 2.1 Akses Halaman Login
1. Buka peramban (*browser*) pada perangkat komputer atau tablet Anda.
2. Masukkan alamat URL aplikasi MOTTO Audit (misal: `http://localhost:8000` atau URL server yang ditentukan). Sistem akan secara otomatis mengarahkan Anda ke halaman login (`/audit/login`).

### 2.2 Cara Login
![Halaman Login System MOTTO Audit](https://via.placeholder.com/600x350?text=Login+Form+MOTTO+Audit)

1. Pada kolom **NIK (Nomor Induk Karyawan)** / Kode Akses:
   * Masukkan NIK resmi Anda (contoh untuk Auditor: `TWI`, `EMU`, `AYP`, `12345`; contoh untuk Admin: NIK khusus Admin).
2. Pada kolom **Password**:
   * Masukkan kata sandi akun Anda.
3. Klik tombol **Masuk ke System**.
4. Jika NIK dan Password sesuai, sistem akan langsung membuka halaman **Dashboard Utama**.

> **Catatan Keamanan**:
> * Jika salah memasukkan NIK atau Password, akan muncul pesan peringatan *"NIK atau password salah"*.
> * Sesi login akan tetap aktif selama browser terbuka.

### 2.3 Cara Logout
1. Klik tombol **Logout** atau ikon keluar yang terletak di bilah navigasi (ujung kanan atas atau menu samping).
2. Sesi login Anda akan dihapus secara aman dan sistem kembali ke layar Login.

---

## 3. Panduan untuk Auditor (Operator Lapangan)

Sebagai Auditor Genba, berikut adalah alur lengkap dan cara menggunakan fitur-fitur audit dari awal hingga selesai.

---

### 3.1 Melihat Jadwal Audit yang Aktif
Agar dapat melakukan pengisian audit, Anda harus memiliki **Jadwal Audit Aktif** yang dibuat oleh Admin pada rentang tanggal hari ini.

1. Buka menu **Dashboard** atau pilih kategori audit di bilah navigasi samping:
   * **5S Standard**: Berisi area-area pemeriksaan 5S (seperti Terminal, Sub Ass'y, Layanan, dsb).
   * **Change Point Management**: Pemeriksaan manajemen perubahan proses/material.
   * **License System**: Pemeriksaan kepemilikan lisensi / kualifikasi operator.
2. Pilih **Area** yang ingin diaudit.
3. Pada halaman area, Anda akan melihat daftar **Process Audit** (Item Check).

---

### 3.2 Memahami Status Tombol & Indikator "Sudah Diaudit"
Pada daftar proses audit, tombol aksi pada setiap kartu proses memiliki 3 kondisi:

| Tampilan Tombol | Status & Arti | Tindakan yang Dapat Dilakukan |
| :--- | :--- | :--- |
| **Proceed to Audit** *(Tombol Merah Aktif)* | **Ready / Aktif** | Klik tombol ini untuk masuk ke form pengisian audit. |
| **Sudah Diaudit** *(Tombol Abu-abu Disabled)* | **Sudah Diaudit** | Anda **sudah pernah mengisi** audit untuk proses ini pada periode jadwal aktif saat ini. Tombol ke-grey-out dan tidak dapat diklik lagi agar tidak terjadi pengisian ganda. |
| **View Only (Admin)** *(Tombol Abu-abu Disabled)* | **Akses Admin** | Akun bertipe Admin hanya dapat melihat daftar proses dan tidak diizinkan mengisi penilaian. |

> **Mengapa Proses Tidak Muncul / Ke-Grey-Out?**
> * **Tidak Muncul**: Berarti Anda belum diberi alokasi jadwal aktif untuk proses tersebut pada hari ini.
> * **Tombol "Sudah Diaudit"**: Berarti laporan audit Anda sudah berhasil disimpan ke sistem.

---

### 3.3 Mengakses Pedoman / Standard Operating Procedure (SOP)
Sebelum mengaudit, Anda dapat mempelajari standar operasional yang berlaku:

1. Klik menu **Pedoman SOP** pada navigasi utama.
2. Pilih area dan nama proses yang ingin Anda lihat.
3. Apabila dokumen telah diunggah oleh Admin, akan muncul tombol **Lihat SOP** (berikon PDF).
4. Klik **Lihat SOP** untuk membuka dan membaca file dokumen PDF panduan resmi langsung di tab baru browser Anda.

---

### 3.4 Mengisi Form Audit (Penilaian Checkpoint & Judgement)
Setelah menekan tombol **Proceed to Audit**, Anda akan masuk ke halaman **Form Audit**.

#### Langkah-langkah Pengisian:
1. **Periksa Informational Header**:
   * Verifikasi Nama Auditor dan Waktu Audit *real-time*.
2. **Baca Panduan Standar Audit Process**:
   * **Checkpoint (Item Yang Diperiksa)**: Penjelasan mengenai benda, area, atau aktivitas fisik yang wajib Anda periksa di lapangan.
   * **Kriteria Audit Judgement (Standar OK / NG)**: Acuan tolok ukur kondisi lulus (OK) vs temuan (NG).
3. **Pilih Penilaian Judgement**:
   * **Tombol OK (LULUS)**:
     * Pilih ini jika kondisi di lapangan **100% sesuai** dengan Kriteria Judgement.
     * Setelah menekan tombol OK, tombol **Simpan Hasil Audit** di bagian bawah akan langsung aktif (berwarna merah).
   * **Tombol NG (TEMUAN)**:
     * Pilih ini jika ditemukan **ketidaksesuaian / pelanggaran** standar di lapangan.
     * Begitu tombol NG dipilih, secara otomatis akan terbuka bagian khusus **Bukti Temuan Kategori NG**.

---

### 3.5 Pengisian Bukti Temuan NG & Batas Upload Foto

Apabila hasil penilaian adalah **NG (Temuan)**, sistem mewajibkan pengisian bukti lapangan dengan aturan ketat sebagai berikut:

#### 1. Upload Foto Temuan (Wajib untuk NG)
* Klik tombol **Choose File** / **Upload Foto Temuan**.
* Ambil foto langsung melalui kamera tablet/smartphone atau pilih file gambar dari galeri Anda.
* **Pratinjau Foto**: Setelah file dipilih, foto temuan akan langsung ditampilkan di layar (*preview*).
* **Ketentuan File Foto**:
  * **Format yang Diizinkan**: JPG, JPEG, PNG, WEBP, GIF.
  * **Batas Maksimum Ukuran File**: **10 Megabyte (10 MB)**. Jika mengunggah file lebih dari 10 MB, sistem akan menolak dan menampilkan pesan error.

#### 2. Catatan & Keterangan Temuan (Wajib untuk NG)
* Ketik penjelasan detail mengenai kondisi temuan di lapangan pada kolom teks **Catatan & Keterangan Temuan**.
* Contoh catatan yang baik: *"Terdapat ceceran oli pada lantai area Sub Ass'y Line 2, belum dibersihkan dan tidak ada tanda peringatan."*

> **Penting (Sistem Pengunci Tombol Simpan)**:
> Jika Anda memilih **NG**, tombol **Simpan Hasil Audit** akan **tetap dalam keadaan mati (disabled / abu-abu)** sampai Anda **melengkapi KEDUA syarat** di atas (mengunggah Foto Temuan DAN mengisi Catatan Temuan).

#### 3. Menyimpan Hasil Audit
* Setelah seluruh data valid, klik **Simpan Hasil Audit**.
* Anda akan diarahkan kembali ke daftar proses dengan pesan sukses, dan status proses tersebut akan berubah menjadi **Sudah Diaudit**.

---

## 4. Panduan untuk Admin QA (Super User)

Admin QA bertugas mengonfigurasi master data, menjadwalkan audit, mengelola akun, hingga menarik laporan audit komprehensif.

---

### 4.1 Kelola Jenis Audit, Area, dan Proses Audit (Master Data)

Fitur ini digunakan untuk menambah atau mengubah struktur daftar pemeriksaan audit.

Akses melalui menu: **Manajemen Admin** > **Jenis Audit**.

#### A. Mengelola Jenis Audit (Audit Type)
1. **Tambah Jenis Audit**:
   * Klik tombol **Tambah Jenis Audit Baru**.
   * Isi **Nama Jenis Audit** (misal: *Audit K3 Genba*).
   * Isi **Slug** (ID unik tanpa spasi, misal: `audit-k3`).
   * Isi Deskripsi ringkas, lalu klik **Simpan**.
2. **Edit / Hapus Jenis Audit**:
   * Klik ikon **Edit** untuk mengubah nama/slug.
   * Klik tombol **Hapus** untuk menghapus. *Catatan: Jenis audit yang masih memiliki area di dalamnya tidak dapat dihapus.*

#### B. Mengelola Area Audit (Audit Area)
1. Masuk ke detail/edit salah satu Jenis Audit.
2. Pada bagian **Daftar Area Audit**, klik **Tambah Area Baru**.
3. Isi **Nama Area** (contoh: *Penyimpanan Terminal 1*), **Deskripsi**, dan ikon SVG jika ada.
4. Klik **Simpan Area**.

#### C. Mengelola Process Audit / Checkpoint (Audit Process)
1. Di bawah area yang bersangkutan, klik **Tambah Process Baru**.
2. Isi formulir data proses:
   * **Nama Proses**: Nama item check (contoh: *Penyimpanan Rak Terminal*).
   * **Deskripsi**: Penjelasan proses.
   * **Checkpoint**: Detail item yang harus diperiksa auditor.
   * **Kriteria Judgement**: Standar kriteria kondisi OK vs NG.
   * **Audit Intention**: Tujuan pelaksanaan audit proses tersebut.
3. Klik **Simpan Process**.

---

### 4.2 Pembuatan Jadwal Audit (Multi-Select / Bulk Penjadwalan)

Fitur ini memungkinkan Admin untuk mengalokasikan tugas audit kepada auditor secara efisien dalam jumlah banyak sekaligus.

Akses melalui menu: **Manajemen Admin** > **Jadwal Audit** > **Buat Jadwal Audit Baru**.

#### Langkah Penjadwalan Multi-Select (Bulk):
1. **Pilih Auditor / Instruktur**:
   * Pilih nama auditor dari menu dropdown.
2. **Pilih Tipe Target Penjadwalan**:
   * **Proses Audit Spesifik** *(Multi-Select)*: Memilih item-item proses tertentu secara independen.
   * **Seluruh Area Audit**: Memilih seluruh proses dalam satu atau beberapa area sekaligus.
3. **Memilih Item Target**:
   * Gunakan kolom **Cari nama proses audit...** untuk memfilter proses secara cepat.
   * Gunakan tombol **Pilih Semua Proses** atau centang opsi **Pilih Semua di Area Ini** pada kelompok area.
   * Perhatikan indikator lencana (*badge*): **"X item dipilih"** akan memperbarui jumlah item yang tercentang secara *real-time*.
4. **Tentukan Periode Tanggal**:
   * Isi **Tanggal Mulai** dan **Tanggal Selesai** berlakunya jadwal audit tersebut.
5. **Simpan Penjadwalan**:
   * Klik tombol **Simpan Jadwal (bisa lebih dari satu)**.

#### Penanganan Bentrok Jadwal (Conflict Validation System):
* Sistem MOTTO Audit secara otomatis akan mengecek kerentanan jadwal (*overlap*).
* Jika auditor yang sama sudah memiliki jadwal aktif pada proses/area tersebut di rentang tanggal yang bersinggungan, sistem akan menolak entri bentrok tersebut dan memberikan notifikasi rincian kegagalan tanpa membatalkan entri lain yang sukses.

---

### 4.3 Kelola Akun Auditor

Admin dapat menambah, mengubah, atau menonaktifkan akses pengguna.

Akses melalui menu: **Manajemen Admin** > **Kelola Auditor**.

1. **Tambah Akun Baru**:
   * Klik **Tambah Auditor Baru**.
   * Isi **NIK** (Nomor Induk Karyawan yang digunakan untuk Login).
   * Isi **Nama Lengkap**.
   * Isi **Password** (minimal 6 karakter).
   * Pilih **Role**:
     * `Admin`: Memiliki hak akses penuh pengelolaan sistem.
     * `Auditor`: Hak akses pengisian audit lapangan.
   * Pilih **Tipe Auditor**:
     * `Auditor` atau `Instruktur`.
   * Klik **Simpan**.
2. **Edit Akun**:
   * Gunakan tombol Edit untuk mengemaskini Nama, Role, Tipe Auditor, atau memperbarui Password (kosongkan jika password tidak ingin diganti).
3. **Hapus Akun**:
   * Klik tombol Hapus. *Sistem akan menolak penghapusan jika akun sedang digunakan untuk login atau masih memiliki riwayat jadwal audit aktif.*

---

### 4.4 Upload Pedoman SOP per Proses (PDF)

Dokumen pedoman SOP membantu auditor di lapangan bekerja sesuai acuan standar kerja resmi.

Akses melalui menu: **Pedoman SOP** (Saat login sebagai Admin).

1. Pada halaman Pedoman SOP, akan muncul penanda: **Mode Super Admin (Akses Edit SOP)**.
2. Cari nama proses audit yang ingin ditambahkan pedomannya.
3. Klik tombol **Upload PDF** (atau **Ganti PDF** jika file sudah ada).
4. Pilih file dokumen **PDF** dari komputer Anda.
   * **Syarat File**: Wajib berformat `.pdf` dengan ukuran maksimal **10 MB**.
5. Setelah file dipilih, sistem akan mengunggah file secara otomatis dan memperbarui status dokumen menjadi **SOP Tersedia**.

---

### 4.5 Export Riwayat Audit ke Microsoft Excel (Lengkap dengan Foto Temuan)

Seluruh histori pelaksanaan audit dapat difilter dan diunduh menjadi berkas Excel (*.xlsx*) profesional.

Akses melalui menu: **Riwayat Audit**.

#### Langkah Export Data:
1. (Opsional) Gunakan filter pencarian di bagian atas tabel:
   * **Kategori Audit**: 5S Standard, Change Point, atau License System.
   * **Area**: Filter berdasarkan area tertentu.
   * **Kondisi Hasil**: OK atau NG.
   * **Rentang Tanggal**: Tanggal Mulai s/d Tanggal Selesai.
2. Klik tombol **Export Excel** (berikon hijau spreadsheet).
3. File Excel dengan nama `Riwayat_Audit_YYYY-MM-DD_HHMMSS.xlsx` akan diunduh secara otomatis.

#### Keunggulan Format Berkas Excel MOTTO Audit:
* **Desain Header Elegan**: Diberi warna tematik merah khas MOTTO dengan teks cetak tebal (*bold*).
* **Kondisi Hasil Visual**: Kolom hasil **OK** berwarna hijau muda, sedangkan **NG** berwarna merah tegas.
* **Embed Foto Temuan Langsung**: Untuk setiap baris temuan NG yang memiliki foto bukti, **gambar foto temuan akan langsung ditempel (*embedded*) secara otomatis di dalam sel kolom K file Excel** dengan penyesuaian tinggi baris secara dinamis.

---

## 5. FAQ / Troubleshooting (Tanya Jawab & Kendala Umum)

Berikut adalah ringkasan solusi atas pertanyaan atau kendala yang sering dijumpai di lapangan berdasarkan aturan sistem:

### Q1: Mengapa tombol pada proses audit berwarna abu-abu dan bertuliskan "Sudah Diaudit"?
> **Jawab**: Artinya Anda telah menyelesaikan pengisian audit untuk item proses tersebut dalam periode jadwal yang berlaku saat ini. Sistem secara otomatis mengunci proses tersebut agar tidak terjadi duplikasi laporan audit pada hari yang sama.

### Q2: Mengapa tombol "Simpan Hasil Audit" pada Form Audit tidak bisa diklik (mati/disabled)?
> **Jawab**:
> 1. Jika Anda memilih hasil **OK**, pastikan Anda telah mengklik tombol OK hingga berwarna hijau aktif.
> 2. Jika Anda memilih hasil **NG**, tombol simpan hanya akan aktif apabila Anda **telah memilih file Foto Temuan AND mengisi teks Catatan Temuan**. Jika salah satu masih kosong, tombol akan tetap terkunci.

### Q3: Mengapa timbul eror saat mengunggah Foto Temuan atau PDF Pedoman?
> **Jawab**:
> * **Ukuran File Terlalu Besar**: Batas maksimum ukuran file foto maupun PDF adalah **10 MB**. Kompres foto atau dokumen PDF Anda terlebih dahulu sebelum diunggah.
> * **Format File Tidak Sesuai**: Foto temuan wajib berformat gambar (`.jpg`, `.jpeg`, `.png`, `.webp`), sedangkan pedoman wajib berformat `.pdf`.

### Q4: Mengapa saat Admin membuat jadwal muncul pesan "Gagal/Bentrok"?
> **Jawab**: Sistem mendeteksi bahwa Auditor yang Anda pilih sudah memiliki alokasi jadwal aktif pada proses/area dan rentang tanggal yang sama. Silakan periksa kembali daftar jadwal yang sudah ada pada menu *Index Jadwal Audit*.

### Q5: Apakah Admin bisa menginput pengisian audit langsung di lapangan?
> **Jawab**: Tidak. Akun dengan role **Admin** dirancang khusus untuk manajemen sistem dan pembacaan data (*View Only*). Pengisian audit di lapangan khusus dilakukan oleh akun ber-role **Auditor** atau **Instruktur**.

---

*Dokumen Manual Book MOTTO Audit - Versi 1.0*

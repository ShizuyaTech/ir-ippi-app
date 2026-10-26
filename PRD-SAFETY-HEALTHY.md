# PRD: Pelaporan Safety & Healthy

**Status:** Disetujui untuk implementasi  
**Tujuan:** Menyediakan alur pelaporan potensi bahaya dari homepage, verifikasi berjenjang oleh PIC Safety & Healthy, Industrial Relationship (IR), dan Safety, Healthy & Environment (SHE), serta pemantauan tindakan perbaikan sampai ditutup.

## 1. Latar belakang

Saat ini aplikasi memiliki homepage publik, autentikasi untuk dashboard, dan navigasi dashboard terpisah dari navigasi homepage. Fitur baru perlu menghubungkan pelaporan publik dengan proses tinjauan internal tanpa mencampur status persetujuan dengan status tindakan perbaikan.

## 2. Tujuan dan indikator keberhasilan

- Pengguna dapat mengirim laporan potensi bahaya melalui homepage beserta foto.
- PIC dapat mengisi checklist kondisi aktual, mengunggah foto hasil pengecekan, dan mengklasifikasikan risiko secara otomatis dari kategori checklist tertinggi yang dipilih.
- Tim SHE dapat menetapkan rencana dan mengelola tindakan perbaikan sampai ditutup.
- IR dan SHE dapat memberikan keputusan yang tercatat dan dapat ditelusuri.
- Laporan yang disetujui SHE memiliki tenggat SLA otomatis berdasarkan level risiko.
- Tindakan perbaikan hanya dapat ditutup setelah bukti perbaikan diunggah.

## 3. Peran dan akses

| Peran | Akses dan tanggung jawab |
|---|---|
| Pelapor | Membuat laporan dari homepage dan melihat konfirmasi pengiriman. |
| PIC Safety & Healthy | Melihat antrean laporan, memverifikasi laporan, dan menetapkan level risiko High/Medium/Low. Tidak mengisi atau mengelola rencana perbaikan. |
| Reviewer IR | Memeriksa laporan setelah verifikasi PIC dan memberi keputusan OK/NG. |
| Tim SHE | Memeriksa laporan setelah keputusan IR dan memberi keputusan final OK/NG. Setelah OK, mengisi rencana perbaikan, melaksanakan/menindaklanjuti tindakan, mengunggah bukti, dan menutup tindakan. |
| Admin aplikasi | Mengelola akses peran dan dapat diberi akses baca/pengelolaan sesuai kebijakan aplikasi. |

**Pembatasan akses:** halaman antrean dan keputusan internal memerlukan login. Otorisasi wajib diperiksa di server, bukan hanya dengan menyembunyikan menu.

## 4. Alur pengguna

1. Dari navbar homepage, pengguna memilih **Laporan Safety & Healthy** lalu memasukkan NPK dan nama.
2. Aplikasi mencocokkan keduanya dengan data karyawan aktif pada tabel `safety_report_employees` di database aplikasi `ir-ippi-db`. Admin mengelola roster ini dari dashboard. Jika tidak cocok, pengguna dikembalikan ke homepage dengan pesan; jika cocok, aplikasi menampilkan pertanyaan:
   - Apakah laporan ini sudah disampaikan kepada atasan? **Sudah / Belum**
   - Apakah Kiken Yochi (KY) sudah dibuat? **Sudah / Belum**
3. Setelah menjawab keduanya, pengguna mengisi seksi, lokasi, deskripsi dugaan bahaya, dan mengunggah foto pendukung. Nama dan NPK pelapor diambil dari hasil verifikasi server, bukan dari input formulir laporan.
4. Setelah submit berhasil, aplikasi menampilkan nomor laporan dan konfirmasi. Laporan masuk ke antrean PIC dengan status **Menunggu verifikasi PIC**.
5. PIC memeriksa data, mengisi level risiko **High / Medium / Low**, serta dapat meminta perbaikan data dengan catatan. PIC tidak mengisi rencana perbaikan.
6. Setelah verifikasi PIC, laporan masuk ke antrean IR. IR memilih **OK** atau **NG** dan wajib mengisi catatan.
7. Jika IR memilih OK, laporan diteruskan ke verifikator SHE. Jika NG, laporan dikembalikan ke PIC untuk ditindaklanjuti; keputusan dan catatan tetap tercatat.
8. Tim SHE memeriksa laporan dan memilih **OK** atau **NG**, wajib dengan catatan. Jika NG, laporan dikembalikan ke PIC. Jika OK, laporan diterima dan tenggat SLA dibuat otomatis sesuai level risiko.
9. Tim SHE mengisi rencana tindakan perbaikan, penanggung jawab pelaksana, dan tanggal target. Tindakan dimulai dengan status **Open**.
10. Tim SHE menindaklanjuti perbaikan, mengunggah foto bukti setelah perbaikan dilakukan, lalu menutup tindakan. PIC hanya menetapkan level risiko pada tahap verifikasi dan tidak mengelola status/rencana tindakan.
11. Detail laporan menampilkan riwayat status, keputusan, penanggung jawab, tanggal, tenggat, dan bukti.

## 5. Kebutuhan fungsional

### 5.1 Pelaporan homepage

- Menambahkan tautan pelaporan ke navbar homepage yang mengarah ke formulir laporan.
- Mengumpulkan dua jawaban Sudah/Belum sebelum membuka formulir utama; jawaban disimpan bersama laporan.
- Validasi identitas menggunakan NPK dan nama pada data karyawan aktif. Data identitas laporan disalin dari roster yang diverifikasi; seksi, lokasi, deskripsi dugaan bahaya, jawaban pertanyaan awal, dan minimal satu foto wajib diisi.
- Unggahan hanya menerima format gambar yang diizinkan (JPEG, PNG, atau WebP) dengan batas ukuran yang ditampilkan pada formulir.
- Menyimpan foto laporan, foto kondisi aktual PIC, dan bukti perbaikan sebagai BLOB dalam database aplikasi yang sama; data foto hanya dapat diakses melalui route internal yang terotorisasi.
- Menggunakan satu database aplikasi (`ir-ippi-db`) untuk laporan, roster karyawan, akun aplikasi, dan foto; tidak membaca roster dari database eksternal.
- Membatasi role IR dan SHE pada route serta menu Dashboard dan Safety & Healthy.
- Menyediakan pesan validasi yang jelas dan konfirmasi sukses beserta nomor laporan.

### 5.2 Antrean dan verifikasi internal

- Menambahkan menu **Safety & Healthy** pada navigasi dashboard untuk peran internal yang berwenang.
- Menampilkan daftar laporan dengan pencarian/filter sekurangnya berdasarkan status, level risiko, seksi, dan tanggal.
- Halaman detail menampilkan data pelapor, jawaban awal, foto, seluruh keputusan, SLA, rencana perbaikan, dan bukti.
- PIC mengisi checklist High/Medium/Low dan mengunggah foto kondisi aktual saat verifikasi. Level risiko ditentukan sistem berdasarkan kategori tertinggi yang dipilih (High mengalahkan Medium, Medium mengalahkan Low). Minimal satu checklist wajib dipilih.
- Dashboard indeks Safety & Healthy menampilkan jumlah total laporan, antrean tiap tahap, perbaikan aktif, laporan closed, dan distribusi risiko aktif.
- PIC dapat menyimpan catatan verifikasi; PIC tidak dapat mengisi atau mengubah rencana perbaikan maupun status tindakan.
- Reviewer IR dan verifikator SHE hanya dapat memberi keputusan pada tahap yang menjadi kewenangannya.
- Tim SHE dapat mengisi rencana tindakan, penanggung jawab, dan target setelah persetujuan final; SHE mengelola pelaksanaan, unggahan bukti, dan penutupan tindakan.
- Keputusan NG mewajibkan catatan dan mengembalikan laporan ke PIC; keputusan tidak menghapus jejak keputusan sebelumnya.
- Perubahan status, level risiko, tenggat, keputusan, dan unggahan bukti dicatat dalam riwayat audit berisi pelaku dan waktu.
- Laporan closed menyediakan halaman cetak berisi rangkaian informasi, checklist, timeline, riwayat, serta foto awal, aktual, dan bukti perbaikan; pengguna menyimpan hasil sebagai PDF melalui dialog cetak browser.

### 5.3 Rencana dan penutupan perbaikan

- Setelah SHE menyetujui laporan, tim SHE mencatat uraian tindakan, penanggung jawab, dan tanggal rencana.
- Status tindakan memiliki nilai **Open** dan **Close**; laporan tetap dalam proses sampai tindakan ditutup.
- Penutupan dilakukan oleh tim SHE dan memerlukan foto bukti perbaikan. PIC tidak memiliki kewenangan mengelola atau mengubah status tindakan.
- Sistem menyimpan waktu selesai aktual dan menampilkan apakah target SLA tercapai atau terlambat.

## 6. Status laporan dan tindakan

Status alur laporan dan status perbaikan adalah dua hal terpisah.

| Tahap | Status laporan | Pelaku berikutnya |
|---|---|---|
| Pengiriman berhasil | Menunggu verifikasi PIC | PIC Safety & Healthy |
| Risiko dan data diverifikasi | Menunggu review IR | Reviewer IR |
| IR memilih NG | Perlu tindak lanjut PIC | PIC Safety & Healthy |
| IR memilih OK | Menunggu verifikasi SHE | Verifikator SHE |
| SHE memilih NG | Perlu tindak lanjut PIC | PIC Safety & Healthy |
| SHE memilih OK | Diterima - menunggu rencana perbaikan | Tim SHE |
| Rencana perbaikan dibuat | Perbaikan Open | Tim SHE |
| Tim SHE mengunggah bukti dan menutup tindakan | Selesai - perbaikan Close | Tidak ada |

Setiap pengembalian ke PIC harus menyertakan catatan. Perbaikan data dan pengajuan ulang mempertahankan riwayat review sebelumnya.

## 7. SLA dan timeline

Setelah keputusan final SHE **OK**, sistem membuat tenggat berdasarkan tanggal/waktu persetujuan final.

| Indikator | High (risiko tinggi) | Medium (risiko sedang) | Low (risiko rendah) |
|---|---:|---:|---:|
| Verifikasi | Maks. 1 hari kerja | Maks. 3 hari kerja | Maks. 5 hari kerja |
| Penetapan rencana setelah verifikasi | Maks. 1 hari kerja | Maks. 3 hari kerja | Maks. 5 hari kerja |
| Penyelesaian perbaikan | Maks. 7 hari kalender | Maks. 14 hari kalender | Maks. 30 hari kalender |
| Closure setelah perbaikan selesai | Segera setelah perbaikan selesai dan aman diuji | Maks. 2 hari kerja | Maks. 3 hari kerja |

Timeline menampilkan tanggal mulai, tanggal jatuh tempo, tanggal aktual, dan indikator tepat waktu/terlambat untuk setiap tahap. Sistem menggunakan zona waktu aplikasi. Untuk implementasi tahap pertama, hari kerja menghitung Senin-Jumat dan melewati Sabtu-Minggu; kalender libur nasional/perusahaan belum dikonfigurasi dan dapat ditambahkan kemudian.

## 8. Kebutuhan nonfungsional

- Validasi dan otorisasi dilakukan di backend untuk semua aksi.
- File unggahan divalidasi dari tipe dan ukuran, serta nama file tidak digunakan sebagai nama penyimpanan.
- Riwayat keputusan dan tenggat tersimpan konsisten walau laporan dikembalikan atau diklasifikasi ulang.
- Antarmuka responsif untuk desktop dan ponsel serta mengikuti gaya visual aplikasi saat ini.
- Pesan error dan keberhasilan ditampilkan dengan jelas; kegagalan simpan atau unggah tidak boleh menampilkan konfirmasi sukses.

## 9. Kriteria penerimaan

1. Tautan homepage membuka alur pelaporan dan dua pertanyaan awal harus dijawab sebelum formulir dapat dikirim.
2. Submit tanpa data wajib atau foto ditolak dengan validasi yang dapat dipahami.
3. Submit valid membuat satu laporan dengan nomor unik, status awal yang benar, dan foto tersimpan.
4. Pengguna tanpa peran internal tidak dapat membuka antrean atau mengirim keputusan internal melalui URL langsung.
5. PIC wajib mengisi minimal satu checklist dan mengunggah foto kondisi aktual; sistem menentukan risiko dari kategori terpilih dengan prioritas High > Medium > Low.
6. IR dan SHE hanya dapat memutuskan laporan pada tahap masing-masing; keputusan NG harus memiliki catatan.
7. Keputusan dan pengembalian ke PIC tercatat di riwayat dan tidak menimpa rekam sebelumnya.
8. Persetujuan final SHE membuat timeline dan tenggat sesuai tabel SLA serta level risiko.
9. Tindakan hanya dapat ditutup oleh tim SHE setelah foto bukti perbaikan diunggah.
10. Status Open/Close, bukti, tanggal aktual, dan informasi keterlambatan tampil pada detail laporan.
11. Ringkasan dashboard menampilkan antrean laporan, perbaikan aktif, laporan closed, dan risiko aktif.
12. Laporan closed dapat dibuka pada halaman cetak yang menyertakan foto awal, foto aktual PIC, foto bukti perbaikan, keputusan, dan riwayat; browser menyediakan opsi simpan sebagai PDF.

## 10. Di luar cakupan tahap pertama

- Notifikasi email, WhatsApp, atau push.
- Eskalasi otomatis dan pengingat SLA.
- Pelaporan anonim.
- Ekspor laporan/rekap ke Excel.
- Dashboard analitik tren safety.
- Integrasi otomatis dengan sistem Kiken Yochi atau HR.

## 11. Asumsi dan keputusan yang perlu disetujui

1. **Pelaporan karyawan:** pelapor tidak perlu login, tetapi harus memverifikasi NPK dan nama terhadap roster karyawan aktif sebelum melanjutkan dari homepage.
2. **Kepemilikan peran:** admin aplikasi menetapkan akun PIC Safety & Healthy, reviewer IR, dan verifikator SHE. Peran-peran tersebut terpisah agar persetujuan berjenjang dapat ditegakkan.
3. **Keputusan NG:** IR atau SHE yang memilih NG mengembalikan laporan ke PIC untuk koreksi/pelengkapan, bukan langsung menghapus atau menutup laporan.
4. **Kewenangan PIC:** PIC hanya memverifikasi laporan dan menetapkan level risiko; PIC tidak mengisi rencana perbaikan dan tidak mengubah status tindakan.
5. **Tindakan perbaikan:** tim SHE bertanggung jawab mengisi rencana, penanggung jawab, dan target; menindaklanjuti perbaikan; mengunggah bukti; dan mengubah status tindakan menjadi Close.
6. **Hari kerja:** tahap pertama menghitung hari kerja Senin-Jumat tanpa kalender libur khusus. SLA penyelesaian 7/14/30 hari menggunakan hari kalender seperti pada gambar.
7. **Jumlah foto dan ukuran maksimum:** usulan minimal satu foto pelaporan dan satu foto bukti perbaikan; batas ukuran dan apakah unggah multi-foto diperlukan perlu dikonfirmasi.
8. **Urgensi bahaya High:** laporan High ditandai dan diprioritaskan; sistem tidak menggantikan prosedur tanggap darurat atau kewajiban melapor langsung kepada atasan.

## 12. Rencana implementasi setelah persetujuan

- Tambahkan penyimpanan laporan, lampiran, histori workflow, dan konfigurasi peran.
- Implementasikan formulir homepage, antrean dashboard, halaman detail, verifikasi, rencana perbaikan, dan unggah bukti.
- Tambahkan otorisasi, validasi, serta pengujian alur sukses dan penolakan akses.
- Jalankan migrasi dan pengujian terarah; dokumentasikan instruksi aktivasi peran dan storage.

RUMIKU PROJECT: TODO LIST

Module 1: Team Management
Status: Completed

Tahap 1: Fondasi Database dan User Role
[x] Buat Migration untuk tabel roles (Admin, Member).
[x] Modifikasi Migration users untuk integrasi role_id.
[x] Buat Migration untuk tabel tasks (ID, Title, Description, Assigned_To, Status, Due_Date).
[x] Buat Model Role, User, dan Task beserta Eloquent Relationship.

Tahap 2: Logika Bisnis (CRUD Minimalis)
[x] Buat TaskController (Index, Create, Store, Update, Delete).
[x] Implementasi Middleware akses internal.
[x] Buat fitur integrasi Livewire untuk update status instan.

Tahap 3: Antarmuka Pengguna (UI)
[x] Desain Dashboard Utama (Task Summary).
[x] Desain Halaman Daftar Tugas dengan Tailwind CSS.
[x] Integrasi notifikasi sederhana di dashboard.

Tahap 4: Database Seeding
[x] Rencanakan urutan seeding (Role, User, Task).
[x] Modifikasi DatabaseSeeder.php memanggil factory Tahap 1.
[x] Buat data 3 anggota tim RUMIKU.
[x] Buat 20 task acak untuk 3 member tersebut.

Tahap 5: Routing & Koneksi UI
[x] Edit routes/web.php dan hapus rute bawaan Laravel.
[x] Daftarkan rute Dashboard dan TaskController::class.

Tahap 6: Form UI (Modernization)
[x] Transform Task Create & Edit to "Peek" Style (Slide-over) Interface.
[x] Remove redundant page-based create/edit views.
[x] Refactor TaskController to use Livewire (TaskForm) for CRUD logic.

Tahap 7: Sinkronisasi Metrik Dashboard
[x] Hitung Active Tasks, Pending, dan Completed via Eloquent di routes/web.php.
[x] Integrasikan variabel metrik dinamis ke dalam dashboard.blade.php.

Tahap 8: Enhancements / Revisi UI
[x] Mengubah tampilan Team Tasks agar dikelompokkan berdasarkan status.
[x] Menambahkan filter deadline (Paling dekat, Paling jauh).
[x] Menambahkan metrik ringkasan Omni-channel dan Social Media di Dashboard.

Module 2: Bookkeeping & Analytics
Status: Completed

Tahap 1: Struktur Data Keuangan
[x] Buat Migration tabel transactions (ID, Date, Type, Project, Amount, Description, User_ID).
[x] Buat Model Transaction dengan properti fillable yang sesuai.
[x] Jalankan perintah migrasi ke database MySQL.

Tahap 2: Komponen Livewire CashFlow
[x] Buat Full-page Livewire Component: CashFlowManager.
[x] Implementasi fungsi save dengan validasi server side.
[x] Implementasi fungsi delete transaksi dengan konfirmasi browser.

Tahap 3: Fitur Multi-Project & Filter
[x] Implementasi kategori project (Creedigo, ROKU, Kyoomi, Glocult, Umum).
[x] Buat logika filter ganda berdasarkan Project dan Kategori Arus Kas.
[x] Sinkronisasi ringkasan saldo (Summary) agar selalu update saat filter digunakan.

Tahap 4: Input Masking & UX
[x] Integrasi Alpine.js untuk formatting ribuan (titik) secara real time.
[x] Gunakan teknik @entangle atau manual sync untuk memastikan data rupiah tersimpan sebagai angka murni.
[x] Implementasi reset form otomatis setelah transaksi berhasil disimpan.

Tahap 5: Integrasi Navigasi & UI Layout
[x] Daftarkan rute named route 'bookkeeping' di routes/web.php.
[x] Standarisasi sidebar: Hapus label pemisah, tambahkan icon, dan sejajarkan semua menu.
[x] Implementasi logika active menu agar indikator warna pindah saat tab Bookkeeping diklik.

Module 3: Inventory Warehouse
Status: Skipped/Pending (Menunggu kesiapan integrasi API / Jubelio)

Module 4: Social Media
Status: Completed

Tahap 1: Persiapan Database & Model
[x] Buat migration untuk `social_accounts`, `social_posts`, dan `post_metrics`.
[x] Definisikan relasi antar tabel (User, Project, Social Accounts, Posts).
[x] Jalankan migrasi ke database.

Tahap 2: Manajemen Akun Multi-Brand
[x] Buat antarmuka (UI) untuk menambahkan dan mengelola akun media sosial berdasarkan proyek (Glocult, ROKU, dll.).
[x] Siapkan pengamanan untuk menyimpan access_token.

Tahap 3: Content Planner & Scheduler
[x] Buat antarmuka kalender visual menggunakan Livewire.
[x] Implementasi form pembuatan konten (Draft, Review, Scheduled).
[x] Integrasi Alpine.js untuk fitur preview konten secara real-time.

Tahap 4: Approval Workflow & Analytics
[x] Buat sistem persetujuan konten sebelum dipublikasikan.
[x] Siapkan penampung data untuk metrik performa (reach, engagement).

RUMIKU PROJECT: TODO LIST

Module 1: Team Management
Status: Completed
... (Riwayat Tugas Selesai)

Module 2: Bookkeeping & Analytics
Status: Completed
... (Riwayat Tugas Selesai)

Module 3: Inventory Warehouse
Status: Skipped/Pending (Menunggu kesiapan integrasi API / Jubelio)

Module 4: Social Media
Status: Completed
[x] Persiapan Database & Model (Accounts, Posts, Metrics).
[x] UI Manajemen Akun Multi-Brand.
[x] Content Planner & Visual Calendar UI.
[x] Fitur Live Preview Konten & Scheduler.

Module 5: Omni-channel Communication
Status: Completed
[x] Persiapan Database & Model (Channels, Conversations, Messages).
[x] Webhook API Controller untuk integrasi n8n.
[x] Unified Inbox Frontend dengan Livewire polling.
[x] Fitur Outbound Messaging & Alpine.js Notifications.

Module 6: Marketing
Status: Pending
[ ] Rencana kampanye email & broadcast WA.
[ ] CRM Dashboard & Customer Segmentation.

UI/UX Refinement & Branding
Status: Completed
[x] Modernisasi UI/UX (Plus Jakarta Sans, Rounded-3xl, Shadow-sm).
[x] Implementasi logo baru (FA RUMI KULTURA UTOPIA).
[x] Standarisasi palet warna sekunder (Phase 2).
[x] Penambahan subtitle keterangan di setiap modul (Phase 3).
[x] Refaktor Dashboard ke Bento Grid Layout (Phase 4).
[x] Optimalisasi Spacing & Padding Global (Premium Look).
[x] Implementasi Custom Select Component (x-custom-select) di seluruh modul.
[x] Implementasi "Peek" Slide-over Interface untuk Task Management.
[x] Optimalisasi Kontras Field Input di Mode Gelap (zinc-800/70 Background).
[x] Implementasi Global Modern Scrollbar (Auto-adapt Mode Terang/Gelap).
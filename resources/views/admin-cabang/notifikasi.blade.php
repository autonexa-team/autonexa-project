@extends('layout.admin-cabang')

@section('content')
    <!-- Animations -->
    <style>
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-slide-up {
            animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .stagger-1 {
            animation-delay: 50ms;
        }

        .stagger-2 {
            animation-delay: 100ms;
        }

        .stagger-3 {
            animation-delay: 150ms;
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(-20px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-slide-up stagger-1">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                Pusat Notifikasi
                <span
                    class="bg-brand text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse shadow-sm shadow-brand/30"
                    id="unreadCountBadge">3 Baru</span>
            </h2>
            <p class="text-slate-500 mt-2 text-sm font-medium">Pemberitahuan aktivitas reservasi, stok, dan sistem secara
                real-time.</p>
        </div>

        <div class="flex gap-2">
            <button
                class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
            </button>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 animate-fade-slide-up stagger-2">
        <!-- Left Sidebar: Filters -->
        <div class="lg:col-span-1 space-y-2">
            <button
                class="w-full text-left bg-white border border-brand text-brand shadow-sm shadow-brand/10 font-bold px-5 py-3.5 rounded-xl transition-all flex justify-between items-center group">
                <span class="flex items-center gap-3"><i class="fas fa-inbox text-lg"></i> Semua Notifikasi</span>
                <span class="bg-brand text-white text-xs px-2 py-0.5 rounded-md shadow-sm">24</span>
            </button>
            <button
                class="w-full text-left bg-transparent border border-transparent hover:bg-white hover:border-slate-100 text-slate-600 font-bold px-5 py-3.5 rounded-xl transition-all flex justify-between items-center group">
                <span class="flex items-center gap-3"><i
                        class="far fa-bell text-lg group-hover:text-amber-500 transition-colors"></i> Belum Dibaca</span>
                <span class="bg-slate-100 text-slate-500 text-xs px-2 py-0.5 rounded-md" id="filterUnreadCount">3</span>
            </button>
            <button
                class="w-full text-left bg-transparent border border-transparent hover:bg-white hover:border-slate-100 text-slate-600 font-bold px-5 py-3.5 rounded-xl transition-all flex justify-between items-center group">
                <span class="flex items-center gap-3"><i
                        class="fas fa-calendar-alt text-lg group-hover:text-blue-500 transition-colors"></i>
                    Reservasi</span>
            </button>
            <button
                class="w-full text-left bg-transparent border border-transparent hover:bg-white hover:border-slate-100 text-slate-600 font-bold px-5 py-3.5 rounded-xl transition-all flex justify-between items-center group">
                <span class="flex items-center gap-3"><i
                        class="fas fa-box text-lg group-hover:text-emerald-500 transition-colors"></i> Stok Sparepart</span>
            </button>
            <button
                class="w-full text-left bg-transparent border border-transparent hover:bg-white hover:border-slate-100 text-slate-600 font-bold px-5 py-3.5 rounded-xl transition-all flex justify-between items-center group">
                <span class="flex items-center gap-3"><i
                        class="fas fa-star text-lg group-hover:text-amber-400 transition-colors"></i> Review
                    Pelanggan</span>
            </button>
        </div>

        <!-- Right Content: Notification List -->
        <div class="lg:col-span-3">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <!-- Loading Indicator for Real-time -->
                <div class="bg-slate-50 border-b border-slate-100 px-6 py-3 flex justify-between items-center">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        Live Connection Active
                    </div>
                    <button id="simulateBtn"
                        class="text-xs font-bold text-brand hover:underline px-2 py-1 bg-brand/10 rounded">Simulasikan Pesan
                        Masuk</button>
                </div>

                <!-- List Container -->
                <div id="notificationList" class="divide-y divide-slate-100">

                    <!-- Unread Notification 1 -->
                    <div class="p-6 bg-orange-50/30 hover:bg-orange-50/60 transition-colors relative group cursor-pointer">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand"></div>
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-white">
                                <i class="fas fa-calendar-plus text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-slate-800 text-base group-hover:text-brand transition-colors">
                                        Reservasi Baru Dibuat</h4>
                                    <span class="text-xs font-bold text-brand">Baru saja</span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium">Pelanggan <strong>Budi Setiawan</strong> telah
                                    membuat reservasi untuk layanan Ganti Oli Mesin pada 19 Mei 2026, 10:00.</p>
                                <div class="mt-3 flex gap-2">
                                    <button
                                        class="px-4 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg shadow-sm hover:bg-slate-50 hover:text-brand hover:border-brand/30 transition-colors">Lihat
                                        Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Notification 2 -->
                    <div class="p-6 bg-orange-50/30 hover:bg-orange-50/60 transition-colors relative group cursor-pointer">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand"></div>
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-white">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-slate-800 text-base group-hover:text-brand transition-colors">
                                        Stok Sparepart Kritis</h4>
                                    <span class="text-xs font-bold text-brand">10 menit yang lalu</span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium">Stok <strong>Filter Oli Avanza</strong> saat
                                    ini tersisa 2 unit. Segera lakukan pengadaan (restock).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Notification 3 -->
                    <div class="p-6 bg-orange-50/30 hover:bg-orange-50/60 transition-colors relative group cursor-pointer">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand"></div>
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-white">
                                <i class="fas fa-star text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-slate-800 text-base group-hover:text-brand transition-colors">
                                        Review Pelanggan Baru</h4>
                                    <span class="text-xs font-bold text-brand">1 jam yang lalu</span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium"><strong>Andi Saputra</strong> memberikan
                                    ulasan <span class="text-amber-500"><i class="fas fa-star"></i> 5.0</span> untuk
                                    pelayanan di cabang Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Read Notification 1 -->
                    <div
                        class="p-6 hover:bg-slate-50 transition-colors relative group cursor-pointer opacity-70 hover:opacity-100">
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-double text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4
                                        class="font-bold text-slate-700 text-base group-hover:text-slate-900 transition-colors">
                                        Reservasi Selesai</h4>
                                    <span class="text-xs font-bold text-slate-400">Kemarin, 14:30</span>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Servis berkala kendaraan B 1234 ABC atas nama
                                    Citra Kirana telah selesai dikerjakan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Read Notification 2 -->
                    <div
                        class="p-6 hover:bg-slate-50 transition-colors relative group cursor-pointer opacity-70 hover:opacity-100">
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4
                                        class="font-bold text-slate-700 text-base group-hover:text-slate-900 transition-colors">
                                        Pembaruan Sistem Pusat</h4>
                                    <span class="text-xs font-bold text-slate-400">2 hari yang lalu</span>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Admin Pusat telah menambahkan layanan baru:
                                    <strong>Cuci Mobil Premium</strong>. Silakan cek menu Layanan untuk mengaktifkannya.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-4 border-t border-slate-100 text-center bg-slate-50">
                    <button class="text-sm font-bold text-slate-500 hover:text-brand transition-colors">Muat Lebih Banyak
                        Notifikasi <i class="fas fa-chevron-down ml-1 text-[10px]"></i></button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const listContainer = document.getElementById("notificationList");
            const simulateBtn = document.getElementById("simulateBtn");
            const badge = document.getElementById("unreadCountBadge");
            const filterBadge = document.getElementById("filterUnreadCount");

            let unreadCount = 3;

            // Kumpulan dummy notifikasi untuk simulasi real-time
            const dummyNotifications = [{
                    type: 'reservasi',
                    icon: 'fa-calendar-check',
                    iconBg: 'bg-emerald-100',
                    iconColor: 'text-emerald-600',
                    title: 'Pembayaran Dikonfirmasi',
                    message: 'Pembayaran DP untuk reservasi #RSV-20260519-002 telah berhasil dikonfirmasi oleh sistem otomatis.',
                },
                {
                    type: 'sparepart',
                    icon: 'fa-box-open',
                    iconBg: 'bg-blue-100',
                    iconColor: 'text-blue-600',
                    title: 'Stok Sparepart Masuk',
                    message: 'Pengiriman 50 unit Kampas Rem Depan dari Gudang Pusat telah tiba dan otomatis ditambahkan ke stok.',
                },
                {
                    type: 'review',
                    icon: 'fa-star-half-alt',
                    iconBg: 'bg-amber-100',
                    iconColor: 'text-amber-600',
                    title: 'Review Perlu Dibalas',
                    message: 'Terdapat 1 review pelanggan dengan rating di bawah 3 bintang yang belum Anda balas hari ini.',
                },
                {
                    type: 'sistem',
                    icon: 'fa-user-plus',
                    iconBg: 'bg-purple-100',
                    iconColor: 'text-purple-600',
                    title: 'Pendaftaran Pelanggan Baru',
                    message: 'Seorang pelanggan baru berhasil mendaftarkan akun di cabang Anda via aplikasi mobile.',
                }
            ];

            function createNotificationHTML(data) {
                return `
            <div class="p-6 bg-orange-50/30 hover:bg-orange-50/60 transition-colors relative group cursor-pointer animate-slide-in">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand"></div>
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-full ${data.iconBg} ${data.iconColor} flex items-center justify-center flex-shrink-0 shadow-sm border border-white">
                        <i class="fas ${data.icon} text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-slate-800 text-base group-hover:text-brand transition-colors">${data.title}</h4>
                            <span class="text-xs font-bold text-brand flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-brand animate-ping"></span> Baru saja</span>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">${data.message}</p>
                    </div>
                </div>
            </div>
        `;
            }

            // Fungsi untuk menambah notifikasi baru
            function triggerNewNotification() {
                // Ambil random notifikasi
                const randomNotif = dummyNotifications[Math.floor(Math.random() * dummyNotifications.length)];

                // Buat DOM element
                const htmlString = createNotificationHTML(randomNotif);
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlString, 'text/html');
                const newNotifEl = doc.body.firstChild;

                // Tambahkan ke paling atas
                listContainer.insertBefore(newNotifEl, listContainer.firstChild);

                // Update unread count
                unreadCount++;
                badge.innerText = `${unreadCount} Baru`;
                filterBadge.innerText = unreadCount;

                // Trigger notification sound (Optional/simulated UI pop Toast)
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-6 right-6 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 z-50 transform transition-all duration-500 ease-out translate-y-10 opacity-0';
                toast.innerHTML = `
            <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-brand border border-brand/30">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide">Notifikasi Baru Masuk!</h4>
                <p class="text-xs text-slate-400 mt-0.5 line-clamp-1 max-w-[200px]">${randomNotif.title}</p>
            </div>
        `;
                document.body.appendChild(toast);

                // Animasi toast muncul
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-10', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                });

                // Animasi toast hilang
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    setTimeout(() => toast.remove(), 500); // Tunggu animasi selesai baru remove DOM
                }, 4000);
            }

            // Button click simulation
            simulateBtn.addEventListener('click', triggerNewNotification);

            // Auto simulate every 25 seconds to feel "real-time" if user stays on page
            setInterval(triggerNewNotification, 25000);
        });
    </script>
@endsection

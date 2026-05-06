@extends('layout.admin-cabang')

@section('content')

<!-- CSS Custom Animations -->
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-up {
        animation: fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .stagger-1 { animation-delay: 50ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 150ms; }
    .stagger-4 { animation-delay: 200ms; }
    
    /* Custom Scrollbar for Time Slots if needed */
    .time-slot-grid::-webkit-scrollbar {
        height: 6px;
    }
    .time-slot-grid::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .time-slot-grid::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>

<!-- Main Container -->
<div class="animate-fade-slide-up stagger-1">
    
    <!-- Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 font-medium mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/admin-cabang/dashboard') }}" class="hover:text-brand transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                            <a href="{{ url('/admin-cabang/reservasi') }}" class="hover:text-brand transition-colors">Reservasi</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                            <span class="text-slate-800 font-bold">Tambah Baru</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Tambah Reservasi Baru</h2>
            <p class="text-slate-500 mt-1 text-sm">Buat reservasi langsung untuk pelanggan (Walk-in / By Phone).</p>
        </div>
        <div>
            <a href="{{ url('/admin-cabang/reservasi') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Form Area -->
    <form id="formReservasi" class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative items-start">
        
        <!-- Left Column: Form Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Informasi Pelanggan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-2">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Informasi Pelanggan</h3>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cari Pelanggan Existing <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <select id="searchPelanggan" onchange="fillPelanggan()" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none font-medium appearance-none transition-all cursor-pointer">
                                <option value="">-- Pilih atau biarkan kosong untuk pelanggan baru --</option>
                                <!-- Dummy Data Injected via JS -->
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="p_nama" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all" placeholder="John Doe" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" id="p_hp" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all" placeholder="081234567890" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="email" id="p_email" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all" placeholder="johndoe@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Plat Nomor Kendaraan <span class="text-red-500">*</span></label>
                            <input type="text" id="p_plat" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all uppercase font-bold" placeholder="B 1234 ABC" required>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Card 3: Informasi Reservasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-4">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Informasi Reservasi</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Layanan Utama <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="layananUtama" onchange="updateLayananDetail()" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3.5 outline-none font-bold appearance-none transition-all cursor-pointer shadow-sm" required>
                                <option value="">-- Pilih Layanan --</option>
                                <!-- JS Injected -->
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-brand">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Reservasi <span class="text-red-500">*</span></label>
                            <input type="date" id="tglReservasi" onchange="generateTimeSlots()" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none font-medium transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 flex justify-between">
                                Jam Reservasi <span class="text-red-500">*</span>
                                <span class="text-xs text-brand font-normal bg-brand/10 px-2 py-0.5 rounded-full" id="slotInfoStatus">Pilih Tanggal Dulu</span>
                            </label>
                            <!-- Hidden input to store selected time -->
                            <input type="hidden" id="jamReservasi" required>
                            
                            <!-- Time Slot Grid -->
                            <div class="grid grid-cols-3 gap-2" id="timeSlotContainer">
                                <!-- JS Injected Slots -->
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2"><i class="fas fa-info-circle"></i> Max 5 reservasi/jam. Abu-abu = Penuh.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Summary & Actions -->
        <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6 animate-fade-slide-up stagger-4">
            
            <!-- Settings Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-brand flex items-center justify-center">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Pengaturan</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Reservasi</label>
                        <div class="relative">
                            <select id="statusReservasi" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none font-medium appearance-none transition-all">
                                <option value="Menunggu" selected>Menunggu Konfirmasi</option>
                                <option value="Dikonfirmasi">Dikonfirmasi (Approved)</option>
                                <option value="Proses">Sedang Diproses (On-going)</option>
                                <option value="Selesai">Selesai (Completed)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan / Keluhan</label>
                        <textarea id="catatan" rows="4" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all resize-none" placeholder="Masukkan keluhan pelanggan atau request khusus di sini..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Estimasi Card -->
            <div class="bg-slate-800 rounded-2xl shadow-lg border border-slate-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="font-bold text-white uppercase tracking-wider text-xs flex items-center gap-2 mb-4">
                        <i class="fas fa-file-invoice-dollar text-slate-400"></i> Estimasi Layanan
                    </h3>
                    
                    <div class="bg-slate-700/50 rounded-xl p-4 mb-4 border border-slate-600">
                        <p class="text-sm font-medium text-slate-300 transition-all duration-300" id="estDesc">Pilih layanan utama terlebih dahulu untuk melihat detail estimasi.</p>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Estimasi Durasi</span>
                            <span class="font-bold text-white bg-slate-700 px-2.5 py-1 rounded-md border border-slate-600 transition-all duration-300" id="estDuration">-</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-t border-slate-700 pt-3">
                            <span class="text-slate-400">Estimasi Biaya Dasar</span>
                            <span class="font-bold text-emerald-400 text-lg transition-all duration-300" id="estPrice">Rp 0</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <button type="submit" id="btnSubmit" class="w-full bg-brand hover:bg-brand-dark text-white py-3.5 rounded-xl font-bold transition-all duration-300 shadow-lg shadow-brand/20 flex items-center justify-center gap-2 active:scale-95 group relative overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></span>
                            <span id="submitText" class="relative z-10">Simpan Reservasi</span>
                            <i id="submitIcon" class="fas fa-check-circle relative z-10"></i>
                            <i id="loadingIcon" class="fas fa-circle-notch fa-spin hidden relative z-10"></i>
                        </button>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="resetForm()" class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <a href="{{ url('/admin-cabang/reservasi') }}" class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2 text-center">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Scripts for Logic & Dummy Data -->
<script>
    // --- DUMMY DATA ---
    const dummyPelanggan = [
        { id: 1, nama: 'Budi Santoso', hp: '081234567890', email: 'budi@gmail.com', plat: 'B 1234 ABC' },
        { id: 2, nama: 'Siti Aminah', hp: '085678901234', email: 'siti.aminah@yahoo.com', plat: 'D 5432 CDE' },
        { id: 3, nama: 'Joko Anwar', hp: '089876543210', email: '', plat: 'F 1111 QWE' }
    ];

    const dummyLayanan = [
        { id: 1, nama: 'Servis Berkala (Tune Up)', harga: 350000, durasi: '90 Menit', desc: 'Pengecekan dan pembersihan komponen mesin, ganti busi, filter udara, dan tune-up standar.' },
        { id: 2, nama: 'Ganti Oli Mesin', harga: 150000, durasi: '30 Menit', desc: 'Penggantian oli mesin standar beserta filter oli. Biaya belum termasuk harga oli.' },
        { id: 3, nama: 'Ganti Kampas Rem', harga: 200000, durasi: '45 Menit', desc: 'Bongkar pasang dan penggantian kampas rem (depan/belakang). Biaya belum termasuk sparepart.' },
        { id: 4, nama: 'Spooring & Balancing', harga: 250000, durasi: '60 Menit', desc: 'Penyelarasan sudut roda dan balancing 4 roda untuk kenyamanan berkendara.' },
        { id: 5, nama: 'Pengecekan Umum', harga: 0, durasi: 'TBD', desc: 'Diagnosa awal keluhan pelanggan. Biaya akan disesuaikan dengan hasil pengecekan.' }
    ];

    const workingHours = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

    // Form Elements
    const elPelanggan = document.getElementById('searchPelanggan');
    const elLayanan = document.getElementById('layananUtama');
    const elTimeContainer = document.getElementById('timeSlotContainer');
    const elJamInput = document.getElementById('jamReservasi');
    const elTglInput = document.getElementById('tglReservasi');

    // Utility: Format Rupiah
    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
    }

    // Init Page
    document.addEventListener('DOMContentLoaded', () => {
        // Populate Pelanggan
        dummyPelanggan.forEach(p => {
            let opt = new Option(`${p.nama} (${p.hp})`, p.id);
            elPelanggan.add(opt);
        });

        // Populate Layanan
        dummyLayanan.forEach(l => {
            let opt = new Option(`${l.nama} - ${formatRupiah(l.harga)}`, l.id);
            elLayanan.add(opt);
        });

        // Set default date today
        const today = new Date().toISOString().split('T')[0];
        elTglInput.value = today;
        generateTimeSlots();
    });

    // Handle Pelanggan Change
    function fillPelanggan() {
        const pId = parseInt(elPelanggan.value);
        if(!pId) {
            // Reset to new customer
            document.getElementById('p_nama').value = '';
            document.getElementById('p_hp').value = '';
            document.getElementById('p_email').value = '';
            document.getElementById('p_plat').value = '';
            return;
        }

        const p = dummyPelanggan.find(x => x.id === pId);
        if(p) {
            // Auto fill effect
            const inputs = ['p_nama', 'p_hp', 'p_email', 'p_plat'];
            inputs.forEach(id => document.getElementById(id).classList.add('ring-2', 'ring-brand/30'));
            
            document.getElementById('p_nama').value = p.nama;
            document.getElementById('p_hp').value = p.hp;
            document.getElementById('p_email').value = p.email;
            document.getElementById('p_plat').value = p.plat;

            setTimeout(() => {
                inputs.forEach(id => document.getElementById(id).classList.remove('ring-2', 'ring-brand/30'));
            }, 500);
        }
    }

    // Handle Layanan Change
    function updateLayananDetail() {
        const lId = parseInt(elLayanan.value);
        const elDesc = document.getElementById('estDesc');
        const elPrice = document.getElementById('estPrice');
        const elDur = document.getElementById('estDuration');

        if(!lId) {
            elDesc.textContent = "Pilih layanan utama terlebih dahulu untuk melihat detail estimasi.";
            elPrice.textContent = "Rp 0";
            elPrice.className = "font-bold text-emerald-400 text-lg transition-all duration-300";
            elDur.textContent = "-";
            return;
        }

        const l = dummyLayanan.find(x => x.id === lId);
        if(l) {
            elDesc.innerHTML = `<span class="text-white font-bold">${l.nama}</span><br>${l.desc}`;
            
            // Animation effect
            elPrice.classList.add('scale-110', 'text-brand');
            elPrice.textContent = formatRupiah(l.harga);
            elDur.textContent = l.durasi;

            setTimeout(() => {
                elPrice.classList.remove('scale-110', 'text-brand');
            }, 300);
        }
    }

    // Generate Time Slots based on Date
    function generateTimeSlots() {
        const tgl = elTglInput.value;
        elJamInput.value = ""; // reset selection
        elTimeContainer.innerHTML = "";

        if(!tgl) {
            document.getElementById('slotInfoStatus').textContent = "Pilih Tanggal Dulu";
            return;
        }

        document.getElementById('slotInfoStatus').textContent = "Tersedia";
        document.getElementById('slotInfoStatus').className = "text-xs text-emerald-600 font-bold bg-emerald-100 px-2 py-0.5 rounded-full transition-colors";

        // Seed random logic for dummy: let's pretend some slots are full based on date parity
        const dayNum = parseInt(tgl.split('-')[2]);

        workingHours.forEach((jam, idx) => {
            // Dummy logic: max 5 res per jam. randomly make it full
            let isFull = false;
            let currentRes = Math.floor(Math.random() * 4); // 0 to 3
            
            // Make some slots full for demo
            if((dayNum + idx) % 5 === 0) {
                isFull = true;
                currentRes = 5;
            }

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `w-full py-2.5 rounded-xl border text-sm font-bold transition-all duration-200 flex flex-col items-center justify-center gap-0.5 relative overflow-hidden `;
            
            if(isFull) {
                btn.className += `bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed`;
                btn.innerHTML = `<span>${jam}</span><span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Penuh</span>`;
                btn.disabled = true;
            } else {
                btn.className += `bg-white border-slate-200 text-slate-600 hover:border-brand hover:text-brand slot-btn active:scale-95 shadow-sm hover:shadow-md`;
                btn.innerHTML = `<span>${jam}</span><span class="text-[9px] font-bold text-emerald-500 uppercase tracking-wide">${5 - currentRes} Slot</span>`;
                btn.onclick = () => selectTimeSlot(btn, jam);
            }
            
            elTimeContainer.appendChild(btn);
        });
    }

    function selectTimeSlot(btnEl, jam) {
        // Reset all buttons
        const allBtns = document.querySelectorAll('.slot-btn');
        allBtns.forEach(b => {
            b.classList.remove('bg-brand/10', 'border-brand', 'text-brand', 'ring-2', 'ring-brand/30');
            b.classList.add('bg-white', 'border-slate-200', 'text-slate-600');
        });

        // Set active
        btnEl.classList.remove('bg-white', 'border-slate-200', 'text-slate-600');
        btnEl.classList.add('bg-brand/10', 'border-brand', 'text-brand', 'ring-2', 'ring-brand/30');
        
        elJamInput.value = jam;
    }

    // Handle Form Reset
    function resetForm() {
        document.getElementById('formReservasi').reset();
        elPelanggan.selectedIndex = 0;
        fillPelanggan();
        updateLayananDetail();
        
        const today = new Date().toISOString().split('T')[0];
        elTglInput.value = today;
        generateTimeSlots();
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Handle Form Submit
    document.getElementById('formReservasi').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate time slot
        if(!elJamInput.value) {
            alert('Silakan pilih jam reservasi yang tersedia terlebih dahulu!');
            return;
        }

        const btnSubmit = document.getElementById('btnSubmit');
        const submitText = document.getElementById('submitText');
        const submitIcon = document.getElementById('submitIcon');
        const loadingIcon = document.getElementById('loadingIcon');
        
        // Loading State
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-80', 'cursor-not-allowed');
        submitText.textContent = "Memproses Data...";
        submitIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');

        // Simulate API Request
        setTimeout(() => {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-80', 'cursor-not-allowed');
            submitText.textContent = "Simpan Reservasi";
            submitIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');

            // Show Toast (create dynamically if not exists)
            showToast('Reservasi berhasil disimpan!', 'Tiket reservasi telah aktif dan status diperbarui.');
            
            // Optional: redirect to list
            // setTimeout(() => {
            //     window.location.href = "{{ url('/admin-cabang/reservasi') }}";
            // }, 2000);

        }, 1500);
    });

    // Helper: Toast Notification
    function showToast(title, msg) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 z-50 bg-slate-800 text-white p-4 rounded-xl shadow-2xl flex items-start gap-4 transform transition-all duration-500 ease-out translate-x-10 opacity-0 border-l-4 border-emerald-500';
        toast.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-white">${title}</h4>
                <p class="text-xs text-slate-300 mt-1">${msg}</p>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-10', 'opacity-0');
        });
        
        // Remove after 3s
        setTimeout(() => {
            toast.classList.add('translate-x-10', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>

@endsection

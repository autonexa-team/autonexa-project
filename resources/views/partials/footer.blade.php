    {{-- ═══════════════════════════════════════
        FOOTER
    ════════════════════════════════════════ --}}
    <footer class="footer-main">
        <div class="footer-inner">
            <div class="footer-grid">

                {{-- Brand --}}
                <div class="footer-col">
                    <div class="footer-brand-name">
                        
                        <img src="{{ asset('assets/LogoAutoNexa_v2.svg') }}" alt="Autonexa Logo" class="logo-img">
                        AutoNexa
                    </div>                 
                    <p class="footer-desc">
                        Reservasi online, pantau proses dan dapatkan informasi service dengan mudah dan transparan.
                    </p>
                    <div class="footer-social">
                        <a href="https://github.com/autonexa" target="_blank" class="social-btn" title="GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" class="social-btn" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-btn" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="footer-col">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('landing') }}">Beranda</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li>
                            <a href="{{ route('pelanggan.bengkel') }}"
                                class="{{ request()->routeIs('pelanggan.bengkel') ? 'active' : '' }}">
                                Bengkel
                            </a>
                        </li>                        
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-col">
                    <h6 class="footer-heading">Hubungi Kami</h6>
                    <ul class="footer-links">
                        <li>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:hello@autonexa.id">hello@autonexa.id</a>
                        </li>
                        <li>
                            <i class="bi bi-telephone"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt"></i>
                            <span>Bandung, Jawa Barat</span>
                        </li>
                    </ul>
                </div>

            </div>

            <hr class="footer-divider">
            <p class="footer-copy">&copy; {{ date('Y') }} AutoNexa. All rights reserved.</p>
        </div>
    </footer>
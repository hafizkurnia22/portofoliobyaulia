<footer class="footer-custom">
    <div class="container">
        <div class="footer-content">

            <div class="footer-brand">
                <strong>MyPortfolio</strong>
                <span>© 2026</span>
            </div>

            <div class="footer-social">

                @if (!empty($tentangSaya->facebook))
                    <a href="{{ $tentangSaya->facebook }}" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                @endif

                @if (!empty($tentangSaya->instagram))
                    <a href="{{ $tentangSaya->instagram }}" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                @endif

                @if (!empty($tentangSaya->tiktok))
                    <a href="{{ $tentangSaya->tiktok }}" target="_blank">
                        <i class="bi bi-tiktok"></i>
                    </a>
                @endif

            </div>

        </div>
    </div>
</footer>

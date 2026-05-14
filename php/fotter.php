</main>
<footer class="bg-light border-top mt-auto mt-4 py-3" role="contentinfo">
    <div class="container text-center">
        <p class="mb-2 small fw-semibold" style="color: #4a4a4a;">© 2026 Institut Pedralbes</p>

        <!-- Contenedor principal en columna -->
        <div class="d-flex flex-column align-items-center gap-2">

            <!-- Bloque de Nombres -->
            <div class="small d-flex justify-content-center gap-3 flex-wrap" style="color: #4a4a4a;">
                <span>Hugo Berea</span>
                <span>Alexandre Brandao</span>
            </div>

            <!-- Bloque de Enlaces (Debajo) -->
            <div class="small d-flex justify-content-center gap-3 flex-wrap">
                <a target="_blank" href="resources/Accessibilitat_Pc.html" style="color: #0d6efd;" class="text-decoration-none">
                    <i class="fa-solid fa-universal-access me-1" aria-hidden="true"></i>Accessibilitat PC
                </a>
                <a target="_blank" href="resources/Accessibilitat_Telef.html" style="color: #0d6efd;" class="text-decoration-none">
                    <i class="fa-solid fa-mobile-screen me-1" aria-hidden="true"></i>Accessibilitat Mòbil
                </a>
            </div>

        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('main .container, main .container-fluid');
        if (container) {
            container.style.opacity = 0;
            setTimeout(function() {
                container.style.transition = 'opacity 0.5s linear';
                container.style.opacity = 1;
            }, 100);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
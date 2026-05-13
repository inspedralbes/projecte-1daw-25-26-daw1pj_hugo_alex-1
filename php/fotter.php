</main>
<footer class="bg-light text-center border-top py-3 mt-auto">
    <div class="sticky-bottom">
        <p class="mb-1 small" style="color: #4a4a4a;">© 2026 Institut Pedralbes</p>
        <div class="small">
            <span style="color: #4a4a4a;" class="me-3">Hugo Berea</span>
            <span style="color: #4a4a4a;">Alexandre Brandao</span>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.querySelector('main .container, main .container-fluid');
        if (container) {
            container.style.opacity = 0;
            setTimeout(function () {
                container.style.transition = 'opacity 0.5s linear';
                container.style.opacity = 1;
            }, 100);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
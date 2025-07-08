    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        // Initialize WOW.js for scroll animations
        new WOW().init();

        // Add animation classes to elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to cards
            document.querySelectorAll('.card').forEach(function(card) {
                card.classList.add('animate__animated', 'animate__fadeIn');
            });

            // Add slide-up animation to buttons
            document.querySelectorAll('.btn').forEach(function(btn) {
                btn.classList.add('animate__animated', 'animate__fadeInUp');
            });

            // Add hover effect to table rows
            document.querySelectorAll('tbody tr').forEach(function(row) {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.transition = 'transform 0.3s ease';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html> 
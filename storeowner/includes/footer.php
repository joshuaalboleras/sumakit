            </div><!-- End of main content -->
        </div><!-- End of row -->
    </div><!-- End of container-fluid -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize DataTables
        $(document).ready(function() {
            // Initialize recent orders table if it exists
            if ($('#recentOrdersTable').length) {
                if (!$.fn.DataTable.isDataTable('#recentOrdersTable')) {
                    $('#recentOrdersTable').DataTable({
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [10, 25, 50, 100],
                        order: [],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search...",
                        }
                    });
                }
            }
            
            // Initialize other datatables with class 'datatable'
            $('.datatable').not('.dataTable').each(function() {
                $(this).DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    order: [],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                    }
                });
            });
        });
    </script>
</body>
</html>

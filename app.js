$(document).ready(function() {
    $('#myTable').DataTable({
        "paging": true,              
        "searching": true,        
        "ordering": true,     
        "info": true,         
        "lengthChange": false,       
        "pageLength": 10,         
        "autoWidth": false,         
        "columnDefs": [
            {
                "targets": [0],      
                "orderable": false   
            }
        ]
    });
});


$(document).ready(function() {
    $('.gallery-item').each(function(index) {
        $(this).delay(index * 200).animate({ opacity: 1 }, 1000);
    });
});


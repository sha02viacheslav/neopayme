<script src="{{ asset('public/backend/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('public/backend/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script>
    //Language script
    $('#lang').on('change', function (e) {
        e.preventDefault();
        lang = $(this).val();
        url = '{{ url('
        change - lang ') }}';
        $.ajax({
            type: 'get',
            url: url,
            data: {
                lang: lang
            },
            success: function (msg) {
                if (msg == 1) {
                    location.reload();
                }
            }
        });
    }); 
</script>

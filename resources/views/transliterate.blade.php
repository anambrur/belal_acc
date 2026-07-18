{{-- <!-- resources/views/transliterate.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Transliteration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Transliterate English to Bengali</h1>
        <form id="transliterateForm" class="mt-3">
            <div class="form-group">
                <label for="englishText">English Text</label>
                <input type="text" class="form-control" id="englishText" name="text"
                    placeholder="Enter English text">
            </div>
        </form>
        <div id="result" class="mt-4">
            <h2>Transliterated Text</h2>
            <p id="banglaText" class="lead"></p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#englishText').on('keyup', function() {
                var englishText = $('#englishText').val();

                if (englishText.trim() !== "") {
                    $.ajax({
                        url: '{{ url('/transliterate') }}',
                        type: 'POST',
                        data: {
                            text: englishText,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#banglaText').text(response.banglaText);
                        }
                    });
                } else {
                    $('#banglaText').text('');
                }
            });
        });
    </script>
</body>

</html> --}}

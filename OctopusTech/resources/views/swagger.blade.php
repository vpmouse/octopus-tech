<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Docs</title>
    <link rel="stylesheet" href="{{ asset('swagger/swagger-ui.css') }}">
</head>
<body>
<div id="swagger-ui"></div>
<script src="{{ asset('swagger/swagger-ui-bundle.js') }}"></script>
<script>
    window.onload = () => {
        window.ui = SwaggerUIBundle({
            url: "{{ asset('swagger/openapi.yaml') }}",
            dom_id: '#swagger-ui',
        });
    };
</script>
</body>
</html>

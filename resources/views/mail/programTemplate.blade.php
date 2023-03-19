<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDAO PROGRAMS</title>
</head>
<body>
    <p>Good Day {{ $pwd->last_name }}, <br><br>
        This is PDAO Cabuyao City. I would say a new program has been added for PWDs entitled <b>{{ $program->program_title }}</b>. The please read the program details are below.
    </p>
    <p>{{ $program->program_description}}</p>
    <h3></h3>
    <p><b>PDAO Cabuyao City</b>
    <br>
    Thank You !
    </p>
</body>
</html>
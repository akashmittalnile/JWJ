<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>{{ $data->title ?? 'Title here' }}</h1>
    <p>{{ $data->content ?? 'Content here' }}</p>
    <p>{{ $data->mood->name ?? 'Mood name' }}</p>
    @foreach($path as $val)
        <img width="120" src="data:image/png;base64,{{ base64_encode(file_get_contents($val['img_path'])) }}" alt="">
    @endforeach
</body>
</html>
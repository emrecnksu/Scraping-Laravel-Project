<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Besinler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/ALLnutrients.css') }}">
</head>
<body>
    <div class="container container-nutrients">
        <div class="container scraping-message mt-2">
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div class="container">
            <h1 class="text-center">Besinler</h1>

            <div class="row justify-content-center mb-4 mt-4">
                @foreach ($foods as $food)
                    <div class="col-sm-4 mb-3">
                        <div class="kkAnasayfaPopulerBesinlerBox text-center">
                            <a class="colorBlack" href="{{ route('GeneralNutrients', ['food' => $food['route']]) }}">
                                <img alt="{{ $food['name'] }}" class="img-fluid rounded mb-2" src="{{ $food['url'] }}">
                                <span class="kkfs16 d-block">{{ $food['name'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div> 
    </div>
</body>
</html>

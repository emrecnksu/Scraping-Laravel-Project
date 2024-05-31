<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $food_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/nutrients.css') }}">
</head>
<body>
    <div class="container cont-a">
        <a class="navbar-brand h1 d-flex justify-content-center align-items-center m-0" href="{{ route('BacktoHomePage') }}">Besinleri Görmek İçin</a>
    </div>

    <div class="container nutrients-numbers mt-2">
        <h1 class="text-center">{{ $food_name }}</h1>
        <div class="text-center mb-4">
            <span class="nut_gr">{{ $gram_value }} gr</span>
        </div>
        <form action="{{ route('GeneralNutrients', ['food' => $food]) }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <input type="number" class="form-control" placeholder="Gram Değeri" aria-label="Gram Değeri" 
                aria-describedby="basic-addon2" name="gram_value" value="{{ $gram_value }}" min="0" max="999" required>
                <button class="btn btn-primary-custom" type="submit">Hesapla</button>
            </div>
        </form>
        
        <div class="row d-flex justify-content-center align-items-center mb-4">
            <div class="col-3 text-center m-1 square">
                <div class="d-inline-block">
                    <span class="kkBigNumber">{{ round($nutrient->kcal) }}</span>
                    <br>
                    <span class="kkfs16">Kcal</span>
                </div>
            </div>
            <div class="col-3 text-center m-1 square">
                <div class="d-inline-block">
                    <span class="kkfsNutrients-carb">Karbonhidrat</span>
                    <br>
                    <span class="kkBigNumber">{{ number_format($nutrient->carbs, 2) }}</span>
                    <br>
                </div>
            </div>
            <div class="col-3 text-center m-1 square">
                <div class="d-inline-block">
                    <span class="kkfsNutrients-protein">Protein</span>
                    <br>
                    <span class="kkBigNumber">{{ number_format($nutrient->protein, 2) }}</span>
                    <br>
                </div>
            </div>
            <div class="col-3 text-center m-1 square">
                <div class="d-inline-block">
                    <span class="kkfsNutrients-fat">Yağ</span>
                    <br>
                    <span class="kkBigNumber">{{ number_format($nutrient->fat, 2) }}</span>
                    <br>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

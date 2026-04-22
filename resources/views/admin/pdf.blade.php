<!DOCTYPE html>
<html>
<head>
    <title>{{ $exam->title }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .exam-title { font-size: 20px; font-bold: bold; text-transform: uppercase; }
        .details { margin-bottom: 20px; }
        .question { margin-bottom: 20px; page-break-inside: avoid; }
        .question-text { font-weight: bold; }
        .options { margin-left: 20px; list-style-type: none; }
        .option-item { margin-bottom: 5px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="exam-title">{{ $exam->title }}</div>
        <div>Tempoh: {{ $exam->duration_minutes }} Minit</div>
    </div>

    <div class="details">
        <p><strong>Arahan:</strong> {{ $exam->instructions ?? 'Jawab semua soalan.' }}</p>
    </div>

    @foreach($exam->questions as $index => $q)
        <div class="question">
            <div class="question-text">
                {{ $index + 1 }}. {{ $q->question_text }}
            </div>

            @if($q->type == 'mcq' && $q->options)
                <div class="options">
                    @foreach($q->options as $key => $value)
                        <div class="option-item">
                            {{ $key }}) {{ $value }}
                        </div>
                    @endforeach
                </div>
            @else
                <div style="margin-top: 10px; border-bottom: 1px dotted #ccc; height: 50px;"></div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
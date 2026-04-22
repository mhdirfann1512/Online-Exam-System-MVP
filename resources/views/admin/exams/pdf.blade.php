<!DOCTYPE html>
<html>
<head>
    <title>Export PDF</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .question { margin-bottom: 20px; }
        .options { margin-left: 20px; list-style-type: none; }
        .answer { color: #2d6a4f; font-weight: bold; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Kertas Soalan: {{ $exam->title }}</h2>
        <p>Masa: {{ $exam->duration_minutes }} Minit</p>
    </div>

    @foreach($exam->questions as $index => $q)
        <div class="question">
            <strong>Soalan {{ $index + 1 }}:</strong> {{ $q->question_text }}
            
            @if(strtolower($q->type) == 'mcq')
                @php
                    // Tukar string JSON kepada array PHP (kalau belum di-cast di Model)
                    $opts = is_array($q->options) ? $q->options : json_decode($q->options, true);
                @endphp
                <ul class="options">
                    <li>A. {{ $opts['A'] ?? '-' }}</li>
                    <li>B. {{ $opts['B'] ?? '-' }}</li>
                    <li>C. {{ $opts['C'] ?? '-' }}</li>
                    <li>D. {{ $opts['D'] ?? '-' }}</li>
                </ul>
            @endif
            
            <div class="answer">Jawapan: {{ $q->correct_answer }}</div>
        </div>
    @endforeach
</body>
</html>
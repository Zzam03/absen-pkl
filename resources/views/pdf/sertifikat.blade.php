
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Praktik Kerja Lapangan</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            font-family: "DejaVu Sans", sans-serif;
            color: #303b4d;
        }

        body {
            background: #fffefa;
        }

        .sheet {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #fffefa;
        }

        /* Bingkai minimalis */
        .outer-border {
            position: absolute;
            top: 8mm;
            left: 8mm;
            width: 281mm;
            height: 194mm;
            border: 1px solid #b99a62;
        }

        .inner-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            width: 277mm;
            height: 190mm;
            border: 0.5px solid #e5dccb;
        }

        .top-accent {
            position: absolute;
            top: 8mm;
            left: 8mm;
            width: 281mm;
            height: 2mm;
            background: #193653;
        }

        .bottom-accent {
            position: absolute;
            bottom: 8mm;
            left: 8mm;
            width: 281mm;
            height: 1mm;
            background: #193653;
        }

        /* Header perusahaan */
        .logo {
            position: absolute;
            top: 18mm;
            left: 27mm;
            width: 23mm;
            height: 23mm;
            object-fit: contain;
        }

        .company {
            position: absolute;
            top: 21mm;
            left: 56mm;
            width: 145mm;
        }

        .company-name {
            font-family: "DejaVu Serif", serif;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #193653;
        }

        .company-subtitle {
            margin-top: 2mm;
            font-size: 7pt;
            letter-spacing: 2.2px;
            color: #a4854f;
        }

        .document-mark {
            position: absolute;
            top: 23mm;
            right: 27mm;
            width: 48mm;
            text-align: right;
        }

        .document-mark .top {
            font-size: 6.5pt;
            letter-spacing: 1.8px;
            color: #a4854f;
            font-weight: bold;
        }

        .document-mark .bottom {
            margin-top: 2mm;
            font-size: 8pt;
            font-weight: bold;
            color: #193653;
            letter-spacing: 0.8px;
        }

        .header-divider {
            position: absolute;
            top: 47mm;
            left: 27mm;
            width: 243mm;
            height: 0.4mm;
            background: #d9c9a9;
        }

        /* Judul utama */
        .eyebrow {
            position: absolute;
            top: 55mm;
            width: 100%;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 2.8px;
            color: #a4854f;
        }

        .title {
            position: absolute;
            top: 61mm;
            width: 100%;
            text-align: center;
            font-family: "DejaVu Serif", serif;
            font-size: 32pt;
            font-weight: bold;
            letter-spacing: 2.5px;
            color: #193653;
        }

        .title-sub {
            position: absolute;
            top: 75mm;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #a4854f;
        }

        /* Identitas peserta */
        .intro {
            position: absolute;
            top: 87mm;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #657083;
        }

        .student-name {
            position: absolute;
            top: 95mm;
            left: 35mm;
            width: 227mm;
            text-align: center;
            font-family: "DejaVu Serif", serif;
            font-size: 26pt;
            font-weight: bold;
            color: #193653;
        }

        .name-divider {
            position: absolute;
            top: 109mm;
            left: 112mm;
            width: 73mm;
            height: 0.5mm;
            background: #b99a62;
        }

        .school {
            position: absolute;
            top: 113mm;
            width: 100%;
            text-align: center;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 1.2px;
            color: #9b7b46;
        }

        .description {
            position: absolute;
            top: 124mm;
            left: 43mm;
            width: 211mm;
            text-align: center;
            font-size: 9pt;
            line-height: 1.8;
            color: #4e596a;
        }

        /* Periode */
        .period-label {
            position: absolute;
            top: 147mm;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #a4854f;
        }

        .period-box {
            position: absolute;
            top: 153mm;
            left: 88mm;
            width: 121mm;
            height: 12mm;
            border: 0.8px solid #c9b487;
            background: #faf7ef;
        }

        .period-date {
            position: absolute;
            top: 156.5mm;
            left: 88mm;
            width: 121mm;
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            color: #193653;
        }

        /* Nomor sertifikat */
        .certificate-number {
            position: absolute;
            top: 181mm;
            left: 27mm;
            width: 100mm;
        }

        .certificate-label {
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #a4854f;
        }

        .certificate-value {
            margin-top: 2mm;
            font-size: 8pt;
            font-weight: bold;
            color: #193653;
        }

        /* Tanda tangan */
        .signature {
            position: absolute;
            top: 169mm;
            right: 27mm;
            width: 75mm;
            text-align: center;
        }

        .signature-date {
            font-size: 8pt;
            color: #657083;
        }

        .signature-space {
            height: 13mm;
        }

        .signature-name {
            font-family: "DejaVu Serif", serif;
            font-size: 10pt;
            font-weight: bold;
            color: #193653;
            text-decoration: underline;
        }

        .signature-position {
            margin-top: 1.5mm;
            font-size: 8pt;
            color: #657083;
        }
    </style>
</head>

<body>

@php
    $studentName = data_get($placement, 'student.name', 'Peserta PKL');

    $schoolName = data_get($placement, 'student.school_name')
        ?? data_get($placement, 'student.school')
        ?? data_get($placement, 'student.asal_sekolah')
        ?? 'SMK BPI';

    $startDate = \Carbon\Carbon::parse($placement->start_date)
        ->locale('id')->translatedFormat('d F Y');

    $endDate = \Carbon\Carbon::parse($placement->end_date)
        ->locale('id')->translatedFormat('d F Y');

    $certificateNumber = $nomor
        ?? 'PKL/'.$placement->end_date->format('Y').'/'.str_pad($placement->id, 4, '0', STR_PAD_LEFT);

    $signatureDate = \Carbon\Carbon::parse($placement->end_date)
        ->locale('id')->translatedFormat('d F Y');
@endphp

<div class="sheet">

    <div class="outer-border"></div>
    <div class="inner-border"></div>
    <div class="top-accent"></div>
    <div class="bottom-accent"></div>

    <img class="logo" src="{{ public_path('images/logo-msn2.png') }}">

    <div class="company">
        <div class="company-name">MEDIA SOLUSI NETWORK</div>
        <div class="company-subtitle">INTERNET SERVICE PROVIDER</div>
    </div>

    <div class="document-mark">
        <div class="top">DOKUMEN RESMI</div>
        <div class="bottom">SERTIFIKAT PKL</div>
    </div>

    <div class="header-divider"></div>

    <div class="eyebrow">PENGHARGAAN DAN APRESIASI</div>

    <div class="title">SERTIFIKAT</div>

    <div class="title-sub">PRAKTIK KERJA LAPANGAN</div>

    <div class="intro">
        Dengan ini menyatakan bahwa:
    </div>

    <div class="student-name">
        {{ $studentName }}
    </div>

    <div class="name-divider"></div>

    <div class="school">
        {{ $schoolName }}
    </div>

    <div class="description">
        Telah menyelesaikan kegiatan Praktik Kerja Lapangan (PKL)
        di <strong>MEDIA SOLUSI NETWORK</strong> dengan baik.
        Sertifikat ini diberikan sebagai bentuk penghargaan atas
        partisipasi dan penyelesaian kegiatan praktik kerja lapangan.
    </div>

    <div class="period-label">PERIODE PELAKSANAAN</div>

    <div class="period-box"></div>

    <div class="period-date">
        {{ $startDate }} &nbsp;&nbsp; — &nbsp;&nbsp; {{ $endDate }}
    </div>

    <div class="certificate-number">
        <div class="certificate-label">NOMOR SERTIFIKAT</div>
        <div class="certificate-value">{{ $certificateNumber }}</div>
    </div>

    <div class="signature">
        <div class="signature-date">
            Bandung, {{ $signatureDate }}
        </div>

        <div class="signature-space"></div>

        <div class="signature-name">YUDIANA ARIFIN</div>

        <div class="signature-position">Direktur Utama</div>
    </div>

</div>

</body>
</html>

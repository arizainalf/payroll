<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    @php

    @endphp
    <h2>Slip Gaji</h2>
    <p><strong>NIP:</strong> {{ $nip }}</p>
    <p><strong>Nama:</strong> {{ $nama }}</p>
    <p><strong>Jabatan:</strong> {{ $jabatan }}</p>
    <p><strong>Tahun Masuk:</strong> {{ $tahun_masuk }}</p>

    <table>
        <tr>
            <th>Gaji Pokok</th>
            <th>Total Gaji</th>
        </tr>
        <tr>
            <td>Rp {{ number_format(1000000, 0, ',', '.') }}</td>
            <td>Rp {{ number_format(1500000, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>

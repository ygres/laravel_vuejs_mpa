<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table tbody tr {
            border: 1px solid #8c8c8c;
        }
    </style>
</head>
<body>
<h1>Список договоров</h1>
<table>
    <thead>
    <tr>
        <th>Номер договора</th>
        <th>ФИО</th>
        <th>День рождение</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order as $customer)
        <tr style="border: 1px solid black">
            <td style="width: 150px">{{ $customer->number }}</td>
            <td>{{ $customer->fio }}</td>
            <td style="width: 15px;">{{ $customer->birth_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

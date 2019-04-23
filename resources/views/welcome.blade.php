@extends('layouts.app')

@section('content')

        <div class="row">
            <div class="col-md-12">
                <form method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-6">
                        <input name="number" type="text" class="form-control form-control-lg" id="numberOrder" aria-describedby="numberOrder" placeholder="Номер договора">
                    </div>

                    <button type="submit" class="btn btn-primary">Найти</button>
                </form>
            </div>
        </div>

        @if(isset($order))
            <hr>
        <div class="container">
            <table class="table table-hover">
                <thead class="bg-info">
                    <tr>
                        <td><b>№</b></td>
                        <td><b>ФИО</b></td>
                        <td><b>Дата рождения</b></td>
                        <td><b>Фото</b></td>
                    </tr>
                </thead>
                <tbody>
                @foreach ($order as $item)
                    <tr>
                        <td>{{ $item->number  }}</td>
                        <td>{{ $item->fio }}</td>
                        <td>{{  \Carbon\Carbon::parse($item->birth_date)->format('j F, Y') }}</td>
                        <td>@if($item->file) <img
                                    class="pointer-image"
                                    data-toggle="popover"
                                    data-placement="right"
                                    data-html="true"
                                    data-content="<div><img width='150px' src={{ 'images/upload/' . $item->file  }}></div>"
                                    src={{ "images/upload/" . $item->file  }} alt=""
                                    width="25px"> @endif</td>
                    </tr>
                </tbody>
                @endforeach
            </table>

            <div >
                <div class="float-right"><a href="/export_pdf">Экпорт PDF</a></div>
                <div class="float-right"><a href="/export_xls">Экпорт XLS</a></div>
                <div class="float-right"><a href="/export_csv">Экспорт CSV</a></div>
            </div>

        </div>
        @endif

@endsection

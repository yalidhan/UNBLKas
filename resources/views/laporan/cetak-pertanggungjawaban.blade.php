<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="/assets/image/png" sizes="16x16" href="/assets/images/unbl.png">

    <style>
        table.static{
            position: relative;
            border:1px solid black;
        }
        table td{
            padding: 5px;
        }
    </style>
    <title>Cetak Lembar Pertanggungjawaban</title>
</head>
<body>
    <div class="form-group">
        <h2 align="center"><b>LAPORAN PERTANGGUNGJAWABAN UANG OPERASIONAL</b></h2>
        <h3 align="center">"{{$departementNama->nama}}"</h3>
        <h4 align="center">Periode {{\Carbon\Carbon::parse($periode['dari'])->format('d F Y')}} - {{\Carbon\Carbon::parse($periode['sampai'])->format('d F Y')}}</h4>
        <table class="static" align="center" rules="all">
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;border:3px solid black;">
                    <td>Tanggal</td>
                    <td>Keterangan</td>
                    <td>Kode Akun</td>
                    <td>Rincian</td>
                    <td>Debet</td>
                    <td>Kredit</td>
                    <td>Saldo</td>
                    <td>No.SPB</td>
                </tr>
            </thead>
            @php $saldo = 0 ;
                $saldo=$saldo+$saldoLastMonth;
                $debitKeseluruhan=0;
                $kreditKeseluruhan=0;
            @endphp
                <tr>
                    <td colspan="6"><b>Saldo Awal</b></td>
                    <td style="text-align:left;white-space: nowrap;" ><b>Rp {{number_format($saldoLastMonth,0,',','.')}}</b></td>
                </tr>
                @foreach ($transactionList as $transaction)     
                @php
                    $detailTransaction=DB::select(
                                "SELECT t.id, t.keterangan,d.account_id,d.nominal, a.nama,a.no 
                                FROM transactions t 
                                LEFT JOIN transaction_details d 
                                    ON t.id = d.transaction_id 
                                LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                                WHERE t.departement_id=$transaction->departement_id and t.id=$transaction->id and tanggal BETWEEN '2023-11-01' and '2023-11-30' ORDER BY tanggal ASC, id ASC;"
                            );
                    $arrayCount=count($detailTransaction);
                @endphp
                <tr>
                    <td rowspan="{{$arrayCount+1}}">{{\Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y')}}</td>
                    <td rowspan="{{$arrayCount+1}}">{{$transaction->keterangan}} <span style="font-size:11px;color: #8898aa !important;"></span></td>
                        <td style="padding: 0px;border-bottom-style: hidden;"></td>
                        <td style="padding: 0px;border-bottom-style: hidden;"></td>
                    @if ($transaction->dk==1)
                        <td rowspan="{{$arrayCount+1}}" style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                            $saldo=$saldo+$transaction->total,
                            $debitKeseluruhan=$debitKeseluruhan+$transaction->total}}</td>
                    @else 
                        <td rowspan="{{$arrayCount+1}}" style="white-space: nowrap;">Rp 0</td>
                    @endif
                    @if ($transaction->dk==2)
                        <td rowspan="{{$arrayCount+1}}" style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                            $saldo=$saldo-$transaction->total,
                            $kreditKeseluruhan=$kreditKeseluruhan+$transaction->total}}</td>
                    @else 
                        <td rowspan="{{$arrayCount+1}}"style="white-space: nowrap;">Rp 0</td>
                    @endif
                    <td rowspan="{{$arrayCount+1}}"style="white-space: nowrap;">Rp {{number_format($saldo,0,',','.')}}</td>
                    <td rowspan="{{$arrayCount+1}}">{{$transaction->no_spb}}</td>            
                </tr>
                    @foreach ($detailTransaction as $value)
                    <tr>
                        <td>{{$value->no}}</td>
                        <td>{{$value->nama}}
                            <br>(Rp {{number_format($value->nominal,0,',','.')}})
                        </td>
                    </tr>
                    @endforeach                    
                @endforeach
                <tr align="center" style="font-size:large;font-weight:bold;border:3px solid black;">
                    <td colspan="4">SISA KESELURUHAN</td>
                    <td style="white-space: nowrap;">Rp {{number_format($debitKeseluruhan,0,',','.')}}</td>
                    <td style="white-space: nowrap;">Rp {{number_format($kreditKeseluruhan,0,',','.')}}</td>
                    <td colspan="2" style="white-space: nowrap;">Rp {{number_format($saldo,0,',','.')}}</td>
                </tr>
                <tr style="border-left-style: hidden;">
                    <td style="border-right-style: hidden;" colspan="4"></td>
                    <td style="border-right-style: hidden;" colspan="4"><br>Banjarbaru, 31 Juli 2023<br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">Penanggungjawab</td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">Bendahara Operasional<br><br><br><br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">Muhammad Dedek Yalidhan, S.Kom</td>
                </tr>
        </table>
    </div>
</body>
</html>
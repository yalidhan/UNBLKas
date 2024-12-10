<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="/assets/image/png" sizes="16x16" href="/assets/images/unbl.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Tooltip container */


        /* Tooltip text */
        .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #ffffff;
        text-align: center;
        padding: 5px 0;
        border-radius: 6px;
        
        /* Position the tooltip text - see examples below! */
        position: absolute;
        z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
        visibility: visible;
        }
        table{
            border-collapse: collapse; 
            -webkit-print-color-adjust: exact; 
        }
        table td{
            padding: 5px;
        }
    </style>
    <title>Cetak Laporan Posisi Kas</title>
</head>
<body>
    <div class="form-group">
        <div class="tooltip"><button onClick="window.print()"><i class="fa fa-print" style="font-size:34px;"></i></button>
            <span class="tooltiptext">Cetak Laporan</span>
        </div>   
        <h2 align="center"><b>Laporan Posisi Kas</b></h2>                  
        <h4 align="center">Periode {{\Carbon\Carbon::parse($sd2)->format('F Y')}}</h4>

        <table class="static" align="center" >
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;">
                    <td style="border-bottom:3pt solid black">Departemen</td>
                    <td style="border-bottom:3pt solid black;"></td>
                    <td style="border-bottom:3pt solid black;">Posisi Saldo</td>
                </tr>
            </thead>
            @php 
                $totalSaldo=0;
            @endphp
            @foreach ($departements as $departement)
            <tr>
                <td >{{$departement->nama}}</td>
                <td style="border-bottom:1pt solid black;white-space: nowrap;">Rp</td>
                @php
                $departement_id=$departement->id;             
                    $saldoDebit=DB::select(
                        "
                        SELECT 
                            t.departement_id, 
                            SUM(d.nominal) AS total_debit 
                        FROM 
                            transactions t 
                        LEFT JOIN 
                            transaction_details d ON t.id = d.transaction_id 
                        WHERE 
                            t.departement_id = $departement_id 
                            AND t.tanggal <= '$lastdateperiode' 
                            AND d.dk = 1 
                        GROUP BY 
                            t.departement_id;
                    ");
                    $saldoKredit=DB::select(
                        "
                        SELECT 
                            t.departement_id, 
                            SUM(d.nominal) AS total_kredit 
                        FROM 
                            transactions t 
                        LEFT JOIN 
                            transaction_details d ON t.id = d.transaction_id 
                        WHERE 
                            t.departement_id = $departement_id 
                            AND t.tanggal <= '$lastdateperiode' 
                            AND d.dk = 2 
                        GROUP BY 
                            t.departement_id;
                    ");
                    
                    $totalDebit = !empty($saldoDebit) ? $saldoDebit[0]->total_debit : 0;
                    $totalKredit = !empty($saldoKredit) ? $saldoKredit[0]->total_kredit : 0;
                    $saldoAkhir = $totalDebit - $totalKredit;
                    $totalSaldo=$totalSaldo+$saldoAkhir;
                    
                @endphp 
                <td style="border-bottom:1pt solid black;white-space: nowrap;"align="right">{{number_format($saldoAkhir,0,',','.')}}</td>
            </tr>
            @endforeach

            <tr style="color:#000080;">
                    <td align="left"><b>Total Saldo </b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;">Rp</td>
                    <td align="right" style="border-top:1pt solid black;white-space: nowrap;"><b>{{number_format($totalSaldo,0,',','.')}}</b></td>
            </tr>
        </table>

    </div>
</body>
</html>
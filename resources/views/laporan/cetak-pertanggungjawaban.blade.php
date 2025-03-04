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
        <div class="tooltip"><button onClick="window.print()"><i class="fa fa-print" style="font-size:34px;"></i></button>
            <span class="tooltiptext">Cetak Laporan</span>
    </div>   
        <h2 align="center"><b>LAPORAN PERTANGGUNGJAWABAN UANG OPERASIONAL</b></h2>
        <h2 align="center">{{$departementNama->pusat}}</h2>
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
                                "SELECT t.no_trf,t.id, t.keterangan,d.account_id,d.dk,d.nominal, a.nama,a.no,a.tipe 
                                FROM transactions t 
                                LEFT JOIN transaction_details d 
                                    ON t.id = d.transaction_id 
                                LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                                WHERE t.departement_id=$transaction->departement_id and t.id=$transaction->id and tanggal BETWEEN '$periode[dari]' and '$periode[sampai]' ORDER BY tanggal ASC, id ASC;"
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
                        <td style="white-space: nowrap;">{{$value->no}} || {{$value->tipe}}</td>
                        @if ($value->no_trf !==null && $value->dk==2 )
                            @php
                            \DB::statement("SET SQL_MODE=''");
                            $no_trf=$value->no_trf;
                            $droppingValue=DB::select(
                                "SELECT t.no_trf,t.id, t.keterangan,d.account_id,d.dk,d.nominal, a.nama,a.no,a.tipe 
                                FROM transactions t 
                                LEFT JOIN transaction_details d 
                                    ON t.id = d.transaction_id 
                                LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                                WHERE t.no_trf='$no_trf'
                                ORDER BY t.id DESC limit 1;"
                            );  
                            @endphp       
                        <td>{{$droppingValue[0]->nama}}
                            <br>(Rp {{number_format($droppingValue[0]->nominal,0,',','.')}})
                        </td>                   
                        @else
                        <td>{{$value->nama}}
                            <br>(Rp {{number_format($value->nominal,0,',','.')}})
                        </td>
                        @endif
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
                    <td style="border-right-style: hidden;" colspan="4"><br>Banjarbaru, {{\Carbon\Carbon::parse($periode['sampai'])->format('d F Y')}}<br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">Penanggungjawab</td>
                </tr>
                <?php 
                    $pejabatQuery=DB::select(
                        "SELECT u.name,u.jabatan,u.status,
                                d.nama 
                        FROM users u
                        LEFT JOIN departements d ON u.departement_id=d.id
                        where u.jabatan LIKE 'Bendahara%' AND u.status=1 AND u.departement_id=$departement_id"
                    );
                    if(!empty($pejabatQuery)){
                        $jabatan=$pejabatQuery[0]->jabatan;
                        $nama=$pejabatQuery[0]->name;
                    }else{
                        $jabatan="Bendahara Operasional";
                        $nama="VACANT";                    
                    }

                ?>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">{{$jabatan}}<br><br><br><br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="4"></td>
                    <td style="border-style: hidden;" colspan="4">{{$nama}}</td>
                </tr>
        </table>
    </div>
</body>
</html>
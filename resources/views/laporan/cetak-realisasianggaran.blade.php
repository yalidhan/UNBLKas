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
    <title>Cetak Lembar Pertanggungjawaban</title>
</head>
<body>
    <div class="form-group">
        <div class="tooltip"><button onClick="window.print()"><i class="fa fa-print" style="font-size:34px;"></i></button>
            <span class="tooltiptext">Cetak Laporan</span>
        </div>   
        <h2 align="center"><b>LAPORAN REALISASI ANGGARAN</b></h2>                  
            @if (!empty($departement->nama))
            <h2 align="center">{{$departement->pusat}}</h2>
            @else 
                
            @endif    
        <h3 align="center">"
            @if (!empty($departement->nama))
                {{$departement->nama}}
            @else 
                {{$departement['nama']}}
            @endif
            "</h3>
        <h4 align="center">Sampai Dengan {{$sd}}</h4>

        <table class="static" align="center" border="0" >
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;">
                    <td></td>
                    <td></td>
                    <td style="border-bottom:3pt solid black;">Pagu Anggaran</td>
                    <td style="border-bottom:3pt solid black;">Relasisasi</td>
                    <td style="border-bottom:3pt solid black;">%</td>
                    <td style="border-bottom:3pt solid black;">Sisa Anggaran</td>
                    <td style="border-bottom:3pt solid black;">%</td>
                </tr>
                @php $total_anggaran=0;$gt_anggaran=0;$total_transaksi=0;$gt_transaksi=0; @endphp
                @foreach ($kelompok as $kelompokvalue)
                <tr>
                    <td style="color:#000080;"colspan="5" align="left"><b>{{$kelompokvalue->kelompok}}</b></td>
                </tr>
                @php 
                    $detail_anggaran=DB::select(
                        "SELECT bd.budget_id,bd.id,bd.account_id,bd.nominal,
                            a.kelompok,a.nama,a.no
                        FROM budget_details bd
                        LEFT JOIN accounts a
                            ON bd.account_id = a.id
                        WHERE budget_id=$kelompokvalue->budget_id AND kelompok='$kelompokvalue->kelompok'
                        ORDER BY account_id ASC"
                        );
                              
                @endphp
                @foreach ($detail_anggaran as $da_value)
                <?php 
                    if (!empty($departement->id)){                    
                    $departement_sql="t.departement_id=$departement->id";}
                    else{    
                    $dp_id=$departement['id'];                
                    $departement_sql="t.departement_id!=$dp_id";}
                ?>
                @php
                    $transaksi=DB::select(
                            "SELECT t.id,t.tanggal, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE $departement_sql AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id"
                            );    
                @endphp
                
                <tr <?php if(empty($transaksi[0]->total)){echo" ";} elseif ($transaksi[0]->total > $da_value->nominal){echo "style='background-color:#ff0000;color:#ffffff;'";}?>>
                        <td></td>
                        <td>{{$da_value->nama}}</td>
                        <td style="white-space: nowrap;">Rp {{number_format($da_value->nominal,0,',','.')}}</td>
                        <td style="white-space: nowrap;">Rp <?php if(empty($transaksi[0]->total)){$transaksi=0;} else {$transaksi=$transaksi[0]->total;}?>{{number_format($transaksi,0,',','.'),$total_transaksi=$total_transaksi+$transaksi}}</td>
                        <td align="center"style="white-space: nowrap;">{{number_format(($transaksi/$da_value->nominal)*100, 2, '.', ',')}} %</td>
                        <td style="white-space: nowrap;">Rp {{number_format(($da_value->nominal-$transaksi),0,',','.')}}</td>
                        <td style="white-space: nowrap;">{{100-number_format(($transaksi/$da_value->nominal)*100, 2, '.', ',')}} %</td>
                    </tr>

                @endforeach
                    <!-- <tr>
                        <td></td>
                        <td style="background-color:#ff0000;color:#ffffff;">Biaya Perjalanan Dinas</td>
                        <td style="background-color:#ff0000;color:#ffffff;">Rp 100.000.000</td>
                        <td style="background-color:#ff0000;color:#ffffff;">Rp 110.000.000</td>
                        <td align="center" style="background-color:#ff0000;color:#ffffff;">110%</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Penyediaan Barang Cetak dan Pengadaan</td>
                        <td>Rp 300.000.000</td>
                        <td>Rp 150.000.000</td>
                        <td align="center">50%</td>
                    </tr> -->
                <tr style="color:#000080;">
                    <td colspan="2" align="left"><b>Total {{$kelompokvalue->kelompok,$total_anggaran=$total_anggaran+$kelompokvalue->total,$gt_anggaran=$gt_anggaran+$total_anggaran,$gt_transaksi=$gt_transaksi+$total_transaksi}}</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format($kelompokvalue->total,0,',','.')}}</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format($total_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:1pt solid black;" align="center"><b>{{number_format(($total_transaksi/$total_anggaran)*100, 2, '.', ',')}}% </b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format(($kelompokvalue->total-$total_transaksi),0,',','.')}}</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>{{100-number_format(($total_transaksi/$total_anggaran)*100, 2, '.', ','),$total_transaksi=0,$total_anggaran=0}} %</b></td>
                </tr>
                @endforeach
                <tr style="color:#ff6347;">
                    <td colspan="2" style="font-size:18px;" align="left"><b>Total Keseluruhan</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format($gt_anggaran,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format($gt_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if($gt_anggaran==0)
                            0
                        @else
                        {{number_format(($gt_transaksi/$gt_anggaran)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(($gt_anggaran-$gt_transaksi),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if($gt_anggaran==0)
                                0
                        @else   
                            {{100-number_format(($gt_transaksi/$gt_anggaran)*100, 2, '.', ',')}}                         @endif
                    %</b></td>
                </tr>
                <tr style="border-left-style: hidden;">
                    <td colspan="3" style="border-right-style: hidden;" ></td>
                    <td colspan="2" style="border-right-style: hidden;" ><br>Banjarbaru, {{\Carbon\Carbon::now()->format('d F Y')}}<br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="3"></td>
                    <td colspan="2" style="border-style: hidden;">Penanggungjawab</td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="3"></td>
                    <td colspan="2" style="border-style: hidden;">{{auth()->user()->jabatan}}<br><br><br><br><br></td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="3"></td>
                    <td colspan="2" style="border-style: hidden;">{{auth()->user()->name}}</td>
                </tr>                              
            </thead>
        </table>

    </div>
</body>
</html>
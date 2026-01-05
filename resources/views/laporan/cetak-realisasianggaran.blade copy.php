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
    <title>Cetak Laporan Realisasi Anggaran</title>
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
        <h4 align="center">Sampai Dengan {{\Carbon\Carbon::parse($sd2)->format('d F Y')}}</h4>

        <table class="static" align="center" border="0" >
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;">
                    <td></td>
                    <td></td>
                    <td style="border-bottom:3pt solid black;">Pagu Anggaran</td>
                    <td style="border-bottom:3pt solid black;">Realisasi</td>
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
                    if (!empty($departement->id)){
                        $detail_anggaran=DB::select(
                            "SELECT bd.budget_id,bd.id,bd.account_id,bd.nominal,
                                a.kelompok,a.nama,a.no
                            FROM budget_details bd
                            LEFT JOIN accounts a
                                ON bd.account_id = a.id
                            WHERE budget_id=$kelompokvalue->budget_id AND kelompok='$kelompokvalue->kelompok'
                            ORDER BY account_id ASC"
                            );}
                    elseif ($departement['id']=='0'){
                        $detail_anggaran=DB::select(
                            "SELECT
                                bd.budget_id,bd.id,bd.account_id,sum(bd.nominal) as nominal,
                                b.tahun,b.departement_id,
                                a.kelompok,a.nama,a.no
                            FROM budget_details bd
                            LEFT JOIN accounts a
                                ON bd.account_id = a.id
                            LEFT JOIN budgets b
                                ON bd.budget_id = b.id
                            WHERE tahun=$tahun AND kelompok='$kelompokvalue->kelompok' AND b.departement_id NOT IN (1,18,19,20,21)
                            GROUP BY a.nama
                            ORDER BY account_id ASC"
                            );
                    }
                    elseif ($departement['id']=='1'){
                        $detail_anggaran=DB::select(
                            "SELECT
                                bd.budget_id,bd.id,bd.account_id,sum(bd.nominal) as nominal,
                                b.tahun,b.departement_id,
                                a.kelompok,a.nama,a.no
                            FROM budget_details bd
                            LEFT JOIN accounts a
                                ON bd.account_id = a.id
                            LEFT JOIN budgets b
                                ON bd.budget_id = b.id
                            WHERE tahun=$tahun AND kelompok='$kelompokvalue->kelompok' AND b.departement_id IN (1,19,20,21)
                            GROUP BY a.nama
                            ORDER BY account_id ASC"
                            );
                    }

                              
                @endphp
                @foreach ($detail_anggaran as $da_value)
                <?php 
                    if (!empty($departement->id)){                    
                    $departement_sql="t.departement_id=$departement->id";}
                    else{    
                    $dp_id=$departement['id'];                
                    $departement_sql="t.departement_id!=$dp_id";}
                    // dd($departement['id']);
                    
                ?>
                @php
                if (!empty($departement->id)){
                    $transaksi=DB::select(
                            "SELECT t.id,t.tanggal,d.dk, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE $departement_sql AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=2"
                            );
                    $transaksiPengembalian=DB::select(
                        "SELECT t.id,t.tanggal,d.dk, d.account_id,sum(d.nominal) AS total,
                            a.id,a.no,a.kelompok 
                        FROM transactions t 
                        LEFT JOIN transaction_details d 
                            ON t.id = d.transaction_id 
                        LEFT JOIN accounts a 
                            ON d.account_id = a.id 
                        WHERE $departement_sql AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=1"
                        );                    
                    }
                elseif ($departement['id']=='0'){
                    $transaksi=DB::select(
                            "SELECT t.id,t.tanggal,t.departement_id, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE departement_id NOT IN (1,18,19,20,21) AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=2"
                            );
                    
                    $transaksiPengembalian=DB::select(
                            "SELECT t.id,t.tanggal,t.departement_id,d.dk, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE departement_id NOT IN (1,18,19,20,21) AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=1"
                            );
                    }
                    elseif ($departement['id']=='1'){
                    $transaksi=DB::select(
                            "SELECT t.id,t.tanggal,t.departement_id, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE departement_id IN (1,19,20,21) AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=2"
                            );
                    
                    $transaksiPengembalian=DB::select(
                            "SELECT t.id,t.tanggal,t.departement_id,d.dk, d.account_id,sum(d.nominal) AS total,
                                a.id,a.no,a.kelompok 
                            FROM transactions t 
                            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
                            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
                            WHERE departement_id IN (1,19,20,21) AND tanggal BETWEEN '$tahun-01-01' and '$sd2' AND account_id=$da_value->account_id AND dk=1"
                            );
                    }
                @endphp
                
                <tr <?php if(empty((int)$transaksi[0]->total)){echo" ";} elseif ((int)$transaksi[0]->total-(int)$transaksiPengembalian[0]->total > (int)$da_value->nominal){echo "style='background-color:#ff0000;color:#ffffff;'";}?>>
                        <td></td>
                        <td>
                        <?php if(!empty($departement->id)){
                            echo"<a href='logtransaksi?thn=$tahun"."&sd=$sd2"."&akn=$da_value->account_id"."&dp=$departement->id' style='color:#000;text-decoration:none;'>$da_value->nama</a>";
                        }else if($departement['id']=='1'){
                            echo"<a href='logtransaksi?thn=$tahun"."&sd=$sd2"."&akn=$da_value->account_id"."&dp=1' style='color:#000;text-decoration:none;'>$da_value->nama</a>";
                        }else{
                            echo $da_value->nama;
                        }
                    ?>
                        </td>
                        <td style="white-space: nowrap;">Rp {{number_format((int)$da_value->nominal,0,',','.')}}</td>
                        <td style="white-space: nowrap;">Rp <?php if(empty($transaksi[0]->total)){(int)$transaksi=0;} else {(int)$transaksi=(int)$transaksi[0]->total-(int)$transaksiPengembalian[0]->total;}?>{{number_format((int)$transaksi,0,',','.'),(int)$total_transaksi=(int)$total_transaksi+(int)$transaksi}}</td>
                        <td align="center"style="white-space: nowrap;"><?php if((int)$da_value->nominal!=0){echo number_format(((int)$transaksi/(int)$da_value->nominal)*100, 2, '.', ',');}else{echo"0";} ?> %</td>
                        <td style="white-space: nowrap;">Rp {{number_format(((int)$da_value->nominal-(int)$transaksi),0,',','.')}}</td>
                        @php
                            $persentase=0;
                            if ((int)$da_value->nominal!=0){
                                $persentase=100-(((int)$transaksi/(int)$da_value->nominal)*100);
                            }
                        @endphp
                        <td style="white-space: nowrap;">{{ number_format($persentase, 2, '.', ',') }} %</td>
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
                    <td colspan="2" align="left"><b>Total {{$kelompokvalue->kelompok,(int)$total_anggaran=(int)$total_anggaran+(int)$kelompokvalue->total,(int)$gt_anggaran=(int)$gt_anggaran+(int)$total_anggaran,(int)$gt_transaksi=(int)$gt_transaksi+(int)$total_transaksi}}</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$kelompokvalue->total,0,',','.')}}</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$total_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:1pt solid black;" align="center"><b><?php if((int)$total_anggaran!=0){echo number_format(((int)$total_transaksi/(int)$total_anggaran)*100, 2, '.', ',');}else{echo"0";} ?> %</b></td>
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$kelompokvalue->total-(int)$total_transaksi),0,',','.')}}</b></td>
                    @php
                            $persentaseTotal=0;
                            if ((int)$total_anggaran!=0){
                                $persentaseTotal=100-(((int)$total_transaksi/(int)$total_anggaran)*100);
                            }
                        @endphp
                    <td style="border-top:1pt solid black;white-space: nowrap;"><b>{{ number_format($persentaseTotal, 2, '.', ',') }} %</b><?php $total_transaksi=0;$total_anggaran=0;?></td>
                </tr>
                @endforeach
                <tr style="color:#ff6347;">
                    <td colspan="2" style="font-size:18px;" align="left"><b>Total RKA</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_anggaran,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if((int)$gt_anggaran==0)
                            0
                        @else
                        {{number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$gt_anggaran-(int)$gt_transaksi),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if((int)$gt_anggaran==0)
                                0
                        @else   
                            {{100-number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}                         
                        @endif
                        %</b>
                    </td>
                </tr>
                <tr style="color:#ff6347;">
                    <td colspan="2" style="font-size:18px;" align="left"><b>Total NON RKA</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_anggaran,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if((int)$gt_anggaran==0)
                            0
                        @else
                        {{number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$gt_anggaran-(int)$gt_transaksi),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if((int)$gt_anggaran==0)
                                0
                        @else   
                            {{100-number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}                         
                        @endif
                        %</b>
                    </td>
                </tr>
                <tr style="color:rgb(255, 172, 9);">
                    <td colspan="2" style="font-size:24px;" align="left"><b>Total Keseluruhan</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_anggaran,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_transaksi,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if((int)$gt_anggaran==0)
                            0
                        @else
                        {{number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$gt_anggaran-(int)$gt_transaksi),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if((int)$gt_anggaran==0)
                                0
                        @else   
                            {{100-number_format(((int)$gt_transaksi/(int)$gt_anggaran)*100, 2, '.', ',')}}                         
                        @endif
                        %</b>
                    </td>
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
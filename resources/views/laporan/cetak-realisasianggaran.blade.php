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
        <!-- <div class="tooltip"><button onClick="window.print()"><i class="fa fa-print" style="font-size:34px;"></i></button>
            <span class="tooltiptext">Cetak Laporan</span>
        </div>    -->
        <div style="display:flex; gap:10px; margin-bottom:10px;">
            <button onclick="window.print()" title="Cetak Laporan">
                <i class="fa fa-print" style="font-size:30px;"></i>
            </button>

            <a href="{{ route('realisasi.csv', request()->all()) }}"
            class="btn btn-success">
                <i class="fa fa-file-csv"></i> Download CSV
            </a>
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
                
                <tr 
                <?php
                    $debit  = !empty($transaksi[0]->total) ? (int)$transaksi[0]->total : 0;
                    $kredit = !empty($transaksiPengembalian[0]->total) ? (int)$transaksiPengembalian[0]->total : 0;

                    // realisasi
                    $transaksiValue = $debit - $kredit;

                    // background warning if melebihi anggaran
                    if($transaksiValue > (int)$da_value->nominal){
                        echo "style='background-color:#ff0000;color:#ffffff;'";
                    }
                ?>
                >
                    <td></td>

                    <td>
                        <?php 
                            if(!empty($departement->id)){
                                echo"<a href='logtransaksi?thn=$tahun&sd=$sd2&akn=$da_value->account_id&dp=$departement->id' style=\"color:#000;text-decoration:none;\">$da_value->nama</a>";
                            }elseif($departement['id']=='1'){
                                echo"<a href='logtransaksi?thn=$tahun&sd=$sd2&akn=$da_value->account_id&dp=1' style=\"color:#000;text-decoration:none;\">$da_value->nama</a>";
                            }else{
                                echo $da_value->nama;
                            }
                        ?>
                    </td>

                    {{-- Budget --}}
                    <td style="white-space: nowrap;">
                        Rp {{ number_format((int)$da_value->nominal,0,',','.') }}
                    </td>

                    {{-- Realisasi --}}
                    <td style="white-space: nowrap; {{ $transaksiValue < 0 ? 'background:rgb(255, 172, 9);color:#fff;' : '' }}">

                        Rp {{ number_format($transaksiValue,0,',','.') }}
                        @php
                            $total_transaksi = (int)$total_transaksi + (int)$transaksiValue;
                        @endphp
                    </td>

                    {{-- Persentase Realisasi --}}
                    <td align="center" style="white-space: nowrap;">
                        @php 
                            $persen = 0;
                            if((int)$da_value->nominal > 0){
                                $persen = ($transaksiValue / (int)$da_value->nominal) * 100;
                            }
                        @endphp
                        {{ number_format($persen, 2, '.', ',') }} %
                    </td>

                    {{-- Sisa Anggaran --}}
                    <td style="white-space: nowrap;">
                        Rp {{ number_format((int)$da_value->nominal - $transaksiValue,0,',','.') }}
                    </td>

                    {{-- Persentase Sisa --}}
                    @php
                        $persentase = 0;
                        if((int)$da_value->nominal > 0){
                            $persentase = 100 - $persen;
                        }
                    @endphp
                    <td style="white-space: nowrap;">
                        {{ number_format($persentase, 2, '.', ',') }} %
                    </td>

                </tr>

                @endforeach
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
                    <td colspan="2" style="font-size:18px;" align="left"><b>TOTAL RKA</b></td>
                    @php
                        $k = collect($kelompok);

                        $gt_anggaran_rka = $k->where('kelompok','!=','Non RKA')->sum('total');
                        $gt_anggaran_non = $k->where('kelompok','Non RKA')->sum('total');

                        // =============================
                        // STRICT TRANSAKSI (Only if in Budget)
                        // =============================
                        $deptFilter = "";

                        if (!empty($departement->id)) {
                            $deptFilter = " AND b.departement_id = {$departement->id}";
                        } elseif ($departement['id'] == '0') {
                            $deptFilter = " AND b.departement_id NOT IN (1,18,19,20,21)";
                        } elseif ($departement['id'] == '1') {
                            $deptFilter = " AND b.departement_id IN (1,19,20,21)";
                        }

                        $transaksiKelompok = collect(DB::select("
                            SELECT 
                                a.kelompok,

                                -- REALISASI = Debit - Kredit
                                SUM(
                                    CASE 
                                        WHEN d.dk = 2 THEN d.nominal 
                                        WHEN d.dk = 1 THEN -d.nominal
                                        ELSE 0 
                                    END
                                ) AS total

                            FROM transactions t
                            JOIN transaction_details d 
                                ON t.id = d.transaction_id
                            JOIN accounts a 
                                ON d.account_id = a.id
                            JOIN budgets b 
                                ON b.departement_id = t.departement_id
                                AND b.tahun = $tahun

                            -- ensure ONLY accounts that exist in budget_details are counted
                            WHERE EXISTS (
                                SELECT 1 
                                FROM budget_details bd 
                                WHERE bd.account_id = a.id 
                                AND bd.budget_id = b.id
                            )
                            $deptFilter
                            AND t.tanggal BETWEEN '$tahun-01-01' AND '$sd2'

                            GROUP BY a.kelompok
                        "));
                        $debug = DB::select("
                            SELECT 
                                t.id AS transaksi_id,
                                t.tanggal,
                                a.kelompok,
                                a.nama AS akun,
                                d.dk,
                                d.nominal
                            FROM transactions t
                            JOIN transaction_details d 
                                ON t.id = d.transaction_id
                            JOIN accounts a 
                                ON d.account_id = a.id
                            JOIN budgets b 
                                ON b.departement_id = t.departement_id 
                                AND b.tahun = $tahun
                            WHERE a.kelompok = 'Non RKA'
                            AND EXISTS (
                                SELECT 1 FROM budget_details bd 
                                WHERE bd.account_id = a.id 
                                AND bd.budget_id = b.id
                            )
                            AND b.departement_id=1
                            AND t.tanggal BETWEEN '$tahun-01-01' AND '$sd2'
                            ORDER BY t.tanggal ASC;
                        ");

                        $gt_transaksi_rka = $transaksiKelompok->where('kelompok','!=','Non RKA')->sum('total');
                        $gt_transaksi_non = $transaksiKelompok->where('kelompok','Non RKA')->sum('total');
                    @endphp
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_anggaran_rka,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_transaksi_rka,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if((int)$gt_anggaran_rka==0)
                            0
                        @else
                        {{number_format(((int)$gt_transaksi_rka/(int)$gt_anggaran_rka)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$gt_anggaran_rka-(int)$gt_transaksi_rka),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if((int)$gt_anggaran_rka==0)
                                0
                        @else   
                            {{100-number_format(((int)$gt_transaksi_rka/(int)$gt_anggaran_rka)*100, 2, '.', ',')}}                         
                        @endif
                        %</b>
                    </td>
                </tr>
                <tr style="color:#ff6347;">
                    <td colspan="2" style="font-size:18px;" align="left"><b>TOTAL NON RKA</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_anggaran_non,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format((int)$gt_transaksi_non,0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;" align="center"><b>
                        @if((int)$gt_anggaran_non==0)
                            0
                        @else
                        {{number_format(((int)$gt_transaksi_non/(int)$gt_anggaran_non)*100, 2, '.', ',')}}
                        
                        @endif
                        %</b>
                    </td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>Rp {{number_format(((int)$gt_anggaran_non-(int)$gt_transaksi_non),0,',','.')}}</b></td>
                    <td style="border-top:2pt solid black;border-bottom:3pt solid black;white-space: nowrap;"><b>
                        @if((int)$gt_anggaran_non==0)
                                0
                        @else   
                            {{100-number_format(((int)$gt_transaksi_non/(int)$gt_anggaran_non)*100, 2, '.', ',')}}                         
                        @endif
                        %</b>
                    </td>
                </tr>
                <tr style="color:rgb(255, 172, 9);">
                    <td colspan="2" style="font-size:24px;" align="left"><b>TOTAL KESELURUHAN</b></td>
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
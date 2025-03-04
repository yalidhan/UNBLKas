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

        @media print {
        body {-webkit-print-color-adjust: exact;}
        }
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
    <title>Cetak Lembar Perencanaan Kegiatan</title>
</head>
<body>
    <div class="form-group">
        <div class="tooltip"><button onClick="window.print()"><i class="fa fa-print" style="font-size:34px;"></i></button>
            <span class="tooltiptext">Cetak Laporan</span>
    </div>   
        <h2 align="center" style="line-height: 0.1em;">
        @php 
            if (!empty($departement_id)){
                $departement=DB::select(
                    "SELECT `nama`,'pusat' FROM `departements` WHERE id=$departement_id"
                );
                echo '<b>'.$departement[0]->nama.'</b>';    
            }else{
                echo '<b>Universitas Borneo Lestari</b>';
            }
         @endphp
        
        </h2>
        <h2 align="center" style="line-height: 0.1em;">Perencanaan Kegiatan Anggaran RKA</h2>
        <table style="font-size: 20px;font-weight:bold;border-collapse: collapse;border-spacing:0;" cellspacing="0">
            <tr>
                <td>Untuk Minggu</td>
                <td>:</td>
                <td>{{$year.'-'.$month}}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                @php 
                    if (!empty($departement_id)){
                        $departement=DB::select(
                            "SELECT `nama` FROM `departements` WHERE id=$departement_id"
                        );
                        echo '<td>'.$departement[0]->nama.'</td>';    
                    }else{
                        echo '<td>Seluruh Unit Kerja</td>';
                    }
                @endphp
            </tr>
            <tr>
                <td>Tahun Anggaran</td>
                <td>:</td>
                <td>{{$year}}</td>
            </tr>
        </table>

        <p>Berikut ini kami sampaikan perencanaan kegiatan dan anggaran yang akan dilaksanakan:</p>
        <table class="static" align="center" rules="all">
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;border:3px solid black;background-color:#8f8c8c;">
                    <td>No</td>
                    <td>Jenis</td>
                    <td>Kegiatan</td>
                    <td>Penanggungjawab Kegiatan</td>
                    @php 
                        if(!empty($departement_id)){
                            echo "<td>Jumlah Diajukan</td>";
                        }else{
                            echo "<td>Jumlah Disetujui</td>";
                        }
                    @endphp
                    <td>Satuan Ukur Kinerja Kegiatan</td>
                    <td>Target Kinerja(Target Output)</td>
                    <td>Capaian Kinerja(Realisasi Output)</td>
                    <td>Target Waktu Pelaksanaan</td>
                    <td>Capaian Target Waktu</td>
                </tr>
                <tr align="center" style="background-color:#ccc8c8;">
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td> 
                    <td>10</td>
                </tr>
                </thead>
                @php $sub_total_dp=0;$gt_pusat=0;$total_all=0;@endphp
                @foreach ($pusat as $value)
                    <tr>
                        <td colspan="10" style="background-color:#919491"><b>{{$value->pusat}}</b></td>
                    </tr>
                    @php
                        if ($value->pusat=="Rektorat"){
                            if(!empty($p_id)){
                                $q="AND p.id=$p_id"; 
                            }else{
                                $q="";
                            }
                            $departement=DB::select(
                                "SELECT DISTINCT p.for_bulan,p.departement_id, d.pusat, dp.group_rektorat as nama 
                                FROM plannings p 
                                LEFT JOIN departements d ON p.departement_id = d.id 
                                LEFT JOIN planning_details dp ON p.id = dp.planning_id 
                                WHERE p.for_bulan='$year-$month' AND d.pusat='Rektorat' $q");
                        }else{        
                            if(!empty($p_id)){
                                $q="AND p.id=$p_id"; 
                            }else{
                                $q="";
                            }
                            $departement=DB::select(
                                "SELECT p.id,p.for_bulan,p.departement_id,
                                        d.pusat,nama
                                FROM plannings p
                                LEFT JOIN departements d
                                    ON p.departement_id = d.id
                                WHERE p.for_bulan='$year-$month' AND d.pusat='$value->pusat' $q
                                GROUP BY d.nama");
                        }
                    @endphp
                    @foreach ($departement as $d_value)
                    <tr>
                        <td colspan="10" style="background-color:#ebe1e1"><b>{{$d_value->nama}}</b></td>
                    </tr>
                    @php
                        if ($d_value->pusat=="Rektorat"){
                            if(!empty($p_id)){
                                $per_unit="AND pd.planning_id=$p_id";
                            }elseif(!empty($kode)){
                                $per_unit="AND pd.status='Paid'";
                            }
                            else{
                                $per_unit="AND approved_by_rektor=1";
                            }
                            $detail_perencanaan=DB::select(
                                    "SELECT 
                                        pd.status,pd.planning_id,pd.id,pd.group_rektorat,pd.account_id,sum(pd.nominal) as nominal,
                                        sum(pd.nominal_disetujui) as nominal_disetujui,
                                        pd.pj,pd.judul_file,pd.target_kinerja,pd.capaian_kinerja,
                                        pd.waktu_pelaksanaan,pd.capaian_target_waktu,pd.approved_by_wr2,
                                        a.nama,
                                        p.departement_id,p.for_bulan
                                    FROM planning_details pd
                                    LEFT JOIN accounts a ON pd.account_id = a.id
                                    LEFT JOIN plannings p ON pd.planning_id = p.id
                                    WHERE group_rektorat='$d_value->nama' AND for_bulan='$year-$month' $per_unit
                                    GROUP BY a.nama"
                                    );
                        }else{
                            if(!empty($p_id)){
                                $per_unit="AND pd.planning_id=$p_id";
                            }elseif(!empty($kode)){
                                $per_unit="AND pd.status='Paid'";
                            }
                            else{
                                $per_unit="AND approved_by_rektor=1";
                            }
                            $detail_perencanaan=DB::select(
                                    "SELECT 
                                        pd.status,pd.planning_id,pd.id,pd.account_id,sum(pd.nominal) as nominal,
                                        sum(pd.nominal_disetujui) as nominal_disetujui,
                                        pd.pj,pd.judul_file,pd.target_kinerja,pd.capaian_kinerja,
                                        pd.waktu_pelaksanaan,pd.capaian_target_waktu,pd.approved_by_wr2,
                                        a.nama,
                                        p.departement_id,p.for_bulan
                                    FROM planning_details pd
                                    LEFT JOIN accounts a ON pd.account_id = a.id
                                    LEFT JOIN plannings p ON pd.planning_id = p.id
                                    WHERE departement_id=$d_value->departement_id AND for_bulan='$year-$month' $per_unit 
                                    GROUP BY a.nama"
                                    );
                            }
                        $no=1;
                    @endphp
                        @foreach ($detail_perencanaan as $dp_value)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>RKA</td>
                                <td>{{$dp_value->nama}}</td>
                                <td>{{$dp_value->pj}}</td>
                                @if(!empty($p_id))
                                    <td style="white-space: nowrap;" align="left">Rp {{number_format($dp_value->nominal,0,',','.'),$total_all=$total_all+$dp_value->nominal,$gt_pusat=$gt_pusat+$dp_value->nominal,$sub_total_dp=$sub_total_dp+$dp_value->nominal}}</td>
                                @else
                                    <td style="white-space: nowrap;" align="left">Rp {{number_format($dp_value->nominal_disetujui,0,',','.'),$total_all=$total_all+$dp_value->nominal_disetujui,$gt_pusat=$gt_pusat+$dp_value->nominal_disetujui,$sub_total_dp=$sub_total_dp+$dp_value->nominal_disetujui}}</td>
                                
                                @endif
                                <td>{{$dp_value->judul_file}}</td>
                                <td>{{$dp_value->target_kinerja}}</td>
                                <td>{{$dp_value->capaian_kinerja}}</td>
                                <td>{{$dp_value->waktu_pelaksanaan}}</td> 
                                <td>{{$dp_value->capaian_target_waktu}}</td>
                            </tr>
                        @endforeach
                    <tr style="background-color:#90f589;">
                        <td align="center" colspan="4"><b>Sub Total</b></td>
                        <td style="white-space: nowrap;" align="left"><b>Rp {{number_format($sub_total_dp,0,',','.'),$sub_total_dp=0}}</b></td>
                        <td align="center"colspan="5"></td>
                    </tr>
                    @endforeach
                    <tr style="background-color:#45f76f;">
                        <td align="center" colspan="4"><b>Jumlah {{$value->pusat}}</b></td>
                        <td style="white-space: nowrap;" align="left"><b>Rp {{number_format($gt_pusat,0,',','.'),$gt_pusat=0}}</b></td>
                        <td align="center"colspan="5"></td>
                    </tr>
                        <tr style="background-color:#fae4a7;height:15px;">
                        <td align="center" colspan="10"></td>
                    </tr>
                @endforeach
                <tr style="background-color:#ebe1e1">
                    <td align="center" colspan="4"><b>Grand Total(Seluruh Unit)</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp {{number_format($total_all,0,',','.')}}</b></td>
                    <td align="center"colspan="5"></td>

                <tr style="border-left-style: hidden;">
                    <td style="border-right-style: hidden;" colspan="7"></td>
                    <td style="border-right-style: hidden;" colspan="3"><br>Banjarbaru, {{\Carbon\Carbon::now()->format('d F Y')}}</td>
                </tr>
                <?php 
                    if(!empty($p_id)){
                        $pusat=$departement[0]->pusat;
                        $pejabatQuery=DB::select(
                            "SELECT u.name,u.jabatan,
                                    d.pusat 
                            FROM users u
                            LEFT JOIN departements d ON u.departement_id=d.id
                            where jabatan='Dekan' AND d.pusat='$pusat'"
                        );
                        if($departement_id==6){
                            $pejabatQuery=DB::select(
                                "SELECT u.name,u.jabatan,
                                    d.pusat 
                            FROM users u
                            LEFT JOIN departements d ON u.departement_id=d.id
                            where jabatan='Rektor'"
                            );
                            $pejabat=$pejabatQuery[0]->name;
                            $jabatan=$pejabatQuery[0]->jabatan;
                            $departemen="Universitas Borneo Lestari";
                        }
                        elseif(empty($pejabatQuery)){
                            $pejabat="VACANT";
                            $jabatan="Dekan";
                            $departemen="Fakultas";                           
                        }
                        else{
                            $pejabat=$pejabatQuery[0]->name;
                            $jabatan=$pejabatQuery[0]->jabatan;
                            $departemen=$pejabatQuery[0]->pusat;
                        }
                    }else{
                        $pejabatQuery=DB::select(
                            "SELECT u.name,u.jabatan,
                                d.pusat 
                        FROM users u
                        LEFT JOIN departements d ON u.departement_id=d.id
                        where jabatan='Rektor'"
                        );
                        $pejabat=$pejabatQuery[0]->name;
                        $jabatan=$pejabatQuery[0]->jabatan;
                        $departemen="Universitas Borneo Lestari";
                    }
                ?>
                <tr style="border-style: hidden;">
                    <td colspan="7"></td>
                    <td style="border-style: hidden;" colspan="3">{{$jabatan}} {{$departemen}}<br><br><br><br><br</td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="7"></td>
                    <td style="border-style: hidden;" colspan="3">{{$pejabat}}</td>
                </tr>
        </table>

        <!-- <table class="static" align="center" rules="all">
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;border:3px solid black;background-color:#8f8c8c;">
                    <td>No</td>
                    <td>Jenis</td>
                    <td>Kegiatan</td>
                    <td>Penanggungjawab Kegiatan</td>
                    <td>Jumlah Disetujui</td>
                    <td>Satuan Ukur Kinerja Kegiatan</td>
                    <td>Target Kinerja(Target Output)</td>
                    <td>Capaian Kinerja(Realisasi Output)</td>
                    <td>Target Waktu Pelaksanaan</td>
                    <td>Capaian Target Waktu</td>
                </tr>
                <tr align="center" style="background-color:#ccc8c8;">
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td> 
                    <td>10</td>
                </tr>
                </thead>
                <tr>
                    <td colspan="10" style="background-color:#919491"><b>Rektorat</b></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Wakil Rektor II</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>Honorarium Admin UTS 2023-1</td>
                    <td>Bendahara Panitia</td>
                    <td style="white-space: nowrap;" align="left">Rp 1.800.000</td>
                    <td>Daftar Tenaga Admin UTS</td>
                    <td>Bukti Pembayaran</td>
                    <td>Terwujudnya Peningkatan Mutu Pendidikan Yang Baik</td>
                    <td>Minggu ke 1</td> 
                    <td>1 Hari</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 1.800.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Wakil Rektor III</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>DPM</td>
                    <td>Bidang Kemahasiswaan</td>
                    <td style="white-space: nowrap;" align="left">Rp 500.000</td>
                    <td>Laporan Pelaksanaan</td>
                    <td>Kwitansi dan Dokumen Kegiatan</td>
                    <td>Program Kerja DPM Terlaksana</td>
                    <td>Minggu ke 1</td> 
                    <td>Minggu ke 1</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Laboratorium</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>Nutrisi Gizi Laboran(Susu Bear Brand)</td>
                    <td>KA Unit Laboratorium</td>
                    <td style="white-space: nowrap;" align="left">Rp 1.200.000</td>
                    <td></td>
                    <td>Bukti Pembayaran</td>
                    <td>Terjaganya Kesehatan Laboran</td>
                    <td>Minggu ke 1</td> 
                    <td>Minggu ke 1</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr style="background-color:#45f76f;">
                    <td align="center" colspan="4"><b>Jumlah Rektorat</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 3.500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                    <tr style="background-color:#fae4a7;height:15px;">
                    <td align="center" colspan="10"></td>
                </tr>

                <tr>
                    <td colspan="10" style="background-color:#919491"><b>Fakultas Farmasi</b></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Dekanat Fakultas Farmasi</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>Honorarium Admin UTS 2023-1</td>
                    <td>Bendahara Panitia</td>
                    <td style="white-space: nowrap;" align="left">Rp 1.800.000</td>
                    <td>Daftar Tenaga Admin UTS</td>
                    <td>Bukti Pembayaran</td>
                    <td>Terwujudnya Peningkatan Mutu Pendidikan Yang Baik</td>
                    <td>Minggu ke 1</td> 
                    <td>1 Hari</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 1.800.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Prodi PSPPA</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>DPM</td>
                    <td>Bidang Kemahasiswaan</td>
                    <td style="white-space: nowrap;" align="left">Rp 500.000</td>
                    <td>Laporan Pelaksanaan</td>
                    <td>Kwitansi dan Dokumen Kegiatan</td>
                    <td>Program Kerja DPM Terlaksana</td>
                    <td>Minggu ke 1</td> 
                    <td>Minggu ke 1</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Prodi S1 Farmasi</b></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>RKA</td>
                    <td>Nutrisi Gizi Laboran(Susu Bear Brand)</td>
                    <td>KA Unit Laboratorium</td>
                    <td style="white-space: nowrap;" align="left">Rp 1.200.000</td>
                    <td></td>
                    <td>Bukti Pembayaran</td>
                    <td>Terjaganya Kesehatan Laboran</td>
                    <td>Minggu ke 1</td> 
                    <td>Minggu ke 1</td>
                </tr>
                <tr style="background-color:#90f589;">
                    <td align="center" colspan="4"><b>Sub Total</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr style="background-color:#45f76f;">
                    <td align="center" colspan="4"><b>Jumlah Fakultas Farmasi</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 3.500.000</b></td>
                    <td align="center"colspan="5"></td>
                </tr>
                <tr style="background-color:#ebe1e1">
                    <td align="center" colspan="4"><b>Grand Total(Seluruh Unit)</b></td>
                    <td style="white-space: nowrap;" align="left"><b>Rp 7.000.000</b></td>
                    <td align="center"colspan="5"></td>

                <tr style="border-left-style: hidden;">
                    <td style="border-right-style: hidden;" colspan="7"></td>
                    <td style="border-right-style: hidden;" colspan="3"><br>Banjarbaru, 24 Januari 2024</td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="7"></td>
                    <td style="border-style: hidden;" colspan="3">Rektor Universitas Borneo Lestari<br><br><br><br><br</td>
                </tr>
                <tr style="border-style: hidden;">
                    <td colspan="7"></td>
                    <td style="border-style: hidden;" colspan="3">Dr. Ir. Bambang Joko Priatmadi, M.P</td>
                </tr>
        </table> -->
    </div>
</body>
</html>
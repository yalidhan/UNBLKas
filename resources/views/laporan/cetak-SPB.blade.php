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

        body{
        font-family: "Courier New", "Lucida Console", Consolas, monospace;
        font-size: 12pt;
        line-height: 1.1;

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
        table{
            border-collapse: collapse; 
            -webkit-print-color-adjust: exact; 
        }
        table td{
            padding: 3px;
        }
    </style>
    <title>Cetak SPB Transaksi</title>
</head>
<body>
    <div class="container">
        <center>
        @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
        <h3>SURAT PERINTAH BAYAR(SPB)<br>
            YAYASAN BORNEO LESTARI
        </h3>
        @else
        <h3>SURAT PERINTAH BAYAR(SPB)<br>
            UNIVERSITAS BORNEO LESTARI
        </h3>
        @endif
        

        <table style="border:3px solid;width=100%;border-collapse: collapse;">
            <tr>
                <td width="25%">Anggaran</td>
                <td width="25%">: {{$showTransaction[0]->nama}}</td>
                <td width="15%" style="border-left:3px solid;">No.SPB</td>
                <td width="35%">: {{$showTransaction[0]->no_spb}}</td>
            </tr>
            <tr>
                <td width="25%">Tahun Anggaran</td>
                <td width="25%">: {{\Carbon\Carbon::parse($showTransaction[0]->tanggal)->format('Y')}}</td>
                <td width="15%" style="border-left:3px solid;">Tanggal SPB</td>
                <td width="35%">: {{\Carbon\Carbon::parse($showTransaction[0]->tanggal)->format('d F Y')}}</td>
            </tr>
            <tr>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="15%" style="border-left:3px solid;vertical-align: top;text-align: left;">Keterangan</td>
                <td width="35%">: {{$showTransaction[0]->keterangan}}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:center;border:3px solid">
                    Banjarbaru, {{\Carbon\Carbon::parse($showTransaction[0]->tanggal)->format('d F Y')}} </br> 
                    Dokumen Penagihan Disahkan Oleh,</br>
                    @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
                        Ketua Yayasan</br></br></br></br>
                        Drs. Akhmad Yanie, M.Si., Apt
                    @else
                        Kepala Bidang Keuangan Universitas Borneo Lestari</br></br></br></br>

                        @php
                            $kabidKeu=\App\Models\User::where('jabatan','Kabid Keuangan')->get();
                        @endphp
                    {{$kabidKeu[0]->name}}</br>
                    NIK. {{$kabidKeu[0]->nik}}
                    @endif
                </td>
            </tr>
            <tr style="text-align:left;">
                <td colspan="4">
                    @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
                        Bendahara Yayasan Borneo Lestari diminta membayar uang
                    @else 
                       {{(auth()->user()->jabatan)}} {{auth()->user()->departement->nama}} UNBL diminta membayar uang
                    @endif 
                </td>
            </tr>
            </tr>
            <tr style="text-align:left;">
                <td style="vertical-align: top;text-align: left;">
                    Sebesar
                </td>
                <td colspan="2">
                    :&nbsp;Rp {{number_format($showTransaction[0]->total,0,',','.')}}
                </td>
            </tr>
            <tr style="text-align:left;">
                <td style="vertical-align: top;text-align: left;">
                    Terbilang
                </td>
                <td colspan="3">
                    :&nbsp;<i>{{terbilang($showTransaction[0]->total)}} Rupiah</i>
                </td>
            </tr>    
            <tr style="text-align:left;">
                <td style="vertical-align: top;text-align: left;">
                    Kepada
                </td>
                <td colspan="3">
                    :&nbsp;{{$showTransaction[0]->kepada}} 
                </td>
            </tr>               
            <tr style="text-align:left;">
                <td style="vertical-align: top;text-align: left;">
                    Untuk Pembayaran
                </td>
                <td colspan="3">:
                    </br>
                    @if($showTransaction[0]->no_trf !==null)
                     @php 
                            $no=0;
                            $id=$showTransaction[0]->no_trf;
                            \DB::statement("SET SQL_MODE=''");
                            $transaction=DB::select(
                                    "SELECT t.no_trf,t.id, t.keterangan,d.account_id,d.nominal, a.nama,a.no,a.tipe 
                                    FROM transactions t 
                                    LEFT JOIN transaction_details d 
                                        ON t.id = d.transaction_id 
                                    LEFT JOIN accounts a 
                                    ON d.account_id = a.id 
                                    WHERE t.no_trf='$id'
                                    ORDER BY t.id DESC LIMIT 1"
                                );
                        @endphp
                        @foreach ($transaction as $transactionList)
                        @php
                            $no++
                        @endphp
                        {{$no}}. [{{$transactionList->no}} | {{$transactionList->tipe}}] {{$transactionList->nama}} (Rp {{number_format($transactionList->nominal,0,',','.')}})</br>
                        @endforeach                   
                    @else
                        @php 
                            $no=0;
                            $id=$showTransaction[0]->id;
                            \DB::statement("SET SQL_MODE=''");
                            $transaction=DB::select(
                                    "SELECT t.id, t.keterangan,d.account_id,d.nominal, a.nama,a.no,a.tipe 
                                    FROM transactions t 
                                    LEFT JOIN transaction_details d 
                                        ON t.id = d.transaction_id 
                                    LEFT JOIN accounts a 
                                    ON d.account_id = a.id 
                                    WHERE t.id=$id"
                                );
                        @endphp
                        @foreach ($transaction as $transactionList)
                        @php
                            $no++
                        @endphp
                        {{$no}}. [{{$transactionList->no}} | {{$transactionList->tipe}}] {{$transactionList->nama}} (Rp {{number_format($transactionList->nominal,0,',','.')}})</br>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:center;"></br> 
                    @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
                        Banjarbaru, {{\Carbon\Carbon::parse($showTransaction[0]->tanggal)->format('d F Y')}} </br>
                        Kepala Bagian Keuangan dan Pajak Yayasan</br></br></br></br>
                        Rinto Widyanto, S.Ak., M.M</br>
                        NIK. 010313053
                    {{--@else 
                        Wakil Rektor II</br></br></br></br>
                        Azmi Yunarti, S.Pi, M.Pd</br>
                        NIK. 010408001 --}}
                    @endif 
                </td>
            </tr>     
        </table>  
    </table> 
        <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 45%; text-align: center;border-left: 3px solid;">
            @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
            STATUS PEMBAYARAN<br>
            Telah Dibayar Oleh<br>
            Bendahara Yayasan</br></br></br></br>
            Nafila, M.Si</br>
            @else 
            STATUS PEMBAYARAN<br>
            Telah Dibayar dan Dibukukan<br>
            Bendahara</br></br></br></br>
            {{auth()->user()->name}}</br>
            NIK. {{$showTransaction[0]->nik}}
            @endif 
            
            </td>
            <td style="width: 10%; text-align: center;">
            STATUS PENERIMAAN<br>
            Telah Diterima Oleh<br><br><br><br>
            ..................
            </td>
            <td style="width: 45%; text-align: center;border-right: 3px solid;">
            @if (in_array($showTransaction[0]->departement_id,[1,19,20,21]))
            STATUS AKUNTANSI<br>
            Telah Dibukukan Oleh<br>
            Bagian Akuntansi</br>(Kas Keluar)<br><br><br><br>
            Nurulita Rahmadayanti, S.Ak</br>
            NIK. 
            @else 
            STATUS AKUNTANSI<br>
            Diinput Sistem Akuntansi<br>
            Yayasan<br><br><br><br>
            .....................</br>
            NIK. ......... 
            &nbsp;
            @endif 
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:left;border:3px solid">
                CATATAN PERPAJAKAN : </br>
                <p>{{$showTransaction[0]->ctt_pajak}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:left;border:3px solid">
                CATATAN PERBENDAHARAAN : </br>
                <p>{{$showTransaction[0]->ctt_bendahara}}</p>
            </td>
        </tr>     
        
        </center>
    </div>
</body>
</html>
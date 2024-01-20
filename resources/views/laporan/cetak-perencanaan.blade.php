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
        <h2 align="center" style="line-height: 0.1em;"><b>Universitas Borneo Lestari</b></h2>
        <h2 align="center" style="line-height: 0.1em;">Perencanaan Kegiatan Anggaran RKA</h2>
        <h3 align="left" style="line-height: 0.1em;">Bulan</h3>
        <h3 align="left" style="line-height: 0.1em;">Unit Kerja</h3>
        <h3 align="left" style="line-height: 0.1em;">Tahun Anggaran</h3>
        <p>Berikut ini kami sampaikan perencanaan kegiatan dan anggaran yang akan dilaksanakan:</p>
        <table class="static" align="center" rules="all">
            <thead>
                <tr align="center" style="font-size:large;font-weight:bold;border:3px solid black;background-color:#8f8c8c;">
                    <td>No</td>
                    <td>Jenis</td>
                    <td>Kegiatan</td>
                    <td>Penanggungjawab Kegiatan</td>
                    <td>Jumlah Anggaran(input)</td>
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
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Rektorat</b></td>
                </tr>
                <tr>
                    <td colspan="10" style="background-color:#ebe1e1"><b>Wakil Rektor II</b></td>
                </tr>
                <tr>
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
        </table>
    </div>
</body>
</html>
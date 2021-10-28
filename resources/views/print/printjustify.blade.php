<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }
        p {
            margin: 0 0 0 10px;
        }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td colspan="2">
                <b>RASUMI MEDIPHARMA SDN. BHD.</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                FARMASI VETERAN <br>
                Hospital Angkatan Tentera Tuanku Mizan <br>
                No. 3 Jalan 4/27A, Seksyen 2, Wangsa Maju 53000 Kuala Lumpur
            </td>
        </tr>
        <tr>
            <td style="width: 70%;">Tel: 03-4131 3214</td>
            <td><b>SST Reg. No.:</b></td>
        </tr>
    </table>

    <hr>

    <table style="width: 100%;">
        <tr>
            <td>
                Jabatan Hal Ehwal Veteran ATM 
                <br> Bahagian Perubatan & Tuntutan
                <br> 301 Medan Tuanku, Jalan Tuanku Abdul Rahman Peti Surat 13191 
                <br> 50802 Kuala Lumpur
            </td>
        </tr>
    </table>
    
    <table style="width: 100%;">
        <tr>
            <td colspan="4">Tuan,</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td colspan="4"><b><u>PER : NOTA JUSTIFIKASI DARI PEGAWAI FARMASI</u></b></td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 150px;">Nama Pesara/Pesakit</td>
            <td>: <u>{{ strtoupper($order->full_name) }}</u></td>
            <td style="width: 150px;">No. KP</td>
            <td>: <u>{{ $order->identification }}</u></td>
        </tr>
        <tr>
            <td>No. Tentera</td>
            <td>: <u>{{ strtoupper($order->army_pension) }}</u></td>
            <td>No. Dispen</td>
            <td>: <u>{{ $order->do_number }}</u></td>
        </tr>
        <tr>
            <td>Tarikh Dispen</td>
            @php
                $date = date_create($order->dispense_date);
                $date = date_format($date, 'd/m/Y');
            @endphp
            <td>: <u>{{ $date }}</u></td>
            <td>No. PS</td>
            <td>: <u>{{ strtoupper($order->rx_number) }}</u></td>
        </tr>
    </table>

    <table style="width: 100%; margin: 5px 0; border-collapse: collapse;" border="1">
        <thead>
            <tr>
                <th style="width: 40px;">BIL</th>
                <th>PERKARA YANG PERLU JUSTIFIKASI</th>
                <th style="width: 100px;">CATATAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>
                    <p>
                        Penukaran item dan / atau dos dibuat oleh Farmasi Pesakit Luar HATTM (FPL).
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">1.1</td>
                <td>
                    <p>
                        Lestric / Lovastatin _____mg ditukar kepada Zocor / Simvastatin _____mg pada (Tarikh) __________.
                        <br> Sebab penukaran: Tiada Bekalan Stok.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">1.2</td>
                <td>
                    <p>
                        Caduet (Amlodipine _____mg + Atorvastatin _____mg) ditukar kepada Amlodipine / Norvasc _____mg + 
                        Atorvastatin / Lipitor _____mg pada (Tarikh) __________. 
                        <br> Sebab Penukaran: Tiada Bekalan Stok.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">1.3</td>
                <td>
                    <p>
                        Exforge (Amlodipine _____mg + Valsartan _____mg) ditukar kepada Amlodipine / Norvasc _____mg + 
                        Valsartan / Diovan _____mg pada (Tarikh) __________. 
                        <br> Sebab penukaran: Tiada Bekalan Stok.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>
                    <p>
                        Kuantiti yang dibekalkan adalah cukup untuk jangka masa antara tarikh 'next due' 
                        tempo pesakit (__________) dan tarikh luput preskripsi (__________) yang dikira 
                        sebagai	_____ hari / minggu / bulan.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>
                    <p>
                        Kuantiti yang dibekalkan adalah cukup untuk jangka masa antara tarikh 'next due' tempo 
                        pesakit (__________) dan tarikh temujanji klinik seterusnya (__________) yang dikira sebagai 
                        _____ hari / minggu / bulan. Sila rujuk salinan Kad Temujanji .
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>
                    <p>
                        Bekalan lebih daripada tiga bulan diberikan kerana pesakit akan melakukan perjalanan 
                        ke luar negara. Sila rujuk salinan tiket penerbangan pergi dan balik yang disertakan.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>
                    <p>
                        Kuantiti item yang dibekalkan adalah lebih daripada tempoh preskripsi untuk mengikuti 
                        jalur penuh 'strip' tablet.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>
                    <p>
                        Tarikh bercetak pada Nota Keterangan dan / atau Pesanan Penghantaran 'DO' telah dipinda 
                        secara manual bersesuaian dengan tarikh pengedaran yang sesuai disebabkan oleh kes sebelumnya 
                        yang bertindih / membekalkan item ubatan yang sama.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>
                    <p>
                        Jenama yang berbeza dibekalkan bukannya jenama yang ditetapkan (____________________) 
                        disebabkan stok tidak tersedia ada pada waktu mendispens.
                    </p>
                    <p>
                        Penjelasan yang sesuai mengenai perbezaan antara produk ini dan arahan dos yang diperlukan 
                        untuk produk yang dibekalkan telah diberikan kepada pesakit.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>
                    <p>
                        Senarai yang ditetapkan bagi item A, A * dan /atau A/KK :
                        <br> telah dimulakan oleh pakar klinikal.
                    </p>
                    <p>
                        Disertakan salinan preskripsi terdahulu pesakit yang telah disahkan oleh pakar 
                        (nombor preskripsi:	_______________)
                    </p>
                    <p>
                        Preskripsi yang dikeluarkan dalam transaksi terdahulu dengan nombor DN/DO no. _______________
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td>
                    <p style="margin-bottom: 40px;">Lain-lain perkara:</p>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td>
                <p>Terima Kasih</p>
                <p>Pegawai Farmasi</p>
                <p>Cop & Tandatangan</p>
            </td>
        </tr>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table, td {
          border-collapse: collapse;
          width: 100%;
        }

        .customHeader{
            text-align: center;
        }
    </style>
    <title>Nota Pembayaran {{ $idDetTransaksi }}</title>
</head>
<body>
    <h3 class="customHeader">{{ $title1 }}</h3>
    <h3 class="customHeader">{{ $title2 }}</h3>
    <hr> </hr>
    <h4>Nota Transaksi Sewa Mobil</h4>

    <table style="border: 1px solid;">
        <table style="border: 1px solid;">
            <tr>
                <td colspan="4" class="customHeader"><b>Atma Rental</b></td>
            </tr>
            <tr>
                <td>{{ $idTransaksi }}</td>
                <td></td>
                <td></td>
                <td>{{ $tglTransaksi }}</td>
            </tr>
        </table>
        <table style="border: 1px solid;">
            <tr>
                <td>Cust</td>
                <td>{{ $namaPelanggan }}</td>
                <td>PRO:</td>
                <td>{{ $kodePromo }}</td>
            </tr>
            <tr>
                <td>CS</td>
                <td colspan="3">{{ $namaPegawai }}</td>
            </tr>
            <tr>
                <td>DRV</td>
                <td colspan="3">{{ $namaDriver }}</td>
            </tr>
        </table>
        <table style="border: 1px solid;"><tr><td colspan="4" style="height: 25px"></td></tr></table>
        <table style="border: 1px solid;">
            <tr>
                <td colspan="4" class="customHeader"><b>Nota Transaksi</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ $idDetTransaksi }}</td>
            </tr>
            <tr>
                <td>Tgl Mulai</td>
                <td>{{ $tglMulai }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Tgl Selesai</td>
                <td>{{ $tglAkhir }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Tgl Pengembalian</td>
                <td>{{ $tglKembali }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Item</b></td>
                <td><b>Satuan</b></td>
                <td><b>Durasi</b></td>
                <td><b>Sub Total</b></td>
            </tr>
            <tr>
                <td>{{ $namaMobil }}</td>
                <td>{{ $sewaMobil }}</td>
                <td>{{ $totalHariSewa }} hari</td>
                <td>{{ $subTotalMobil }}</td>
            </tr>
            <tr>
                <td>
                    <?php
                        if (!is_null($namaDriver)) {
                            echo 'Driver ' . $namaDriver;
                        }
                        else {
                            echo '';
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (!is_null($namaDriver)) {
                            echo $sewaDriver;
                        }
                        else {
                            echo '';
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (!is_null($namaDriver)) {
                            echo $totalHariSewa . 'hari';
                        }
                        else {
                            echo '';
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (!is_null($namaDriver)) {
                            echo $subTotalDriver;
                        }
                        else {
                            echo '';
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $subTotalMobilDriver }}</td>
            </tr>
        </table>
        <table style="border: 1px solid;"><tr><td colspan="4" style="height: 25px"></td></tr></table>
        <table style="border: 1px solid;">
            <tr>
                <td>Cust</td>
                <td>CS</td>
                <td>Disc</td>
                <td>{{ $diskon }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Denda</td>
                <td>{{ $denda }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td><b>{{ $total }}</b></td>
            </tr>
            <tr>
                <td>{{ $namaPelanggan }}</td>
                <td>{{ $namaPegawai }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </table>
</body>
</html>
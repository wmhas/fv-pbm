<style>
    .page-break {
        page-break-after: always;
    }
    td {
        vertical-align: top;
        padding: 0;
        margin: 0;
    }
</style>

<div style="padding: 10px 40px;">
    <div style="text-align: right; font-size: 13px; font-family: roboto, sans-serif;">
        BQ-BP-14 Borang Perubatan JHEV 1/09 (T)
    </div>

    <div style="margin-top: 40px;">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: center">
                    <img src="{{public_path('Jata.png')}}" style="width:90px" />
                </td>
                <td align="center" style="font-size: 15px;">
                    <strong style="white-space: nowrap;">JABATAN HAL EHWAL VETERAN ATM</strong> <br/><br/>
                    <strong style="white-space: nowrap;">MAKLUMAT TAMBAHAN PEMOHON BAGI PERMOHONAN</strong> <br/>
                    <strong style="white-space: nowrap;">UBAT / ALAT / PERKHIDMATAN PERUBATAN / RAWATAN</strong> <br/>
                </td>
                <td align="right" style="vertical-align: center">
                    <img src="{{public_path('FVPicture.png')}}" style="width: 75px" />
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px">
        <strong>Maklumat Veteran ATM</strong>
    </div>

    <div style="margin-top: 30px">
        <table style="width: 100%;">
            <tr>
                <td width="120">No. Kad Pengenalan</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height: 25px;">
                        {{ $order->patient->card->ic_no }}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="120">Alamat</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; min-height:  90px;">
                        @if (!empty($order->patient->card->patient->address_1 )) {{ strtoupper($order->patient->card->patient->address_1) }} @endif
                        @if (!empty($order->patient->card->patient->address_2 )) <br> {{ strtoupper($order->patient->card->patient->address_2) }} @endif
                        @if (!empty($order->patient->card->patient->address_3 )) <br> {{ strtoupper($order->patient->card->patient->address_3) }} @endif
                        @if (!empty($order->patient->card->patient->postcode )) <br> {{ strtoupper($order->patient->card->patient->postcode) }} @endif
                        @if (!empty($order->patient->card->patient->city )) {{ strtoupper($order->patient->card->patient->city) }} @endif
                        @if (!empty($order->patient->card->patient->state->name )) <br> {{ strtoupper($order->patient->card->patient->state->name) }} @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td width="120">No. Telefon</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height: 25px;">
                        @if (!empty($order->patient->card->patient->phone )) {{ $order->patient->card->patient->phone }} @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td width="120">Alamat e-mel (Jika ada)</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height: 25px;">
                        @if (!empty($order->patient->card->patient->email )) {{ $order->patient->card->patient->email }} @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 60px">
        <strong>Maklumat Pembekal / Panel Haemodialisis</strong> <br/>
        <strong>(Sekiranya Bayaran Secara Terus Kepada Pembekal / Panel)</strong>
    </div>

    <div style="margin-top: 30px">
        <table style="width: 100%">
            <tr>
                <td width="120">
                    Nama Pembekal / <br/>
                    Panel Haemodialisis
                </td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height: 45px;"></div>
                </td>
            </tr>
            <tr>
                <td width="120">Alamat</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height:  90px;"></div>
                </td>
            </tr>
            <tr>
                <td width="120">No. Telefon</td>
                <td width="10">:</td>
                <td>
                    <div style="border: 1px solid black; height: 25px;"></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    {{--Page 2--}}
    <div style="text-align: right; font-size: 13px; font-family: roboto, sans-serif;">
        BQ-BP-14 Borang Perubatan JHEV 1/09 (T) <br/>
        Pindaan : 0
    </div>
    <div style="border: 1px solid black; padding: 10px 20px; font-size: 11px; margin-top: 20px;">
        <div style="text-align: center;">
            <strong>PERMOHONAN PERBELANJAAN KEMUDAHAN PERUBATAN</strong> <br/>
            <strong>DI BAWAH PEKELILING PERKHIDMATAN BILANGAN 21 TAHUN 2009</strong> <br/> <br/>
            <strong>UBAT/ALAT/PERKHIDMATAN PERUBATAN /RAWATAN</strong>
        </div>
        <div style="margin-top: 10px">
            <table style="width: 100%;">
                <tr>
                    <td width="40px">Arahan :</td>
                    <td>
                        <ol style="margin: 0; list-style-type: lower-roman">
                            <li>
                                Maklumat hendaklah dilengkapkan dengan <strong>jelas</strong> dengan menggunakan <strong>huruf besar</strong>.
                            </li>
                            <li>Sila rujuk panduan yang disediakan bagi butiran yang berkaitan</li>
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN I</strong>
        </div>
        <div>
            <strong>Butir Diri Veteran ATM</strong>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">1.</td>
                    <td colspan="3">
                        Nama Penuh <i>(seperti dalam kad pengenalan/pasport)</i>
                        <div style="border: 1px solid black; height: 20px; margin-top: 5px;">
                            {{$order->patient->card->name}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td colspan="3">
                        No. Kad Pengenalan /Pasport
                        <div style="border: 1px solid black; height: 16px; margin-top: 5px;">
                            {{$order->patient->card->ic_no}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td style="width: 200px">
                        No. Tentera
                        <div style="border: 1px solid black; height: 16px; margin-top: 5px;">
                            {{$order->patient->card->army_pension}}
                        </div>
                    </td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>4.</td>
                                <td colspan="2" style="width: 60px;">
                                    Status:
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Berpencen</td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 18px; text-align: center">
                                        {{ $order->patient->card->type === 'Veteran Berpencen' || $order->patient->card->type === 'JPA Berpencen' ? '/' : '' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    Tidak <br/>
                                    Berpencen
                                </td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 18px; text-align: center">
                                        {{ $order->patient->card->type === 'Veteran Berpencen' || $order->patient->card->type === 'JPA Berpencen'? '' : '/' }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%;" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width: 20px;">5.</td>
                                <td colspan="2">
                                    Kategori:
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center; width: 25px; padding-top: 5px;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'ATM' ? '/' : '' }}
                                    </div>
                                </td>
                                <td style="padding-top: 5px;">ATM</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'Kerahan Sepenuh Masa' ? '/' : '' }}
                                    </div>
                                </td>
                                <td>Kerahan Sepenuh Masa</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'Force 136' ? '/' : '' }}
                                    </div>
                                </td>
                                <td>Force 136</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'Tentera British' ? '/' : '' }}
                                    </div>
                                </td>
                                <td>Tentera British</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'Sarawak Rangers' ? '/' : '' }}
                                    </div>
                                </td>
                                <td>Sarawak Rangers</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="vertical-align: center;">
                                    <div style="border: 1px solid black; height: 16px; width: 20px; text-align: center">
                                        {{ $order->patient->card->army_type === 'JPA' ? '/' : '' }}
                                    </div>
                                </td>
                                <td>JPA</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <strong>Butir Diri Pesakit</strong>
            <i>(sekiranya pesakit <strong>bukan Veteran ATM</strong>)</i>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">6.</td>
                    <td colspan="3">
                        Nama Penuh <i>(seperti dalam kad pengenalan/passport/sijil kelahiran)</i>
                        <div style="border: 1px solid black; height: 20px; margin-top: 5px;">
                            @if ($order->patient->relation != 'CardOwner')
                                @if (!empty($order->patient->full_name)) {{ $order->patient->full_name }} @endif
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td colspan="3">
                        No. Kad Pengenalan/Pasport/Sijil Kelahiran
                        <div style="border: 1px solid black; height: 16px; margin-top: 5px;">
                            @if ($order->patient->relation != 'CardOwner')
                                @if (!empty($order->patient->identification)) {{ $order->patient->identification }} @endif
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td colspan="2">
                        Hubungan Pesakit Dengan Veteran ATM
                    </td>
                    <td style="width: 25%"><div style="border: 1px solid black; height: 16px; width: 40px; display: inline-block;"></div></td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td colspan="3">
                        Maklumat Tambahan Bagi <strong>Anak</strong>
                        <div>
                            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>i.</td>
                                    <td>Umur</td>
                                    <td>
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">tahun</div>
                                    </td>
                                    <td>
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">bulan</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ii.</td>
                                    <td>Daif</td>
                                    <td style="vertical-align: center">
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">Ya</div>
                                    </td>
                                    <td style="vertical-align: center">
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">Tidak</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>iii.</td>
                                    <td>Masih Bersekolah</td>
                                    <td style="vertical-align: center">
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">Ya</div>
                                    </td>
                                    <td style="vertical-align: center">
                                        <div style="border: 1px solid black; height: 16px; width: 18px; display: inline-block;"></div>
                                        <div style="display: inline-block; padding-bottom: 5px;">Tidak</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN II</strong>
        </div>
        <div>
            <strong>Butiran Rawatan Dan Tuntutan Perbelanjaan</strong>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">10.</td>
                    <td>Rawatan Di Hospital/Klinik Kerajaan</td>
                    <td>Pembekal Kemudahan Perubatan</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>i.</td>
                                <td>Nama & Alamat Hospital/Klinik Kerajaan</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>ii.</td>
                                <td>
                                    Tarikh Rawatan
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>i.</td>
                                <td>Nama & Alamat Hospital/Agensi Swasta</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                    <div style="border-bottom: 1px solid black; height: 16px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>ii.</td>
                                <td>
                                    Tarikh Kemudahan Perubatan Diperolehi
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">11.</td>
                    <td>
                        <div style="border: 1px solid black; height: 18px; margin-top: 5px;"></div>
                        <div style="text-align: center">
                            <i>(hari)</i> &nbsp; &nbsp;
                            <i>(bulan)</i> &nbsp; &nbsp;
                            <i>(tahun)</i> &nbsp; &nbsp;
                        </div>
                    </td>
                    <td>
                        <div style="border: 1px solid black; height: 18px; margin-top: 5px;"></div>
                        <div style="text-align: center">
                            <i>(hari)</i> &nbsp; &nbsp;
                            <i>(bulan)</i> &nbsp; &nbsp;
                            <i>(tahun)</i> &nbsp; &nbsp;
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">12.</td>
                    <td>
                        Kategori Tuntutan
                    </td>
                    <td align="center">i.</td>
                    <td>
                        <div style="border: 1px solid black; height: 14px; margin-top: 5px;"></div>
                    </td>
                    <td align="center">ii.</td>
                    <td>
                        <div style="border: 1px solid black; height: 14px; margin-top: 5px;"></div>
                    </td>
                    <td align="center">ii.</td>
                    <td>
                        <div style="border: 1px solid black; height: 14px; margin-top: 5px;"></div>
                    </td>
                    <td align="center">iv.</td>
                    <td>
                        <div style="border: 1px solid black; height: 14px; margin-top: 5px;"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 40px;">13.</td>
                    <td>
                        Senarai Tuntutan <i>(sila gunakan lampiran sekiranya perlu)</i>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table style="width: 100%;" border="1" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center">Bill</td>
                                <td align="center">Nama Ubat/Alat/Perkhidmatan Perubatan/Rawatan</td>
                                <td align="center">
                                    No. Rujukan <br/>
                                    Dokumen Kewangan
                                </td>
                                <td align="center">
                                    Harga <br/>
                                    (RM)
                                </td>
                            </tr>
                            @for($i = 0; $i < 4; $i++)
                            <tr>
                                <td><br/><br/></td>
                                <td><br/><br/></td>
                                <td><br/><br/></td>
                                <td><br/><br/></td>
                            </tr>
                            @endfor
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    {{--Page 3--}}
    <div style="text-align: right; font-size: 13px; font-family: roboto, sans-serif;">
        BQ-BP-14 Borang Perubatan JHEV 1/09 (T) <br/>
        Pindaan : 0
    </div>
    <div style="font-size: 11px; border: 1px solid black; margin-top: 20px; padding: 20px;">
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">14.</td>
                <td>
                    Dokumen Sokongan yang Disertakan
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table style="width: 100%;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Surat Pengesahan Pegawai
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Surat Pengesahan Kementerian Kesihatan Malaysia
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Surat Ketua Pengarah Kesihatan Malaysia
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Surat Pengesahan Institut Pendidikan / Pengajian Tinggi
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Dokumen Kewangan
                                <i>
                                    (contoh: resit, invois, sebut harga atau dokumen kewangan lain yang berkaitan) <br/>
                                    (Resit Rasmi Tuntutan Hanya Sah Diperakukan Dalam Tempoh 1 Tahun (12 Bulan) Dari Tarikh Resit Rasmi
                                    dikeluarkan)
                                </i>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30px;">
                                <div style="border: 1px solid black; height: 16px; width: 20px;"></div>
                            </td>
                            <td>
                                Resit Asal Yang Hilang Perlu Mendapat Salinan Pendua Yang Diperakukan Dengan
                                <strong>“Certified True Copy”</strong>
                                Oleh Farmasi Yang Mengeluarkan Bagi Tujuan Bayaran Balik
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN III</strong>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">15.</td>
                <td>
                    Pengesahan Veteran ATM
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    “Saya dengan ini mengesahkan bahawa maklumat sebagaimana yang dinyatakan di <strong>Bahagian I</strong> dan <strong>Bahagian II</strong> di atas
                    adalah <strong>benar</strong> belaka. Berkaitan itu, saya memohon supaya perbelanjaan bagi maksud kemudahan perubatan yang
                    diperolehi sebanyak <strong>RM</strong> ________________ adalah tanggungan oleh Kerajaan.”
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td>Tandatangan</td>
                            <td style="text-align: center">__________________________</td>
                            <td>Tarikh</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">(
                                @for($i = 0; $i < 25; $i++)
                                    &nbsp;
                                @endfor
                            )</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">
                                <i>(nama penuh veteran ATM)</i>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN IV</strong>
        </div>
        <div>
            <strong>Perakuan dan Pengesahan Oleh Pegawai / Pakar Perubatan Kerajaan</strong> <i>(Sila guna lampiran sekiranya perlu)</i>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">16.</td>
                <td>
                    Nama / Jenis Penyakit Yang Dihidapi oleh Pesakit
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                </td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">17.</td>
                <td>
                    Nama atau Jenis Ubat / Alat / Perkhidmatan Perubatan / Rawatan yang Diperakukan kepada Pesakit
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                </td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">18.</td>
                <td>
                    Sebab-sebab Ubat / Alat / Perkhidmatan Perubatan / Rawatan yang Diperlukan oleh Pesakit Tidak Dapat Dibekal /
                    Disediakan oleh Hospital / Klinik Kerajaan
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                    <div style="border-bottom: 1px solid black; height: 16px"></div>
                </td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">19.</td>
                <td>
                    Perakuan dan Pengesahan Pegawai / Pakar Perubatan Kerajaan
                    <div style="margin-left: 20px;">
                        “Saya dengan ini memperakukan bahawa kemudahan perubatan seperti di <strong>Butiran 16</strong> di atas diperlukan oleh pesakit
                        berdasarkan penyakit yang dihadapinya. Saya juga mengesahkan bahawa kemudahan perubatan berkenaan tidak
                        dapat dibekal / disediakan oleh pihak hospital / klinik atas sebab-sebab seperti yang dinyatakan dalam <strong>Butiran 17</strong> di
                        atas.”
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 80px;">Tandatangan</td>
                            <td style="text-align: center; width: 240px;">__________________________</td>
                            <td colspan="2" rowspan="5">
                                <span style="white-space: nowrap">
                                    Nama & Cop Rasmi Pegawai/Pakar Perubatan
                                </span>
                                <div style="border: 1px solid black; height: 70px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">(
                                @for($i = 0; $i < 25; $i++)
                                    &nbsp;
                                @endfor
                            )</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">
                                <i>(nama penuh)</i>
                            </td>
                        </tr>
                        <tr>
                            <td>Jawatan</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                        <tr>
                            <td>Tarikh</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN V</strong>
        </div>
        <div>
            Kelulusan Penggunaan Ubat <i>(ubat yang tidak disenaraikan dalam senarai ubat-ubatan KKM/hospital Universiti sahaja)</i>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">20.</td>
                <td>
                    Kelulusan Penggunaan ubat oleh Kementerian Kesihatan Malaysia/Pengarah Hospital Universiti
                    “Penggunaan ubat yang <strong>tidak disenaraikan</strong> dalam senarai ubat-ubatan Kementerian Kesihatan Malaysia/Hospital
                    Universiti seperti di <strong>Butiran 16</strong> di atas adalah <strong>*DILULUSKAN / TIDAK DILULUSKAN.</strong>”
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 80px;">Tandatangan</td>
                            <td style="text-align: center; width: 240px;">__________________________</td>
                            <td colspan="2" rowspan="5">
                                <span style="white-space: nowrap">
                                    Cop Rasmi KKM/Pengarah Hospital Universiti
                                </span>
                                <div style="border: 1px solid black; height: 70px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">(
                                @for($i = 0; $i < 25; $i++)
                                    &nbsp;
                                @endfor
                            )</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">
                                <i>(nama penuh)</i>
                            </td>
                        </tr>
                        <tr>
                            <td>Jawatan</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                        <tr>
                            <td>Tarikh</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    * <i>Potong mana yang tidak berkenaan</i>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    {{--Page 4--}}
    <div style="text-align: right; font-size: 13px; font-family: roboto, sans-serif;">
        BQ-BP-14 Borang Perubatan JHEV 1/09 (T) <br/>
        Pindaan : 0
    </div>
    <div style="font-size: 11px; border: 1px solid black; margin-top: 20px; padding: 20px;">
        <div style="background-color: darkgray; padding: 5px 10px; margin-top: 5px">
            <strong>BAHAGIAN V</strong>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="width: 40px;">21.</td>
                <td>
                    Pengesahan Dan Keputusan Ketua Jabatan <br>
                    Saya dengan ini mengesahkan bahawa permohonan veteran ATM mematuhi syarat-syarat dan 
                    peraturan-peraturan sebagaimana yang ditetapkan dalam Perintah Am Bab F Tahun 1974 dan 
                    Pekeliling Perkhidmatan Bilangan 21 Tahun 2009. Berkaitan itu, permohonan perbelanjaan 
                    bagi maksud kemudahan perubatan yang diperolehi sebanyak <b>RM</b> ________________ adalah 
                    <b>*DILULUSKAN / TIDAK DILULUSKAN</b>.
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 80px;">Tandatangan</td>
                            <td style="text-align: center; width: 240px;">__________________________</td>
                            <td colspan="2" rowspan="5">
                                <span style="white-space: nowrap">
                                    Nama & Cop Rasmi
                                </span>
                                <div style="border: 1px solid black; height: 70px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">(
                                @for($i = 0; $i < 25; $i++)
                                    &nbsp;
                                @endfor
                            )</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">
                                <i>(nama pegawai)</i>
                            </td>
                        </tr>
                        <tr>
                            <td>Jawatan</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                        <tr>
                            <td>Tarikh</td>
                            <td style="text-align: center">__________________________</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    * <i>Potong mana yang tidak berkenaan</i>
                </td>
            </tr>
        </table>
    </div>
</div>
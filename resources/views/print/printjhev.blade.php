<!DOCTYPE html>
<html>

<style>
    .borang {
        font-family: Arial, sans-serif;
        font-size: 10px;
        padding-left: 470px;
        margin-top: 50px;
    }

    .text-center {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        font-size: 20px;
    }

    .center {
        padding-left: 10px;
        width: 100%;
    }

    .ridge {
        border-style: ridge;
    }

    .centertop {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .jata {
        text-align: center;
        margin-right: 450px;
    }

    .bottom {
        border: 1px solid black;
        border-collapse: collapse;
        margin-left: auto;
        margin-right: auto;
    }

    .bottom th {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 10px;
        padding: 10px;


    }

    .bottom td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }

    hr.solid {
        border-top: 1px solid #bbb;
    }

    .borang {
        font-family: Arial, sans-serif;
        font-size: 10px;
        padding-left: 470px;
        margin-top: 0px;
    }

    .jabatan {
        margin-right: 450px;
        text-align: center;
        font-size: 20px;
    }

    .div1 {
        width: 250px;
        height: 70px;
        border: 1px solid black;
    }

    .div2 {
        width: 10px;
        height: 10px;
        border: 1px solid black;
    }

    .firstpage {
        font-size: 95%;
    }

    .other {
        border: 1px solid black;
        padding: 10px;
        font-size: 70%;
        line-height: 1em;
    }

</style>

<body>
    <div class="firstpage">
        <div class="borang">
            <p>BQ-BP-14 Borang Perubatan JHEV 1/09 (T)</p>
        </div>
        <table class="text-center">
            <tr>
                <td><img src="Jata.png" alt="Jata Kerajaan" width="80" height="70"
                        style="padding-right:95px;padding-bottom:5px;"></td>
                <td>
                    <h6 style="padding-left:15px;">JABATAN HAL EHWAL VETERAN ATM</h6>
                    <h6>MAKLUMAT TAMBAHAN PEMOHON BAGI PERMOHONAN<br>
                        UBAT/ALATAN/PERKHIDMATAN PERUBATAN/RAWATAN</h6>
                </td>
                <td><img src="FVPicture.png" alt="FV Logo" width="80" height="70"
                        style="padding-left:95px;padding-bottom:5px;"></td>
            </tr>
        </table>
        <div class="row" style="padding-left:50px;">
            <div class="col-12">
                <h5 style="font-size:15px;margin-top:-5px;">Maklumat Veteran ATM</h5>
            </div>
        </div>
        <table class="center">
            <tr>
                <td style="font-size:13px;line-height:3px;"><label>No. Kad Pengenalan &nbsp;&nbsp;&nbsp; :</label></td>
                <td style="padding-bottom:7px;"><input type="text" size="26" name="identification" class="form-control"
                        value="{{ $patient->identification }}"></td>
            </tr>
            <tr>
                <td style="font-size:13px;line-height:5px;"></label>Alamat
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    :</label><br></td>
                <td class="alamat"><input type="text" size="26" name="address_1" class="form-control"
                        value="{{ $patient->address_1 }}"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" size="26" name="address_2" class="form-control"
                        value="{{ $patient->address_2 }}">
                </td><br>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" size="26" name="postcode" class="form-control"
                        value="{{ $patient->postcode }}">
                </td><br></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" size="26" name="city" class="form-control" value="{{ $patient->city }}"></td>
            </tr>
            <br>
            <tr>
                <td style="font-size:13px;"></label>No. Telefon
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</u></label>
                </td>
                <td style="padding-bottom:8px;"><input type="text" size="26" name="phone" class="form-control"
                        value="{{ $patient->phone }}"></td>
            </tr>
            <br>
            <tr>
                <td style="font-size:13px;"><label>Alamat e-mail
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<br>(Jika ada)
                    </label></td>
                <td style="padding-bottom:10px;"><input type="text" size="26" name="email" class="form-control"
                        value="{{ $patient->email }}"></td>
            </tr>
        </table>
        <div class="row" style="padding-left:50px;font-size:18px;margin-top:-35px;">
            <div class="col-12">
                <h5>Maklumat Pembekal/Panel Haemodialisis <br>
                    (Sekiranya Bayaran secara Terus Kepada Pembekal/Panel)
                </h5>
            </div>
        </div>
        <table>
            <tr>
                <td style="font-size:13px;width:135px;"><label>Nama Pembekal/
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<br> Panel
                        Haemodialisis</label></td>
                <td style="padding-left:50px;width:290px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td style="font-size:13px;width:125px;"></label>Alamat
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</u></label>
                </td>
                <td style="padding-left:50px;width:125px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left:50px;width:135px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left:50px;width:135px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left:50px;width:135px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td style="font-size:13px;width:135px"></label>No. Telefon
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</u></label>
                </td>
                <td style="padding-left:50px;width:135px">
                    <p style="border:1px solid grey;" class="form">&nbsp;</p>
                </td>
            </tr>
        </table>

    </div>
    <p style="page-break-before: always">
    <div class="borang">
        <p><b>BQ-BP-14 Borang Perubatan JHEV 1/09
                (T)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pindaan:0
            </b></p>
    </div>
    <div class="other">
        <table class="centertop">
            <tr>
                <td><b>PERMOHONAN PERBELANJAAN KEMUDAHAN PERUBATAN <br>
                        DI BAWAH PEKELILING PERKHIDMATAN BILANGAN 21 TAHUN 2009<br><br>
                        UBAT/ALATAN/PERKHIDMATAN PERUBATAN/RAWATAN </b><br><br>
                </td>
            </tr>
        </table>
        <div style="text-align: left;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Arahan:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Maklumat
            hendaklah dilengkapkan dengan <b>jelas</b> dengan menggunakan <b>huruf besar</b>. <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ii. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sila rujuk <b>panduan</b> yang disediakan bagi butiran yang
            berkaitan.
        </div>



        @if ($patient->relation == 'CardOwner') {{-- need to confirm with syahin --}}
            <div class="row">
                <div class="col-12">
                    <h5 style="background-color:powderblue;">BAHAGIAN I</h5>
                    <h5>Butir Diri Veteran ATM</h5>
                </div>
            </div>

            <table class="center">
                <tr>
                    <td style="width:50%"><label>1. Nama Penuh (seperti dalam kad pengenalan/pasport):</label></td>
                    <td>
                        <input type="text" name="salutation" class="form-control" style="width:15%" value="@if (!empty($patient->card)) {{ $patient->card->salutation }}@else @endif" disabled>
                        <input type="text" name="full_name" class="form-control" style="width:61%" value="@if (!empty($patient->card)) {{ $patient->card->name }}@else @endif" disabled>
                    </td>zz
                </tr>
                <tr>
                    <td style="width:50%"></label>2. No Kad Pengenalan/Pasport:</u></label></td>
                    <td><input type="text" name="identification" class="form-control" style="width:80%"
                            value="@if (!empty($patient->card)) {{ $patient->card->ic_no }}@else @endif" disabled ></td>
                </tr>
                <tr>
                    <td style="width:50%"></label>3. No. Tentera:</u></label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->army_pension }}@else @endif" disabled></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>4. Status:</label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->type }}@else @endif" disabled></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>5. Jenis Tentera:</label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->army_type }}@else @endif" disabled></td>
                    <br>
            </table>

            <div class="row">
                <div class="col-12">
                    <h5>Butir Diri Pesakit (sekiranya pesakit bukan Veteran ATM)</h5>
                </div>
            </div>
            <table class="center">
                <tr>
                    <td style="width:50%"><label>6. Nama Penuh (seperti dalam kad pengenalan/pasport/sijil
                            kelahiran)</label></td>
                    <td><input type="text" class="form-control" style="width:80%"></td>
                </tr>
                <tr>
                    <td style="width:50%"></label>7. No Kad Pengenalan/Pasport/Sijil Kelahiran</u></label></td>
                    <td><input type="text" class="form-control" style="width:80%"></td>
                </tr>
                <tr>
                    <td style="width:50%"></label>8. Hubungan Pesakit Dengan Veteran ATM</u></label></td>
                    <td><input type="text" class="form-control" style="width:80%"></td>
                </tr>
            </table>

            <table class="center">
                <tr>
                    <td style="width:50%"><label>9. Maklumat Tambahan Bagi Anak</u></label></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>i. Umur</label> </td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>ii. Daif</label></td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>iii. Masih Bersekolah</label></td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
            </table>
        @else
            <div class="row">
                <div class="col-12">
                    <h5 style="background-color:powderblue;">Bahagian I</h5>
                    <h5>Butir Diri Veteran ATM</h5>
                </div>
            </div>
            <table class="center">
                <tr>
                    <td style="width:50%"><label>1. Nama Penuh (seperti dalam kad pengenalan/pasport):</label><br>
                    <td>
                        <input type="text" name="salutation" class="form-control" style="width:15%" value="@if (!empty($patient->card)) {{ $patient->card->salutation }}@else @endif" disabled>
                        <input type="text" name="full_name" class="form-control" style="width:61%" value="@if (!empty($patient->card)) {{ $patient->card->name }}@else @endif" disabled>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%"></label>2. No Kad Pengenalan/Pasport:</u></label></td>
                    <td><input type="text" name="identification" class="form-control" style="width:80%"
                            value="@if (!empty($patient->card)) {{ $patient->card->ic_no }}@else @endif" disabled ></td>
                </tr>
                <tr>
                    <td style="width:50%"></label>3. No. Tentera:</u></label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->army_pension }}@else @endif" disabled></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>4. Status:</label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->type }}@else @endif" disabled></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>5. Jenis Tentera:</label></td>
                    <td><input type="text" name="card_type" class="form-control" style="width:80%" value="@if (!empty($patient->card)) {{ $patient->card->army_type }}@else @endif" disabled></td>
                    <br>
            </table>

            <div class="row">
                <div class="col-12">
                    <h5>Butir Diri Pesakit (sekiranya pesakit bukan Veteran ATM)</h5>
                </div>
            </div>
            <table class="center">
                <tr>
                    <td style="width:50%"><label>6. Nama Penuh (seperti dalam kad pengenalan/pasport/sijil
                            kelahiran)</label></td>
                    <td>
                        <input type="text" name="salutation" class="form-control" style="width:15%"
                            value=" {{ $patient->salutation }}" disabled>
                        <input type="text" name="full_name" class="form-control" style="width:61%"
                            value=" {{ $patient->full_name }}" disabled>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%"></label>7. No Kad Pengenalan/Pasport/Sijil Kelahiran</u></label></td>
                    <td><input type="text" class="form-control" style="width:80%"
                            value={{ $patient->identification }}></td>
                </tr>
                <tr>
                    <td style="width:50%"></label>8. Hubungan Pesakit Dengan Veteran ATM</u></label></td>
                    <td><input type="text" class="form-control" style="width:80%" value={{ $patient->relation }}>
                    </td>
                </tr>
            </table>

            <table class="center">
                <tr>
                    <td style="width:50%">9. Maklumat Tambahan bagi Anak</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>i. Umur</label> </td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>ii. Daif</label></td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
                <tr>
                    <td style="width:50%"><label>iii. Masih Bersekolah</label></td>
                    <td><input type="text" class="form-control" style="width:80%" placeholder="No. Telefon"></td>
                </tr>
            </table>
        @endif
        <br>

        <br>
        <h5 style="background-color:powderblue;">Bahagian II</h5>
        <h5>Butir Rawatan Dan Tuntutan Perbelanjaan</h5>

        <table class="center">
            <tr>
                <td style="width:50%"><label>10. Rawatan Di Hospital/Klinik Kerajaan</label></td>
                <td></td>
            </tr>
            <tr>
                <td style="width:50%"><label>i. Nama & Alamat Hospital/Klinik Kerajaan</label></td>
                <td><input type="text" class="form-control" style="width:80%" placeholder="No Kad Pengenalan"></td>
            </tr>
            <tr>
                <td style="width:50%"><label>ii. Tarikh Rawatan</label></td>
                <td><input type="text" class="form-control" style="width:80%" placeholder="No Kad Pengenalan"></td>
            </tr>
            <tr>
                <td style="width:50%"><label>11. Pembekal Kemudahan Perubatan</label></td>
                <td></td>
            </tr>
            <tr>
                <td style="width:50%"><label>i. Nama & Alamat Hospital/Agensi Swasta</label></td>
                <td><input type="text" class="form-control" style="width:80%" placeholder="No Kad Pengenalan" /></td>
            </tr>
            <tr>
                <td style="width:50%"><label>ii. Tarikh Kemudahan Perubatan Diperolehi</label></td>
                <td><input type="text" class="form-control" style="width:80%" placeholder="No Kad Pengenalan" /></td>
            </tr>
            <tr>
                <td style="width:50%"><label>12. Kategori Tuntutan</label></td>
                <td><input type="text" class="form-control" style="width:80%" placeholder="No Kad Pengenalan" /></td>
            </tr>
            <tr>
                <td style="width:50%"><label>13. Senarai Tuntutan(sila gunakan lampiran sekiranya perlu)</label></td>
                <td></td>
            </tr>
        </table>
        <br>
        <table class="bottom">
            <tr>
                <th>Bil</th>
                <th>Nama Ubat/Alat/Perkhidmatan Perubatan/Rawatan</th>
                <th>No Rujukan Dokumen Kewangan</th>
                <th>Harga(RM)</th>
            </tr>
            <tr>
                <td>1</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>2</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>3</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br><br><br><br>
    </div>
    <p style="page-break-before: always">
    <div class="borang">
        <p><b>BQ-BP-14 Borang Perubatan JHEV 1/09
                (T)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pindaan:0
            </b></p>
    </div>
    <div class="other">
        <table class="center">
            <tr>
                <td><label>14. Dokumen Sokongan Yang Disertakan</label></td>
            </tr>
        </table>
        <table class="center">
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Surat Pengesahan Pegawai</td>
            </tr>
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Surat Pengesaha Kementerian Kesihatan Malaysia</td>
            </tr>
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Surat Ketua Pengarah Kesihatan Malaysia</td>
            </tr>
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Surat Pengesahan Institut Pendidikan/ Pengajian
                    Tinggi</td>
            </tr>
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Dokumen Kewangan(contoh: resit, invois, sebut harga atau dokumen
                    kewangan
                    lain yang berkaitan)</td>
            </tr>
            <tr>
                <td style="padding-left:20px;">
                    <div class="div2"></div>
                </td>
                <td style="padding-left:20px;">Resit Asal Yang Hilang Perlu Mendapatkan Salinan Pendua Yang
                    Diperakukan
                    Dengan <b><i>"Certified True Copy"</b></i>
                    Oleh Farmasi Yang
                    Mengeluarkannya
                    Bagi Tujuan Bayaran Balik</td>
            </tr>


        </table>

        <h5 style="background-color:powderblue;">Bahagian III</h5>

        <table class="center">
            <tr>
                <td><label>15. Pengesahan Veteran ATM </label></td>
            </tr>
            <tr>
                <td style="padding-left:40px;">Saya dengan ini mengesahkan bahawa maklumat sebagaimana yang dinyatakan
                    di Bahagian I dan
                    Bahagian
                    II di atas
                    adalah benar belaka. Berkaitan itu, saya memohon supaya perbelanjaan bagi maksud kemudahan
                    perubatan yang diperolehi<br>
                    sebanyak RM ______________ adalah tangunggan oleh Kerajaan."
                </td>
            </tr>
        </table>
        <table class="center">
            <tr>
                <td style="padding-left:40px;">Tandatangan _______________________________<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(nama
                    penuh veteran ATM)<br>
                </td>
                <td>Tarikh _______________________________<br>
                    &nbsp;<br>
                    &nbsp;
                </td>
            </tr>
        </table>

        <h5 style="background-color:powderblue;">Bahagian IV</h5>
        <h5>Perakuan Dan Pengesahan Oleh Pegawai/Pakar Perubatan Kerajaan (Sila guna lampiran sekiranya perlu)
        </h5>

        <table class="center">
            <tr>
                <td><label>16. Nama/Jenis Penyakit Yang Dihidapi Oleh Pesakit </label></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" style="width:80%;"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" style="width:80%;"></td>
            </tr>
            <tr>
                <td>17.Nama Atau Jenis Ubat/Alat/Perkhidmatan</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control " style="width:80%"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" style="width:80%;"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>18.Sebab-sebab Ubat/Alat/Perkhidmatan Perubatan/Rawatan yang Diperlukan Oleh Pesakit Tidak
                    Dapat Dibekalkan/Disediakan Oleh Hospital/Klinik Kerajaan</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" style="width:80%;"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" style="width:80%"></td>
            </tr>
            <tr>
                <td><br>19. Perakuan Dan Pengesahan Pegawai/Pakar Perubatan Kerajaan <br>
                    "Saya dengan ini memperakukan bahawa kemudahan perubatan seperti di Butiran 16 di atas
                    diperlukan oleh pesakit
                    berdasarkan penyakit yang dihidapinya. Saya juga mengesahkan bahawa kemudahan perubatan
                    berkenaan tidak dapat dibekal/
                    disediakan oleh pihak hospital/klinik atas sebab-sebab seperti yang dinyatakan dalam Butiran
                    17 di atas".

                </td>
            </tr>
        </table>

        <table class="center">
            
                <tr>
                    <td style="padding-left:40px;">Tandatangan _______________________________<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(nama
                        penuh)<br>
                        Jawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________<br>
                        Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________
                    </td>
                    <td style="padding-right:55px;">
                        Nama & Cop Rasmi Pegawai/Pakar Perubatan <br>
                        <div class="div1"></div>
                    </td>
                </tr>
        </table>

        <h5 style="background-color:powderblue;">Bahagian V</h5>
        <h5>Kelulusan Penggunaan Ubat (ubat yang tidak disenaraikan dalam senarai ubat-ubatan KKM/hospital Universiti
            sahaja)
        </h5>
        <table class="center">
            <tr>
                <td>20. Kelulusan Penggunaan ubat Oleh Kementerian Kesihatan Malaysia/Pengarah Hospital Universiti</td>
            </tr>
            <tr>
                <td style="padding-left:40px;">"Penggunaan ubat yang <b>tidak disenaraikan</b> dalam senarai ubat-<br>
                    Hospital Universiti seperti di <b>Butiran 16</b> di atas adalah <b>*DILULUSKAN / TIDAK
                        DILULUSKAN."</b>
                </td>
            </tr>
        </table>
        <table class="center">
            <tr>
                <td style="padding-left:40px;">Tandatangan _______________________________<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(nama
                    penuh)<br>
                    Jawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________<br>
                    Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________
                </td>
                <td style="padding-right:55px;">
                    Cop Rasmi KKM/Pengarah Hospital Universiti <br>
                    <div class="div1"></div>
                </td>
            </tr>
        </table>
    </div>
    <p style="page-break-before: always">

    <div class="borang">
        <p><b>BQ-BP-14 Borang Perubatan JHEV 1/09
                (T)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pindaan:0
            </b></p>
    </div>
    <div class="other">
        <h5 style="background-color:powderblue;">Bahagian VI</h5>

        <table class="center">
            <tr>
                <td>21. Pengesahan Dan Keputusan Ketua Jabatan</td>
            </tr>
            <tr>
                <td style="padding-left:40px;">"Saya dengan ini mengesahkan bahawa permohonan veteran ATM mematuhi
                    syarat-syarat dan peraturan-
                    peraturan sebagaimana yang ditetapkan dalam Perintah Am Bab F Tahun 1974 dan Pekeliling Perkhidmatan
                    Bilangan 21 Tahun 2009. Berkaitan itu, permohonan perbelanjaan bagi maksud kemudahan perubatan yang
                    deperolehi sebanyak <b> RM _______________</b> adalah <br><b> *DILULUSKAN / TIDAK DILULUSKAN." </b>
                </td>
            </tr>
        </table>
        <table class="center">
            <tr>
                <td style="padding-left:40px;">Tandatangan _______________________________<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(nama
                    penuh)<br>
                    Jawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________<br>
                    Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________
                </td>
                <td style="padding-right:125px;">
                    Nama & Cop Rasmi <br>
                    <div class="div1"></div>
                </td>
            </tr>
        </table>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </div>
</body>

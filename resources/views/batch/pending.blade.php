@extends('layouts.app')


@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">View Order <span class="badge bg-info">Pending Batch Order</span></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('batch') }}">Orders</a></li>
                            <li class="breadcrumb-item active">View Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Patient Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patient Name</label>
                                                <input type="text" class="form-control" value="ABDUL HAKIM BIN AB RAHMAN"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>IC Number</label>
                                                <input type="text" class="form-control" value="950506045329" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date" class="form-control" placeholder="Patient Name"
                                                    value="1995-05-06" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control" disabled>
                                                    <option selected>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input type="text" class="form-control" value="+60133375724" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <input type="email" class="form-control" value="ahbar95@gmail.com" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Card Type</label>
                                                <select class="form-control" disabled>
                                                    <option selected>PENSIONABLE VETERAN</option>
                                                    <option>NON-PENSIONABLE VETERAN</option>
                                                    <option>JPA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pension / Army Number</label>
                                                <input type="text" class="form-control" value="74124598" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>IC Attachment</label>
                                                <input type="text" class="form-control" value="file1.jpg" readonly
                                                    onclick="window.open('dist/img/avatar.png');" style="cursor:pointer;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Support Latter Attachment</label>
                                                <input type="text" class="form-control" value="file1.jpg" readonly
                                                    onclick="window.open('dist/img/avatar.png');" style="cursor:pointer;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Dispense Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Payment Method</label>
                                                <input type="text" class="form-control" value="PANEL" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Order Type</label>
                                                <select class="form-control" disabled>
                                                    <option selected>DRUG</option>
                                                    <option>MEDICAL DEVICE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Dispensing By</label>
                                                <select class="form-control" disabled>
                                                    <option selected>FVKL</option>
                                                    <option>FVT</option>
                                                    <option>FVL</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Dispensing Method</label>
                                                <select class="form-control" id="dispensingMethod" onchange="deliveryAdd();"
                                                    disabled>
                                                    <option value="1">WALK IN</option>
                                                    <option value="2" selected>DELIVERY COURIER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="rowAddress">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Address Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address 1</label>
                                                <input type="text" class="form-control" value="NO 19, JALAN LINTANG JAYA 1"
                                                    readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Address 2</label>
                                                <input type="text" class="form-control" value="TAMAN LINTANG JAYA" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Postcode</label>
                                                <input type="text" class="form-control" value="75460" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" value="AYER MOLEK" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State</label>
                                                <select class="form-control" disabled>
                                                    <option>Wilayah Persekutuan Kuala Lumpur</option>
                                                    <option>Wilayah Persekutuan Labuan</option>
                                                    <option>Wilayah Persekutuan Putrajaya</option>
                                                    <option>Perlis</option>
                                                    <option>Kedah</option>
                                                    <option>Pulau Pinang</option>
                                                    <option>Perak</option>
                                                    <option>Pahang</option>
                                                    <option>Terengganu</option>
                                                    <option>Kelantan</option>
                                                    <option>Selangor</option>
                                                    <option>Negeri Sembilan</option>
                                                    <option selected>Melaka</option>
                                                    <option>Johor</option>
                                                    <option>Sarawak</option>
                                                    <option>Sabah</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked disabled>
                                                <label class="form-check-label">Use Address in Patinet's Card</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Prescription Information</h3>
                                    <div class="card-tools">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="NSD"
                                                    onchange="displayNSD();" disabled>
                                                <label class="custom-control-label" for="NSD">Set One Off Supply</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hospital</label>
                                                <select class="form-control" disabled>
                                                    <option value="">Please choose hospital/clinic...</option>
                                                    <option value="">711 PUSAT PERUBATAN ANGKATAN TENTERA</option>
                                                    <option value="" selected>93 HOSPITAL ANGKATAN TENTERA TUANKU MIZAN
                                                    </option>
                                                    <option value="">94 HOSPITAL ANGKATAN TENTERA KEM TERENDAK</option>
                                                    <option value="">96 HOSPITAL ANGKATAN TENTERA LUMUT</option>
                                                    <option value="">HOSPITAL BAHAGIAN MIRI</option>
                                                    <option value="">HOSPITAL BAHAGIAN SRI AMAN</option>
                                                    <option value="">HOSPITAL BATU GAJAH</option>
                                                    <option value="">HOSPITAL BINTULU</option>
                                                    <option value="">HOSPITAL DUNGUN</option>
                                                    <option value="">HOSPITAL ENCHE BESAR HAJJAH KALSOM</option>
                                                    <option value="">HOSPITAL GUA MUSANG</option>
                                                    <option value="">HOSPITAL JENGKA</option>
                                                    <option value="">HOSPITAL JERANTUT</option>
                                                    <option value="">HOSPITAL KAMPAR</option>
                                                    <option value="">HOSPITAL KENINGAU</option>
                                                    <option value="">HOSPITAL KOTA MARUDU</option>
                                                    <option value="">HOSPITAL KUALA KANGSAR</option>
                                                    <option value="">HOSPITAL KUALA LIPIS</option>
                                                    <option value="">HOSPITAL LABUAN</option>
                                                    <option value="">HOSPITAL LAHAD DATU</option>
                                                    <option value="">HOSPITAL MERSING</option>
                                                    <option value="">HOSPITAL PAKAR SULTANAH FATIMAH</option>
                                                    <option value="">HOSPITAL PERMAI JOHOR BAHRU</option>
                                                    <option value="">HOSPITAL PUTRAJAYA</option>
                                                    <option value="">HOSPITAL RAJA PERMAISURI BAINUN, IPOH</option>
                                                    <option value="">HOSPITAL RAUB</option>
                                                    <option value="">HOSPITAL ROMPIN</option>
                                                    <option value="">HOSPITAL SERDANG</option>
                                                    <option value="">HOSPITAL SIK, KEDAH</option>
                                                    <option value="">HOSPITAL SULTAN HAJI AHMAD SHAH, TEMERLOH</option>
                                                    <option value="">HOSPITAL SULTAN ISMAIL</option>
                                                    <option value="">HOSPITAL SULTANAH AMINAH</option>
                                                    <option value="">HOSPITAL SULTANAH NUR ZAHIRAH</option>
                                                    <option value="">HOSPITAL SUNGAI BAKAP</option>
                                                    <option value="">HOSPITAL SUNGAI BULOH</option>
                                                    <option value="">HOSPITAL SUNGAI SIPUT</option>
                                                    <option value="">HOSPITAL TAIPING, PERAK</option>
                                                    <option value="">HOSPITAL TAMPIN</option>
                                                    <option value="">HOSPITAL TANAH MERAH</option>
                                                    <option value="">HOSPITAL TELOK INTAN</option>
                                                    <option value="">HOSPITAL TENGKU AMPUAN AFZAN</option>
                                                    <option value="">HOSPITAL TENGKU ANIS</option>
                                                    <option value="">HOSPITAL TUANKU AMPUAN NAJIHAH</option>
                                                    <option value="">HOSPITAL TUANKU JA AFAR</option>
                                                    <option value="">HOSPITAL TUMPAT</option>
                                                    <option value="">KEMENTERIAN PERTAHAN MALAYSIA (MINDEF)</option>
                                                    <option value="">KLINIK KESIHATAN BANDAR GUA MUSANG</option>
                                                    <option value="">KLINIK KESIHATAN BANDAR PEKAN</option>
                                                    <option value="">KLINIK KESIHATAN DAERAH JELI</option>
                                                    <option value="">KLINIK KESIHATAN WAKAF BHARU</option>
                                                    <option value="">PEJABAT FARMASI BAHAGIAN LIMBANG</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH BACHOK</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH HULU LANGAT</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH JELI</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH JEMPOL</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KAMPAR</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KLUANG</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KOTA BHARU</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KOTA TINGGI</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KUALA KRAI</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KUALA NERUS</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH KUANTAN</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH MARAN</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH MARANG</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH PARIT BUNTAR</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH PETALING</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH PONTIAN</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH PORT DICKSON</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH REMBAU</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH SEGAMAT</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH SEPANG</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH TIMUR LAUT</option>
                                                    <option value="">PEJABAT KESIHATAN DAERAH TUMPAT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>RX Number</label>
                                                <input type="text" class="form-control" value="RX00000001" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>RX Attachment | <i>File Uploaded: <a href="#">Rx.pdf</a></i></label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                                            disabled>
                                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="colRxStart">
                                            <div class="form-group">
                                                <label>RX Start</label>
                                                <input type="date" class="form-control" value="2020-10-27" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="colRxEnd">
                                            <div class="form-group">
                                                <label>RX End</label>
                                                <input type="date" class="form-control" value="2021-10-27" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="colNSD">
                                            <div class="form-group">
                                                <label>Next Supply Date</label>
                                                <input type="date" class="form-control" value="2021-01-26" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Order Entry</h3>
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm">

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="overflow-x:auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Dose Qty.</th>
                                                <th>Dose UOM</th>
                                                <th>Freq.</th>
                                                <th>Duration</th>
                                                <th>Ins.</th>
                                                <th>Qty.</th>
                                                <th>Unit Price</th>
                                                <th>Total Amount</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:260px;" disabled>
                                                            <option selected>VAXIGRIP (Influenza) VACCINE INJ</option>
                                                            <option>GRANOCYTE 34 (Lenograstim) 33.6MIU INJ</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="1"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:74px;" disabled>
                                                            <option selected>TAB</option>
                                                            <option>VAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:60px;" disabled>
                                                            <option selected>W</option>
                                                            <option>OD</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:67px;" disabled>
                                                            <option selected>1M</option>
                                                            <option>2M</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:180px;" disabled>
                                                            <option selected>IN014 - BEFORE MEAL</option>
                                                            <option selected>IN015 - AFTER MEAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="30"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="30.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="190.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="width:83px;">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:260px;" disabled>
                                                            <option selected>VAXIGRIP (Influenza) VACCINE INJ</option>
                                                            <option>GRANOCYTE 34 (Lenograstim) 33.6MIU INJ</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="1"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:74px;" disabled>
                                                            <option selected>TAB</option>
                                                            <option>VAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:60px;" disabled>
                                                            <option selected>W</option>
                                                            <option>OD</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:67px;" disabled>
                                                            <option selected>1M</option>
                                                            <option>2M</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:180px;" disabled>
                                                            <option selected>IN014 - BEFORE MEAL</option>
                                                            <option selected>IN015 - AFTER MEAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="30"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="30.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="190.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="width:83px;">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group" disabled>
                                                        <select class="form-control" style="width:260px;" disabled>
                                                            <option selected>VAXIGRIP (Influenza) VACCINE INJ</option>
                                                            <option>GRANOCYTE 34 (Lenograstim) 33.6MIU INJ</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="1"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:74px;" disabled>
                                                            <option selected>TAB</option>
                                                            <option>VAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:60px;" disabled>
                                                            <option selected>W</option>
                                                            <option>OD</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:67px;" disabled>
                                                            <option selected>1M</option>
                                                            <option>2M</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width:180px;" disabled>
                                                            <option selected>IN014 - BEFORE MEAL</option>
                                                            <option>IN015 - AFTER MEAL</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="30"
                                                            style="width:60px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="30.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="190.00"
                                                            style="width:78px;" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="width:83px;">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Order Attachment</h3>
                                </div>
                                <div class="card-body">
                                    <p>Uploaded File: <a href="#">DN0000121.pdf</a></p>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" disabled>
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-footer">
                                    <div class="form-group">
                                        <button class="btn btn-primary"
                                            style="float:right; margin-left:3px; margin-right:3px;">Generate Dispensing Order Note
                                        </button>
                                        {{-- <a href="{{ action('BatchController@downloadPDF2') }}" target="_blank"
                                            class="btn btn-info"><i class="mdi mdi-file-pdf"></i>Generate Invoice</a>
                                        <a href="{{ action('BatchController@downloadPDF3') }}" target="_blank"
                                            class="btn btn-info"><i class="mdi mdi-file-pdf"></i>Generate DO</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                </form>
            </div>
        </section>
    @endsection
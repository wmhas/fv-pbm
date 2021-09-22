@section('script')
    <script src="{{ asset('js/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // calculate quantity based on f x dq x d
            $('input[type="number"] ,input[type="text"] ').keyup(function() {

                var dose_quantity = parseFloat($('.value_dq').val());
                var frequency = $('.value_f').val();
                // var frequency = $('.value_f').prop('selectedIndex',0);
                var duration = parseFloat($('.value_d').val());
                var unit_price = parseFloat($('.price').val());
                var uom = $('.uom').val();
                var formula_id = $('.formula_id').val();
                var formula_value = $('.formula_value').val();

                if (frequency == 'OD' || frequency == 'PRN' || frequency == 'OM' || frequency == 'ON' ||
                    frequency == 'STAT') {
                    var frequency = 1;
                } else if (frequency == 'BD') {

                    var frequency = 2;

                } else if (frequency == 'TDS') {

                    var frequecy = 3;

                } else {
                    var frequency = 4;
                }

                //mcm mana nak retrieve formula_id dengan formula_value
                if (formula_id == '1') {
                    var quantity = dose_quantity * frequency * duration;

                } else if (formula_id == '6') {
                    var quantity = 1;

                } else {

                    var quantity = (dose_quantity * frequency * duration) / formula_value;

                }

                var sum = quantity * unit_price;

                parseFloat($("input#quantity").val(quantity.toFixed(2)));
                parseFloat($("input#price").val(sum.toFixed(2)));
            });


            // Department Change
            $('#item_id').change(function() {
                $('#quantity').val('');
                // Department id
                var id = $(this).val();
                // console.log(id);
                // Empty the dropdown
                $('#selling_price').find('option').not(':first').remove();
                $('#selling_uom').find('option').not(':first').remove();
                $('#instruction').find('option').not(':first').remove();
                $('#indication').find('option').not(':first').remove();

                // AJAX request 
                $.ajax({
                    url: '/getItemDetails/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        console.log(len);

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var selling_price = response['data'][i].selling_price;
                                var selling_uom = response['data'][i].selling_uom;
                                var instruction = response['data'][i].instruction;
                                var indication = response['data'][i].indication;
                                var frequency = response['data'][i].name;
                                var frequency_id = response['data'][i].freq_id;
                                var formula_id = response['data'][i].formula_id;
                                var formula_value = response['data'][i].value;

                                // console.log(frequency);
                                // var option = "<option value='"+id+"'>"+amount+"</option>"; 

                                // $("#unit_price").append(option);
                                $("#selling_price").val(selling_price);
                                $("#selling_uom").val(selling_uom);
                                $("#instruction").val(instruction);
                                $("#indication").val(indication);
                                $("#frequency option[value='" + frequency_id + "']").attr(
                                    'selected', 'selected');
                                $("#formula_id").val(formula_id);
                                $("#formula_value").val(formula_value);
                                // $("#gst").val(0.00);
                            }
                        }

                    }
                });
            });
        });

        //search item on dropdown
        $(document).ready(function() {
            $('.js-single').select2();
        });

        //set on off supply on prescription
        $('#NSD').change(function() {
            if ($(this).prop("checked")) {
                $('#colNSD').hide();
                $('#rx_interval').val(1);
            } else {
                $('#colNSD').show();
                $('#rx_interval').val(2);
            }
        });

        $(function formRX() {
            if ({{ $order->rx_interval }} == 1) {
                $('#colNSD').hide();
                // console.log('true')
            } else {
                $('#colNSD').show();
            }
        });

        //hide delivery div
        $(document).ready(function() {
            $("#dispensing_method").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    console.log(optionValue);
                    if (optionValue) {
                        $(".delivery").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".delivery").hide();
                    }
                });
            }).change();
        });

        //file upload
        $(function() {
            bsCustomFileInput.init();
        });

        $('#dispensing_by').change(function() {
            var id = $(this).val();
            // console.log(id);
            $.ajax({
                url: '/ajax/getDONumber/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    console.log(response);
                    $("#do_number").val(response);
                }
            });
        });

        $(document).ready(function() {
            var id = '{{$order->dispensing_by}}';
            // console.log(id);
            $.ajax({
                url: '/ajax/getDONumber/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    console.log(response);
                    $("#do_number").val(response);
                }
            });
        });
    </script>
@endsection

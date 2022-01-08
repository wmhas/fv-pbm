@section('script')
    <script src="{{ asset('js/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">

        function calculateQuantity(thisParent, except = [], quantity = null){
            var dose_quantity = parseFloat(thisParent.find('.value_dq').val());
            var frequency = thisParent.find('.value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat(thisParent.find('.value_d').val());
            var unit_price = parseFloat(thisParent.find('.price').val());
            var uom = thisParent.find('.uom').val();
            var formula_id = thisParent.find('.formula_id').val();
            var formula_value = thisParent.find('.formula_value').val();

            if (frequency == 1 || frequency == 5 || frequency == 6 || frequency == 7 ||
                frequency == 8) {
                var frequency = 1;
            } else if (frequency == 2) {

                var frequency = 2;

            } else if (frequency == 3) {

                var frequecy = 3;

            } else {
                var frequency = 4;
            }

            //mcm mana nak retrieve formula_id dengan formula_value
            if (quantity === null) {
                if (formula_id == 1) {
                    quantity = dose_quantity * frequency * duration;
                } else if (formula_id == 6) {
                    quantity = 1;
                } else {
                    quantity = (dose_quantity * frequency * duration) / formula_value;
                }
            } else {
                quantity = parseFloat(quantity);
            }

            ceilQ = Math.ceil(quantity.toFixed(2));
            var sum = ceilQ * unit_price;

            if (!except.includes('quantity')) {
                parseFloat(thisParent.find("input#quantity").val(ceilQ));
            }
            if (!except.includes('price')) {
                parseFloat(thisParent.find("input#price").val(sum.toFixed(2)));
            }
        }

        function uCalculateQuantity(thisParent, except = [], quantity = null){
            var dose_quantity = parseFloat(thisParent.find('.u_value_dq').val());
            var frequency = thisParent.find('.u_value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat(thisParent.find('.u_value_d').val());
            var unit_price = parseFloat(thisParent.find('.u_price').val());
            var uom = thisParent.find('.u_uom').val();
            var formula_id = thisParent.find('.u_formula_id').val();
            var formula_value = thisParent.find('.u_formula_value').val();

            if (frequency == 1 || frequency == 5 || frequency == 6 || frequency == 7 ||
                frequency == 8) {
                var frequency = 1;
            } else if (frequency == 2) {

                var frequency = 2;

            } else if (frequency == 3) {

                var frequecy = 3;

            } else {
                var frequency = 4;
            }

            //mcm mana nak retrieve formula_id dengan formula_value
            if (quantity === null) {
                if (formula_id == 1) {
                    quantity = dose_quantity * frequency * duration;
                } else if (formula_id == 6) {
                    quantity = 1;
                } else {
                    quantity = (dose_quantity * frequency * duration) / formula_value;
                }
            } else {
                quantity = parseFloat(quantity);
            }

            ceilQ = Math.ceil(quantity.toFixed(2));
            var sum = ceilQ * unit_price;

            if (!except.includes('quantity')) {
                parseFloat(thisParent.find("input#u_quantity").val(ceilQ));
            }
            if (!except.includes('price')) {
                parseFloat(thisParent.find("input#u_price").val(sum.toFixed(2)));
            }
        }

        function ajaxUpdateItem(id){
             // AJAX request
            $.ajax({
                url: '/getItemDetails/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

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
                            $("#u_selling_price").val(selling_price);
                            $("#u_selling_uom").val(selling_uom);
                            $("#u_instruction").val(instruction);
                            $("#u_indication").val(indication);
                            $("#u_frequency option[value='" + frequency_id + "']").attr(
                                'selected', 'selected');
                            $("#u_formula_id").val(formula_id);
                            $("#u_formula_value").val(formula_value);
                            // $("#gst").val(0.00);
                        }
                    }

                }
            });
        }

        $(document).ready(function() {
        // calculate quantity based on f x dq x d
            $('input[type="number"] ,input[type="text"] ').keyup(function() {
                thisParent = $(this).parent().parent().parent();
                if ( this.id === 'quantity' || this.id === 'u_quantity' ) {
                    const quantity = $(this).val().trim();
                    calculateQuantity(thisParent, ['quantity'], quantity);
                    uCalculateQuantity(thisParent, ['quantity'], quantity);
                } else {
                    calculateQuantity(thisParent);
                    uCalculateQuantity(thisParent);
                }
            });

            $(document).on("change","#frequency",function(){
                thisParent = $(this).parent().parent().parent();
                calculateQuantity(thisParent);
            });

            $(document).on("change","#u_frequency",function(){
                thisParent = $(this).parent().parent().parent();
                uCalculateQuantity(thisParent);
            });

            $('#item_id').change(function() {
                var hidden_duration = $('#hidden_duration').val();
                $('#quantity').val('');
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
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

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
                                $('#duration').val(hidden_duration);
                                // $("#gst").val(0.00);
                            }
                        }

                    }
                });
            });
        
            $(document).on("click","#editItem",function(e){
                e.preventDefault();
                order_item_id = $(this).parent().parent().parent().find("#order_item_id");
                item = $(this).parent().parent().parent().find("#i_item");
                item_title = $(this).parent().parent().parent().find("#i_item_title");
                quantity = $(this).parent().parent().parent().find("#i_quantity");
                frequency = $(this).parent().parent().parent().find("#i_frequency");
                intruction = $(this).parent().parent().parent().find("#i_intruction");
                indication = $(this).parent().parent().parent().find("#i_indication");
                total_price = $(this).parent().parent().parent().find("#i_total_price");
                unit_price = $(this).parent().parent().parent().find("#i_unit_price");
                dose_qty = $(this).parent().parent().parent().find("#i_dose_qty");
                dose_duration = $(this).parent().parent().parent().find("#i_dose_duration");
                dose_uom = $(this).parent().parent().parent().find("#i_dose_uom");

                $("#u_item_id").val(item.val().replaceAll(/\s/g,''));
                $("#u_order_item_id").val(order_item_id.val().replaceAll(/\s/g,''));
                $("#u_item_title").val(item_title.val());
                $("#u_indication").val(indication.val());
                $("#u_instruction").val(intruction.val());
                $("#u_frequency").val(frequency.val().replaceAll(/\s/g,''));
                $("#u_dose_quantity").val(dose_qty.val());
                $("#u_duration").val(dose_duration.val());
                $("#u_quantity").val(dose_qty.val());
                $("#u_selling_price").val(unit_price.val());
                $("#u_price").val(total_price.val());
                $("#u_selling_uom").val(dose_uom.val());

                $('#u_quantity').val(quantity.val());
                var id = item.val();
                ajaxUpdateItem(id);

                modalEdit = $("#modalEditItem");
                modalEdit.modal("show");

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

        async function checkDate() {
            const start = $('[name="rx_start_date"]').val();
            const end = $('[name="rx_end_date"]').val();
            if (start && end) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                if (startDate >= endDate) {
                    alert('RX Start must be less than RX End');
                    if ($(this).attr('name') === 'rx_start_date') {
                        $('[name="rx_start_date"]').val(end);
                    } else {
                        $('[name="rx_end_date"]').val(start);
                    }
                }
            }
        }
        $(document).ready(function () {
            $('[name="rx_start_date"]').change(checkDate);
            $('[name="rx_end_date"]').change(checkDate);
        });
    </script>
@endsection

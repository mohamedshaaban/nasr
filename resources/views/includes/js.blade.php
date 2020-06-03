<script>
    function togglePanel (){
        var w = $(window).width();
        if (w <= 767) {
            $('.hide-mob').removeClass('in');
        } else {
            $('.hide-mob').addClass('in');
        }
    }

    $(window).resize(function(){
        togglePanel();
    });

    togglePanel();
</script>
<script>
    @if(Session::has('success'))

    swal("{{ Session::get('title') }}", "{{ Session::get('success') }}", "success");
    @endif
    @if(Session::has('error'))
    swal("{{ Session::get('title') }}", "{{ Session::get('error') }}", "error");
    @endif
    @if(Session::has('info'))
    swal("{{ Session::get('title') }}", "{{ Session::get('info') }}", "info");
    @endif

    function getSizeText(id , productId) {
        $('#smlSizeText').html('');
        $.ajax({
            method: "get",
            url: "/getSizeInfo/"+id+"/"+productId,

            success: function(result) {
                $('#smlSizeText').html(result.description);
            }
        });

    }

    function editAddress(id) {
        $.ajax({
            method: "get",
            url: "/getAddressInfo/"+id,

            success: function(result) {
                $("input[name=first_name]").val(result.address.first_name);
                $("input[name=second_name]").val(result.address.second_name);
                $("input[name=address_id]").val(result.address.id);
                $("input[name=phone_no]").val(result.address.phone_no);
                $("input[name=fax]").val(result.address.fax);
                $("input[name=city]").val(result.address.city);
                $("input[name=company]").val(result.address.company);
                $("input[name=zip_code]").val(result.address.zip_code);
                $("input[name=city]").val(result.address.city);
                $("input[name=block]").val(result.address.block);
                $("input[name=street]").val(result.address.street);
                $("input[name=avenue]").val(result.address.avenue);
                $("input[name=floor]").val(result.address.floor);
                $("input[name=flat]").val(result.address.flat);
                $("input[name=extra_direction]").val(result.address.extra_direction);

                $("#country_id").val(result.address.country_id);
                $('#governate_id').empty();
                $('#area_id').empty();
                var option = new Option("{{__('website.please_select_label')}}", "");

                $("#governate_id").append(option);
                $("#area_id").append(option);
                $.each(result.governorates, function(index, value){
                    var option = new Option(value.name_en, value.id);

                    $("#governate_id").append(option);

                });


                $("#area_id").append(option);
                $.each(result.areas, function(index, value){
                    var option = new Option(value.name_en, value.id);

                    $("#area_id").append(option);

                });
                $("#area_id").val(result.address.area_id);
                $("#governate_id").val(result.address.governate_id);

                if(result.address.address_type ==1 && result.address.is_default == 1)
    {
        $('#billing_default').prop('checked', true);
        $('#shipping_default').prop('checked', false);
    }
    else if(result.address_type == 2 && result.is_default == 1)
    {
        $('#shipping_default').prop('checked', true);
        $('#billing_default').prop('checked', false);
    }
            }
        });

    }
    function deleteAddress(id)
    {
        $.ajax({
            method: "get",
            url: "/deleteAddressInfo/"+id,
            success: function(result) {
                if(result.status == true)
                {
                    $('#address'+id).remove();
                    swal("Your Address Has Been Deleted");
                }
        }
        });
    }
    function checkEmail(email) {

        $.ajax({
            method: "post",
            url: "/checkEmail",
            data: {'email':email,'_token': $("input[name=_token]").val()},

            success: function(result) {
            result = JSON.parse(result);
            if(result.status == false)
            {
                $('#classerrorEmail').addClass('has-error');
                $('#errorEmail').html('<p class="help-block">{{__('website.invalid_email_label')}}</p>');
            }
            else
            {
                $('#classerrorEmail').removeClass('has-error');
                $('#errorEmail').html('');
            }
            }

        });
    }



        $('a[href="#Reviews"]').on('shown.bs.tab', function (e) {
            $('#rateit7').rateit({ max: 5, step: 0.5, backingfld: '#backing7', mode: 'font' });
            $('.rateit').rateit();
        });
    $(document).ready(function () {
        $('.select-box input').click(function () {
            $('.select-box input:not(:checked)').parent().parent().css("border", "1px solid #f0f0f0");
            $('.select-box input:checked').parent().parent().css("border", "1px solid #57991f");
        });
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#next-rev-pay').click(function (e) {

        e.preventDefault();
        $('#payment-tabs a:last').tab('show');
    })
function getgovernate(country_id) {
    $('#governate_id').empty();
    $.ajax({
        method: "post",
        url: "/country/getgovernate",
        data: {'country_id':country_id,'_token': $("input[name=_token]").val()},

        success: function(result) {
            var option = new Option("{{__('website.please_select_label')}}", "");

            $("#governate_id").append(option);
            $.each(result, function(index, value){
                var option = new Option(value.name_en, value.id);

                $("#governate_id").append(option);
            });
        }

    });

}

    function getarea(governate_id) {
        $('#area_id')
            .empty();
        $.ajax({
            method: "post",
            url: "/country/getareas",
            data: {'governorate_id':governate_id,'_token': $("input[name=_token]").val()},

            success: function(result) {
                var option = new Option("{{__('website.please_select_label')}}", "");

                $("#area_id").append(option);
                $.each(result, function(index, value){
                    var option = new Option(value.name_en, value.id);

                    $("#area_id").append(option);

                });
            }

        });
    }
    $.ajaxSetup({
        beforeSend:function(){
            $(".loading-holder").css("display", "block");
        },
        complete:function(){
            $(".loading-holder").css("display", "none");
        },
        error:function(){
            $(".loading-holder").css("display", "none");
        }
    });

</script>

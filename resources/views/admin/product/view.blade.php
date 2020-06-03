<script>


     $( ".product_type" ).on('select2:select', function (e) {
        var data= $(this).val();

        if(data==1||data==3||data==4||data==5||data==6||data==7)
        {
            $.ajax({
                method: "get",
                url: "/admin/getsizestype/"+data,

                success: function(result) {
                    console.log($("select.productsizes").empty());
                    $("select.productsizes").trigger('change');
                    $.each(result, function(i, item) {
                        $("select.productsizes").append(
                            $("<option>")
                                .attr("value", item.id)
                                .text(item.name_en)
                        );
                    });

                  console.log(1);
                    $("select.productsizes").trigger('change');
                }
            });

        $('[href="#tab-form-3"]').closest('li').show();
        $('[href="#tab-form-4"]').closest('li').hide();
        }
        else if(data==2)
        {
            $('[href="#tab-form-3"]').closest('li').hide();
            $('[href="#tab-form-4"]').closest('li').show();
        }
            else
         {
             $('[href="#tab-form-4"]').closest('li').hide();
             $('[href="#tab-form-3"]').closest('li').hide();
         }
    });

</script>

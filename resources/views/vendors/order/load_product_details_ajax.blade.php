 <div class="col-md-6 product_options">
        <div class="form-group">
                @php
                $options = $product->getOptions();
                
                $div4 ='';
                $div12 ='';
                
                /** @var \App\Models\Option $option */
                foreach($options as $option){

                    $optionHTML = $option->getHTML();
                    preg_match_all('/col-.{2}-4/', $optionHTML, $output_array);
                    if(count($output_array[0])){
                        $div4 .= $optionHTML."\n";
                    }
                    else {
                        $div12 .= "<div class='row'>\n".$optionHTML."\n</div>\n\n";
                    }
                }
                
                if($div4!=''){
                    echo "<div class='row'>\n".$div4."\n</div>\n\n";
                }
                
                echo $div12;
                
                @endphp
                        </div>
    </div>

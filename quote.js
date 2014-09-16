$(function() {

	if (location.pathname == "/ProductDetails.asp" || location.pathname.indexOf("-p/") != -1 || location.pathname.indexOf("_p/") != -1){

		var price = $('span[itemprop=price]').html();
		//alert(price);
		var carton = $('span[itemprop=carton]').html();
		//alert(carton);

		if (!carton){
			//alert("Carton Price Not Set");
		} else {
			// prepare calc	
			$('.colors_pricebox').after().append('<div id="calculator_sqft"><div class="calculator_sqft_t"><table width=400 border=0 cellspacing=5 cellpadding=0 class="boxp"><tr><td colspan=2 align=center valign=top><span class="cartontitle">Carton Calculator</span></td></tr><tr><td width=233 align=left valign=top>Enter Sqft needed:</td><td width=122 align=left valign=top class=sqfttt><input type=text name=sqft value="" /></td></tr><tr><tr><td width="233" align="left" valign="top">Ship To Zipcode</td><td width="122" align="left" valign="top"><input type="text" name="shipto_zip" value="" /><td></tr><td width=233 align=left valign=top>Actual Square feet:</td><td width=122 align=left valign=top class=csqft_pricettt><span class=csqft_price1></span></td></tr><tr><td width=233 align=left valign=top>Cartons to order:</td><td width=122 align=left valign=top class=csqft_pricettt><input type=text name=cartons value="" /></td></tr><tr><td width=233 align=left valign=top><span style="color:#890016;">Total Amount:</span></td><td width=122 align=left valign=top class=result_csqft></td></tr><<tr><td width=233 align=left valign=top><span style="color:#890016;">Shipping Charge:</span></td><td width=122 align=left valign=top class=result_shipping></td></tr>/table><input type=text name=sf value="" style="display:none;" /></div></div>');
			// end prepare calc
			
			// SET Total to 0.00
			$('#calculator_sqft .result_csqft').html('$0.00');
			
			$('#calculator_sqft input[name=sqft]').live('keyup', function() {
				var price = $('span[itemprop=price]').eq(0).text();
				var fixprice = +(price.charAt(0) === "$" ? price.substr(1) : price);
				//alert(fixprice);
				var sqft = $(this).val();
				var result_cart = Math.ceil(sqft / carton);
				var actual_sqft = result_cart * carton;
				var adjusted_sqft = Math.round(result_cart * carton);
				var end_price = adjusted_sqft * fixprice;
				$('.csqft_price1').html(actual_sqft.toFixed(2));
				$('input[name=cartons]').val(result_cart);
				$('.result_csqft').html('$' + end_price.toFixed(2) + ' <span style="color: #f0b404; font: 14px arial;"></span>');
				$('input').eq(2).val(adjusted_sqft);
			});
		}
		
	} else {
	//alert('hi');
	}
});

$(document).ready(function () {
	$('#process_shipping').click(function (event) {
		event.preventDefault();
		alert('hi');
		$.ajax({
            url: 'http://ec2-54-200-159-33.us-west-2.compute.amazonaws.com/echo-proxy.php',
			data: { 
				origin: '',
				destination: '',
				weight: ''
			},
            dataType: 'jsonp',
            jsonp: 'callback',
            jsonpCallback: 'jsonpCallback',
            success: function(){
                //console.log("successfully got information from the proxy");
            }
        });
	});
});

function jsonpCallback(data) {
    $('#calculator_sqft .result_shipping').html('$' + data.GetQuoteResult.RateQuote.RateDetails.RateDetail.Charges.TotalCharge);
    //console.log(data.GetQuoteResult.RateQuote.RateDetails.RateDetail.Charges.TotalCharge);
}
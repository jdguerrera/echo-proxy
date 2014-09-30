$(function() {

	// Price variables
	var price = $('span[itemprop=price]').html();
	var carton = $('span[itemprop=carton]').html();
	var origin_zipcode = $('span[itemprop=warehouse]').html();

	// Shipping variables

	var shipdate = $.datepicker.formatDate('yy-mm-dd', new Date());
	var destination_zipcode = 0;
	var weight = 0;

	// Images
	var ajax_loader_img = "/v/vspfiles/images/ajax-loader.gif";
	var calculate_img = "/v/vspfiles/images/calculate.png";

	var updateShipping = function (){
		$('.shipping_cost').html('<a href="#" class="shipping_link" style="background:url(' + calculate_img + ');width:106px;height:22px;display:block;"></a>');
		// Add Calculate Shipping button
		$('.shipping_link').click(function() {
			calculateShippingCost();
		});
	}

	var calculateShippingCost = function() {
		destination_zipcode = $('input[name="zipcode"]').val();
		weight = $('span[itemprop="weight"]').text() * $('input').eq(2).val();
		if( destination_zipcode && weight ) {
			getShippingCost( origin_zipcode, destination_zipcode, shipdate, weight );
		}
	}
	var getShippingCost = function(origin_zipcode, destination_zipcode, shipdate, weight) {
		var url =   "http://ec2-54-200-159-33.us-west-2.compute.amazonaws.com" +
					"/echo-proxy.php?"+
					//"callback=callback&" +
					"origin=" + origin_zipcode + "&" +
					"destination=" + destination_zipcode + "&" +
					"shipdate=" + shipdate + "&"+
					"weight=" + weight;
		if ($('input[name="residential"]').attr('checked')) {
			url = url + "&residential=1";
		}

		$('.shipping_cost').html('<img src="' + ajax_loader_img + '" width="16" style="border:none;" height="16" alt="loading...">');
		$.ajax({
			url: url,
			dataType: 'jsonp',
			success: function(json){
				try {
					var quote = json.GetQuoteResult.RateQuote.RateDetails.RateDetail.Charges.TotalCharge;
					$('.shipping_cost').text( "$" + quote );
				}catch(E){
					$('.shipping_cost').text( "n/a" );
				}			
			}
		});
	}


	// If there is carton info available
	if (carton) {
		// Populare Carton Calculator
		$('.colors_pricebox').after().append(
			'<div id="calculator_sqft">' +
			'  <div class="calculator_sqft_t">' +
			'    <table width=400 border=0 cellspacing=5 cellpadding=0 class="boxp">' +
			'      <tr>' +
			'        <td colspan=2 align=center valign=top><span class="cartontitle">Carton Calculator</span></td>'+
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top >Enter Sqft needed:</td>' +
			'        <td width=122 align=left valign=top class="sqfttt" ><input type=text name="sqft" value="" /></td>' +
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top>Actual Square feet:</td>' +
			'        <td width=122 align=left valign=top class="csqft_pricettt" ><span class="csqft_price1"></span></td>' +
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top>Cartons to order:</td>' +
			'        <td width=122 align=left valign=top class="csqft_pricettt" ><input type=text name="cartons" value="" /></td>'+
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top>Zip Code:</td>' +
			'        <td width=122 align=left valign=top class="csqft_pricettt" ><input type=text name="zipcode" value="" /></td>' +
			'      </tr>' +
			'      <tr>' +
			'         <td width=233 align=left valign=top>Residential Delivery:</td>' +
			'         <td width=122 align=left valign=top><input type=checkbox name="residential" value="1" /></td>' +
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top><span style="color:#890016;">Total Amount:</span></td>' +
			'        <td width=122 align=left valign=top class="result_csqft" ></td>' +
			'      </tr>' +
			'      <tr>' +
			'        <td width=233 align=left valign=top>Shipping cost:</td>' +
			'        <td width=122 align=left valign=top class="csqft_pricettt" ><span class="shipping_cost"></span></td>' +
			'      </tr>' +
			'    </table>' +
			'    <input type=text name=sf value="" style="display:none;" />' +
			'  </div>' +
			'</div>'
		);
		
		// set total to 0.00
		$('#calculator_sqft .result_csqft').html('$0.00');

		
		// Calculate total on keyup
		$('#calculator_sqft input[name=sqft]').live('keyup', function() {
			var price = $('span[itemprop=price]').eq(0).text();
			var fixprice = +(price.charAt(0) === "$" ? price.substr(1) : price);
			var sqft = $(this).val();
			var result_cart = Math.ceil(sqft / carton);
			var actual_sqft = result_cart * carton;
			var adjusted_sqft = Math.round(result_cart * carton);
			var end_price = adjusted_sqft * fixprice;

			$('.csqft_price1').html(actual_sqft.toFixed(2));
			$('input[name=cartons]').val(result_cart);
			$('.result_csqft').html('$' + end_price.toFixed(2) + ' <span style="color: #f0b404; font: 14px arial;"></span>');
			$('input').eq(2).val(adjusted_sqft);
			updateShipping();
		});

		$('#calculator_sqft input[name=zipcode]').live('keyup', function() {
			updateShipping();
		});

		$('#calculator_sqft input[name=residential]').live('keyup', function() {
			updateShipping();
		});

	}
});



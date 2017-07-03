// JavaScript Document




$(function($){

	//bootstrap hover popup
	//$(document).ready(function(e) {
    		$('ul.nav li.dropdown, .export-button').hover(function() {
  		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		
		 var displayVal=$('.dropdown-menu').css('display');
		if(displayVal=='none')
			{
				//$('.dropdown-menu').parent('li').addClass('open');
				}
				
	}, function() {
 		 $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		   var displayVal=$('.dropdown-menu').css('display');
if(displayVal=='block')
			{	
				//$('.dropdown-menu').parent('li').removeClass('open');
				}
		});
		
		
	//});

//input type file buttion design
	$('input[type="file"]').before("<a class='filebrowse' href='javascript:void(0);'>Choose File</a>"); 
	$('input[type="file"]').after("<span id='filename'>No file chosen</span>"); 
		
		
	$('input[type="file"]').change(function(){
		
			$("#filename").text($(this).val())
		var filename=$("#filename").text();
		var result=filename.replace("C:\\fakepath\\", "");
		$("#filename").text(result)
		});
	
	
	
	
	//alert massage for external link
	$('a').bind('click', function(){
	var $str=$(this).attr('href');
	var $value=$str.indexOf("http");
	if($value==0)
	{
		var $con=confirm("This is external link, Are you sure you want to continue?");
		if($con==0)
		{return false;	}
	
	}
	});	
	
});
//bootstrap tooltip 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
		
	
	//Font-size increase and descrease
	 $(function () {
	
		
			/*$('h1').css("font-size","20px");
			$('h2').css("font-size","17px");
			$('h3').css("font-size","15px");
			$('h4').css("font-size","13px");
			$('h5').css("font-size","12px");
			$('h6').css("font-size","11px");
			$('body,a,input,textarea,select,td,th,p,span,div,ul,li').css("font-size","13px");*/
			var size = parseInt($('a,input,textarea,select,td,th,p,span,div,ul,li').css("font-size"));
			var h1size = parseInt($('h1').css("font-size"));
			var h2size = parseInt($('h2').css("font-size"));
			var h3size = parseInt($('h3').css("font-size"));
			var h4size = parseInt($('h4').css("font-size"));
			var h5size = parseInt($('h5').css("font-size"));
			var h6size = parseInt($('h6').css("font-size"));
		   //alert(size);
			
			 $("ul.font-zoom li.round-icons a").bind("click", function () {
			 //Manipulating font size of following HTML tag body,a,input,textarea,select,td,th,p,span,div
			
			 if ($(this).hasClass("a-plus")) {
				// alert(size);
                size = size + 2;
				//alert(size);
				 if (size >= 20) {
                    size = 20;
                }
                $('a,input,textarea,select,td,th,p,span,div,ul,li').css("font-size", size);
            } else if ($(this).hasClass("a-minus")) {
                size = size - 2;
                if (size <= 8) {
                    size = 8;
                }
                $('a,input,textarea,select,td,th,p,span,div,ul,li').css("font-size", size);
            } else if ($(this).hasClass("clsBold")) {
               // $('body').css("font-weight", "Bold");
            } else if ($(this).hasClass("clsItalic")) {
               // $('body').css("font-style", "italic");
            }
            else if ($(this).hasClass("a-normal")) {
                location.reload();
				//$('a,input,textarea,select,td,th,p,span,div,ul,li').css("font-size","");
                //$('body,a,input,textarea,select,td,th,p,span,div').css("font-style", "normal");
                //$('body,a,input,textarea,select,td,th,p,span,div').css("font-weight", "normal");
            }
			
			
			
			//Manipulating font size of H1 tag
			if($(this).hasClass('a-plus')){
				 h1size = h1size+2;
				if(h1size>=24){
				h1size = 24;
				}
			$('h1').css('font-size',h1size);
			}
			
			else if($(this).hasClass('a-minus')){
				 h1size = h1size-2;
				if(h1size<=14){
				 h1size = 14;
				}
				$('h1').css("font-size",h1size);
			}
			
			else if($(this).hasClass('a-normal')){
				 location.reload();
			}
		 	
			
			//Manipulating font size of H2 tag
			  if($(this).hasClass('a-plus')){
			  h2size = h2size + 2;
			
			  var h2maxsize=h2size+'4';
				  if (h2size >=h2maxsize){
				  	
					//h2size = 22;
				  }
				  var h2sizes=h2size+" "+"!important";
				
			  $('h2').css({"font-size":h2sizes});
			  }
		  else if ($(this).hasClass('a-minus')){
		  		 h2size = h2size - 2;
			  if (h2size <=12){
				  	h2size = 12;
				  } 
				   var h2sizes=h2size+" !important";
		  	$('h2').css("font-size",h2sizes);
		  }
		  
		  else if($(this).hasClass('a-normal')){
				
		  	 location.reload();
				}
			
			
			//Manipulating font size of H3 tag
			if($(this).hasClass('a-plus')){
				h3size	= 	h3size+2;
				if(h3size>=20){
					h3size	= 	20;
				}
				$('h3').css('font-size',h3size);
			}
			
			else if($(this).hasClass('a-minus')){
				h3size = h3size-2;
				if(h3size<=10){
					h3size = 10;	
				}
				$('h3').css('font-size',h3size);
			}
			
			else if($(this).hasClass('a-normal')){
				 location.reload();
			}
			
			//Manipulating font size of H4 tag
			if($(this).hasClass('a-plus')){
				h4size	= 	h4size+2;
				if(h4size>=16){
					h4size	= 	16;
				}
				$('h4').css('font-size',h4size);
			}
			
			else if($(this).hasClass('a-minus')){
				h4size = h4size-2;
				if(h4size<=10){
					h4size = 10;	
				}
				$('h4').css('font-size',h4size);
			}
			
			else if($(this).hasClass('a-normal')){
				 location.reload();
			}
			
			//Manipulating font size of H5 tag
			if($(this).hasClass('a-plus')){
				h5size	= 	h5size+2;
				if(h5size>=15){
					h5size	= 	15;
				}
				$('h5').css('font-size',h5size);
			}
			
			else if($(this).hasClass('a-minus')){
				h5size = h5size-2;
				if(h5size<=10){
					h5size = 10;	
				}
				$('h5').css('font-size',h5size);
			}
			
			else if($(this).hasClass('a-normal')){
				 location.reload();
			}
			
			//Manipulating font size of H6 tag
			if($(this).hasClass('a-plus')){
				h6size	= 	h6size+2;
				if(h6size>=14){	
					h5size	= 	14;
				}
				$('h6').css('font-size',h6size);
			}
			
			else if($(this).hasClass('a-minus')){
				h6size = h6size-2;
				if(h6size<=9){
					h5size = 9;	
				}
				$('h6').css('font-size',h6size);
			}
			
			else if($(this).hasClass('a-normal')){
				 location.reload();
			}
			
			
			 $('ul.font-zoom ,div.fund-cumulative,div.fund-cumulative span, ul.font-zoom li ,.skipContent ,ul.font-zoom li a, ul.font-zoom li select,.fundTransfer span, .fundTransfer h2,.fundTransferspan span, .fundTransferspan h2, .aadharbasepayment h2, .aadharbasepayment h2 span, .saving, .saving span, .department, .department span, .scheme, .scheme span,.navbar-brand span,.navbar-brand small').css("font-size","");
        });
	
    });

 

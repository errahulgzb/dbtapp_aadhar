jQuery(document).ready(function()
{
	jQuery("#ministryschemeadd").submit(function()
	{	
		
		if(!$("input[name=type_of_scheme]:checked").val()) 
        {
           	alert("Type of Scheme can't be empty");
			$("#type_of_scheme").focus();
			return false;
		}

		var fund_regex = /^[0-9]+$/;
		var fund = $("#fund_allocation").val();
		if(fund == '' || fund == null)
		{
			alert("Fund Allocated for the Scheme can't be empty");
			$("#fund_allocation").focus();
			return false;
		}
		else if(!fund.match(fund_regex))
		{
       	        	alert("Fund Allocated for the Scheme can take only numeric values");
			$("#fund_allocation").focus();
     		        return false;
		}
		else if(fund.length > 20)
        {
            alert("Fund Allocation for the Scheme is invalid");
			$("#fund_allocation").focus();
			return false;
		} 

		
		var agency_regex =/^[a-zA-Z0-9&-_() ]+$/; 
		var agency = $("#implemeting_agency").val();
		if(agency == '' || agency == null)
		{
			alert("Implemeting Agency can't be empty");
			$("#implemeting_agency").focus();
			return false;
		}
		else if(!agency.match(agency_regex))
   	        {
			alert("Implemeting Agency can take alphbets only \nSome special characters are used \nThese are not allowed");
			$("#implemeting_agency").focus();
		   	return false;
		}

		
		//var target_regex = /^[0-9 ]+$/;
	    var target_regex = /^[a-zA-Z0-9\-\_\,\.\' ]+$/;
		var target = $("#target_beneficiary").val();
		if(target == '' || target == null)
		{	
			alert("Target Beneficiaries can't be empty");
			$("#target_beneficiary").focus();
			return false;
		}
		else if(!target.match(target_regex))
   	        {
			alert("Target Beneficiaries can take alpha numeric values only");
			$("#target_beneficiary").focus();
		   	return false;
		}
		else if(target.length > 20)
        {
            alert("Target Beneficiaries is invalid");
			$("#target_beneficiary").focus();
			return false;
		} 

		var eligible_regex = /^[0-9]+$/;
		var eligible = $("#total_eligble_beneficiary").val();
		if(eligible == '' || eligible == null)
		{
			alert("Total Number of Eligble Beneficiary can't be empty");
			$("#total_eligble_beneficiary").focus();
			return false;
		}
		else if(!eligible.match(eligible_regex))
		{
       	        	alert("Total Number of Eligible Beneficaiary can take only numeric values");
			$("#total_eligble_beneficiary").focus();
     		        return false;
		}
		else if(eligible.length > 20)
        {
            alert("Total Number of Eligible Beneficaiary is invalid");
			$("#total_eligble_beneficiary").focus();
			return false;
		} 


		if(!$("input[name=digitized_beneficiary_status]:checked").val()) 
        {
            alert("Digitized Beneficiary Status in Place can't be empty");
			$("#digitized_beneficiary_status").focus();
			return false;
		}

		if(!$("input[name=mis_portal_status]:checked").val()) 
        {
          	alert("Mis Portal in Place for th Scheme can't be empty");
			$("#mis_portal_status").focus();
			return false;
		}

		var extent_regex = /^[0-9]{0,3}.?([0-9]{0,2})?$/;

		var aadhar = $("#aadhar_seeding_bd").val();
		if(aadhar == '' || aadhar == null)
		{
			alert("Aadhaar Seeding can't be empty");
			$("#aadhar_seeding_bd").focus();
			return false;
		}
		if(!aadhar.match(extent_regex)) 
		{
       	    alert("Aadhaar Seeding can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#aadhar_seeding_bd").focus();
     		return false;
		}
		if(aadhar.length >= 3)
		{
			if(aadhar > 100)
			{
				alert("Aadhaar Seeding cannot take value greater than 100");
				$("#aadhar_seeding_bd").focus();
	     		return false;
			}
		}
		
		var bankacc = $("#bank_account_bd").val();
		if(bankacc == '' || bankacc == null)
		{
			alert("Bank Accounts Number can't be empty");
			$("#bank_account_bd").focus();
			return false;
		}
		else if(!bankacc.match(extent_regex)) 
		{
       	    alert("Bank Accounts Number can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#bank_account_bd").focus();
     		return false;
		}
		if(bankacc.length >= 3)
		{
			if(bankacc > 100)
			{
				alert("Bank Accounts Number cannot take value greater than 100");
				$("#bank_account_bd").focus();
	     		return false;
			}
		}

		
		var mobile = $("#mobile_number_bd").val();
		if(mobile == '' || mobile == null)
		{
			alert("Mobile Number can't be empty");
			$("#mobile_number_bd").focus();
			return false;
		}
		else if(!mobile.match(extent_regex)) 
		{
       	    alert("Mobile Number can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#mobile_number_bd").focus();
     		return false;
		}
		if(mobile.length >= 3)
		{
			if(mobile > 100)
			{
				alert("Mobile Number cannot take value greater than 100");
				$("#mobile_number_bd").focus();
	     		return false;
			}
		}

		
		var linkage = $("#aadhar_linkage_account").val();
		if(linkage == '' || linkage == null)
		{
			alert("Aadhaar linkage with Bank Account can't be empty");
			$("#aadhar_linkage_account").focus();
			return false;
		}
		else if(!linkage.match(extent_regex)) 
		{
       	    alert("Aadhaar linkage with Bank Account can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#aadhar_linkage_account").focus();
     		return false;
		}
		if(linkage.length >= 3)
		{
			if(linkage > 100)
			{
				alert("Aadhaar linkage with Bank Account cannot take value greater than 100");
				$("#aadhar_linkage_account").focus();
	     		return false;
			}
		}
		var scheme_regex = /^[OR&|<>]?$/;
		var scheme = $("#scheme_description").val();
		if(scheme == '' || scheme == null)
		{
			alert("Scheme Description can't be empty");
			$("#scheme_description").focus();
			return false;
		}
		else if(scheme.match(scheme_regex)) 
		{
       	    alert("Scheme Description has some special characters. \nThese are not allowed");
       	    $("#scheme_description").focus();
			return false;
		}

		if(!$("input[name=type_of_benefit]:checked").val()) 
        {
           	alert("Type of Benefit can't be empty");
			$("#type_of_benefit").focus();
			return false;
		}
		var detailsofbenefit = $("#details_of_benefit").val();
		if(detailsofbenefit == '' || detailsofbenefit == null)
		{
			alert("Details of Benefit can't be empty");
			$("#details_of_benefit").focus();
			return false;
		}

		var processflowdescription = $("#process_flow_description").val();
		if(processflowdescription == '' || processflowdescription == null)
		{
			alert("Description of Process flow  can't be empty");
			$("#process_flow_description").focus();
			return false;
		}

		if(!$("input[name=pfms_payment]:checked").val()) 
        {
           	alert("Payment Linked to PFMS can't be empty");
			$("#pfms_payment").focus();
			return false;
		}

		if($('#mode_of_payment').val() == '0') 
        {
            alert("Mode of Payment can't be empty");
			return false;
		}
		
	var funddisbursedescription = $("#fund_disburse_description").val();
		if(funddisbursedescription == '' || funddisbursedescription == null)
		{
			alert("Description of Fund disbursement mechanism  can't be empty");
			$("#fund_disburse_description").focus();
			return false;
		}
		
	


		var c = confirm("Do you want to submit? Once submitted, form will not be modified later!!");
		if(!c){
			return false;
		}
		
	});
	
	
	
	
	
	
	
	
	
	
	
/********************************************************/	
	/*
//here is the option of save button
		jQuery("#elifinilityPhaseSave").click(function()
	{	
		

		var fund_regex = /^[0-9]+$/;
		var fund = $("#fund_allocation").val();
		if(!fund.match(fund_regex))
		{
       	    alert("Fund Allocated for the Scheme can take only numeric values");
			$("#fund_allocation").focus();
     		        return false;
		}
		else if(fund.length > 20)
        {
            alert("Fund Allocation for the Scheme is invalid");
			$("#fund_allocation").focus();
			return false;
		} 

		
		var agency_regex =/^[a-zA-Z0-9&-_() ]+$/; 
		var agency = $("#implemeting_agency").val();
		
		if(!agency.match(agency_regex))
   	        {
			alert("Implemeting Agency can take alphbets only \nSome special characters are used \nThese are not allowed");
			$("#implemeting_agency").focus();
		   	return false;
		}

		
		var target_regex = /^[0-9 ]+$/;
		var target = $("#target_beneficiary").val();
		
		if(!target.match(target_regex))
   	        {
			alert("Target Beneficiaries can take numeric values only");
			$("#target_beneficiary").focus();
		   	return false;
		}
		else if(target.length > 20)
        {
            alert("Target Beneficiaries is invalid");
			$("#target_beneficiary").focus();
			return false;
		} 

		var eligible_regex = /^[0-9]+$/;
		var eligible = $("#total_eligble_beneficiary").val();
		
		if(!eligible.match(eligible_regex))
		{
       	        	alert("Total Number of Eligible Beneficaiary can take only numeric values");
			$("#total_eligble_beneficiary").focus();
     		        return false;
		}
		else if(eligible.length > 20)
        {
            alert("Total Number of Eligible Beneficaiary is invalid");
			$("#total_eligble_beneficiary").focus();
			return false;
		} 



		var extent_regex = /^[0-9]{0,3}.?([0-9]{0,2})?$/;

		var aadhar = $("#aadhar_seeding_bd").val();
		
		if(!aadhar.match(extent_regex)) 
		{
       	    alert("Aadhaar Seeding can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#aadhar_seeding_bd").focus();
     		return false;
		}
		if(aadhar.length >= 3)
		{
			if(aadhar > 100)
			{
				alert("Aadhaar Seeding cannot take value greater than 100");
				$("#aadhar_seeding_bd").focus();
	     		return false;
			}
		}
		
		var bankacc = $("#bank_account_bd").val();
		
		if(!bankacc.match(extent_regex)) 
		{
       	    alert("Bank Accounts Number can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#bank_account_bd").focus();
     		return false;
		}
		if(bankacc.length >= 3)
		{
			if(bankacc > 100)
			{
				alert("Bank Accounts Number cannot take value greater than 100");
				$("#bank_account_bd").focus();
	     		return false;
			}
		}

		
		var mobile = $("#mobile_number_bd").val();
		if(!mobile.match(extent_regex)) 
		{
       	    alert("Mobile Number can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#mobile_number_bd").focus();
     		return false;
		}
		if(mobile.length >= 3)
		{
			if(mobile > 100)
			{
				alert("Mobile Number cannot take value greater than 100");
				$("#mobile_number_bd").focus();
	     		return false;
			}
		}

		
		var linkage = $("#aadhar_linkage_account").val();
		if(!linkage.match(extent_regex)) 
		{
       	    alert("Aadhaar linkage with Bank Account can take only numeric values \nPlease enter correct value. Format xx.xx");
			$("#aadhar_linkage_account").focus();
     		return false;
		}
		if(linkage.length >= 3)
		{
			if(linkage > 100)
			{
				alert("Aadhaar linkage with Bank Account cannot take value greater than 100");
				$("#aadhar_linkage_account").focus();
	     		return false;
			}
		}
		var scheme_regex = /^[OR&|<>]?$/;
		var scheme = $("#scheme_description").val();
		if(scheme.match(scheme_regex)) 
		{
       	    alert("Scheme Description has some special characters. \nThese are not allowed");
       	    $("#scheme_description").focus();
			return false;
		}

			

	});
//save button jquery here	


*/
	
	
});
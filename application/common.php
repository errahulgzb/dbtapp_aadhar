<?php
//below function is using for the strip xss queryies from the user input
function safexss($value = null){
	$badtags=array('<','>','&lt;','&gt;','javascript','script','document','onmouseover','onclick','location','#','(',')','alert','src','img');
		$filtered_string=str_replace($badtags,"",trim($value));
		return $filtered_string;
}
function strcrs($value){
	if($value != ""){
		return $value;
	}else{
		return "NA";
	}
}
/*
function currencyData($rs = null){
		setlocale(LC_ALL, 'nl_NL');

	if($rs == 0){
		return 0;
	}else{
		return $rs;
    setlocale(LC_MONETARY, 'en_IN');
    $amount = money_format('%!i', $rs);
    $amount=explode('.',$amount); //Comment this if you want amount value to be 1,00,000.00
    return $amount[0];
	}
}*/
function currencyData($num = null){
	$nums = explode(".",$num);
		if(count($nums)>2){
            return "0";
        }else{
        $num = $nums[0];
		$explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; 
        }
}
function errorRet($errorno = null){
	if($errorno == "100"){	
		return "Pi(basic) attributes of demographic data did not match.";
	}else if($errorno == "200"){
		return "Pa address attributes of demographic data did not match.";
	}else if($errorno == "300"){
		return "Biometric data did not match.";
	}else if($errorno == "310"){
			return "Duplicate fingers used.";
	}else if($errorno == "311"){
		return "Duplicate Irises used.";
	}else if($errorno == "312"){
		return "FMR and FIR cannot be used in same transaction.";
	}else if($errorno == "313"){
		return "Single FIR record contains more than one finger.";
	}else if($errorno == "314"){
		return "Number of FMR/FIR should not exceed 10.";
	}else if($errorno == "315"){
			return "Number of IIR should not exceed 2.";
	}else if($errorno == "316"){
			return "Number of FID should not exceed 1.";
	}else if($errorno == "330"){
			return "Biometrics locked by Aadhaar holder.";
	}else if($errorno == "400"){
		return "Invalid OTP value.";
	}else if($errorno == "402"){
		return "txn value did not match with txn value used in Request OTP API.";
	}else if($errorno == "500"){
		return "Invalid encryption of session key.";
	}else if($errorno == "501"){
		return "Invalid certificate identifier in ci attribute of Skey.";
	}else if($errorno == "502"){
		return "Invalid encryption of PID.";
	}else if($errorno == "503"){
		return "Invalid encryption of Hmac.";
	}else if($errorno == "504"){
		return "Session key re-initiation required due to expiry or key out of sync.";
	}else if($errorno == "505"){
		return "Synchronized Key usage not allowed for the AUA.";
	}else if($errorno == "510"){
		return "Invalid Auth XML format.";
	}else if($errorno == "511"){
		return "Invalid PID XML format.";
	}else if($errorno == "512"){
		return "Invalid Aadhaar holder consent in rc attribute of Auth.";
	}
	else if($errorno == "520"){
		return "Invalid tid value.";
	}
	else if($errorno == "521"){
		return "Invalid dc code under Meta tag.";
	}
	else if($errorno == "524"){
		return "Invalid mi code under Meta tag.";
	}
	else if($errorno == "527"){
		return "Invalid mc code under Meta tag.";
	}
	else if($errorno == "530"){
		return "Invalid authenticator code.";
	}
	else if($errorno == "540"){
		return "Invalid Auth XML version.";
	}
	else if($errorno == "541"){
		return "Invalid PID XML version.";
	}
	else if($errorno == "542"){
		return "AUA not authorized for ASA. This error will be returned if AUA and ASA do not have linking in the portal.";
	}
	else if($errorno == "543"){
		return "Sub-AUA not associated with AUA. This error will be returned if Sub-AUA specified in sa attribute is not added as Sub-AUA in portal.";
	}
	else if($errorno == "550"){
		return "Invalid Uses element attributes.";
	}
	else if($errorno == "551"){
		return "Invalid tid value.";
	}
	else if($errorno == "553"){
		return "Registered devices currently not supported. This feature is being implemented in a phased manner.";
	}
	else if($errorno == "554"){
		return "Public devices are not allowed to be used.";
	}
	else if($errorno == "555"){
		return "rdsId is invalid and not part of certification registry.";
	}
	else if($errorno == "556"){
		return "rdsVer is invalid and not part of certification registry.";
	}
	else if($errorno == "557"){
		return "dpId is invalid and not part of certification registry.";
	}	
	else if($errorno == "558"){
		return "Invalid dih.";
	}	
	else if($errorno == "559"){
			return "Device Certificat has expired.";
		}	
	else if($errorno == "560"){
			return "DP Master Certificate has expired.";
		}	
	else if($errorno == "561"){
			return "Request expired (Pid->ts value is older than N hours where N is a configured threshold in authentication server).";
		}	
	else if($errorno == "562"){
			return "Timestamp value is future time (value specified Pid->ts is ahead of authentication server time beyond acceptable threshold).";
		}	
	else if($errorno == "563"){
			return "Duplicate request (this error occurs when exactly same authentication request was re-sent by AUA).";
		}	
	else if($errorno == "564"){
			return "HMAC Validation failed.";
		}	
	else if($errorno == "565"){
			return "AUA license has expired.";
		}	
	else if($errorno == "566"){
			return "Invalid non-decryptable license key.";
		}	
	else if($errorno == "567"){
			return "Invalid input (this error occurs when unsupported characters were found in Indian language values, lname or lav).";
		}	
	else if($errorno == "557"){
			return "Unsupported Language.";
		}	
	else if($errorno == "568"){
			return "dpId is invalid and not part of certification registry.";
		}	
	else if($errorno == "569"){
			return "Digital signature verification failed (means that authentication request XML was modified after it was signed).";
		}	
	else if($errorno == "570"){
			return "Invalid key info in digital signature (this means that certificate used for signing the authentication request is not valid – it is either expired, or does not belong to the AUA or is not created by a well-known Certification Authority).";
		}
	else if($errorno == "571"){
			return "PIN requires reset.";
		}
	else if($errorno == "572"){
				return "Invalid biometric position.";
			}
	else if($errorno == "573"){
				return "Pi usage not allowed as per license.";
			}
	else if($errorno == "574"){
				return "Pa usage not allowed as per license.";
			}
	else if($errorno == "575"){
				return "Pfa usage not allowed as per license.";
			}
	else if($errorno == "576"){
				return "FMR usage not allowed as per license.";
			}
	else if($errorno == "577"){
				return "FIR usage not allowed as per license.";
			}
	else if($errorno == "578"){
				return "IIR usage not allowed as per license.";
			}
	else if($errorno == "579"){
				return "OTP usage not allowed as per license.";
			}
	else if($errorno == "580"){
				return "PIN usage not allowed as per license.";
			}
	else if($errorno == "581"){
				return "Fuzzy matching usage not allowed as per license.";
			}
	else if($errorno == "582"){
				return "Local language usage not allowed as per license.";
			}
	else if($errorno == "586"){
				return "FID usage not allowed as per license. This feature is being implemented in a phased manner.";
			}
	else if($errorno == "587"){
				return "Name space not allowed.";
			}
	else if($errorno == "588"){
				return "Registered device not allowed as per license.";
			}
	else if($errorno == "590"){
				return "Public device not allowed as per license.";
			}
	else if($errorno == "710"){
				return "Missing Pi data as specified in Uses.";
			}
	else if($errorno == "720"){
				return "Missing Pa data as specified in Uses.";
			}
	else if($errorno == "721"){
				return "Missing Pfa data as specified in Uses.";
			}
	else if($errorno == "730"){
				return "Missing PIN data as specified in Uses.";
			}
	else if($errorno == "740"){
				return "Missing OTP data as specified in Uses.";
			}
	else if($errorno == "800"){
				return "Invalid biometric data.";
			}
	else if($errorno == "810"){
				return "Missing biometric data as specified in Uses.";
			}
	else if($errorno == "811"){
				return "Missing biometric data in CIDR for the given Aadhaar number.";
			}
	else if($errorno == "812"){
				return "Aadhaar holder has not done “Best Finger Detection”. Application should initiate BFD to help Aadhaar holder identify their best fingers.";
			}
	else if($errorno == "820"){
					return "Missing or empty value for bt attribute in Uses element.";
				}
	else if($errorno == "821"){
					return "Invalid value in the bt attribute of Uses element.";
				}
	else if($errorno == "822"){
					return "Invalid value in the bs attribute of Bio element within Pid.";
				}
	else if($errorno == "901"){
					return "No authentication data found in the request (this corresponds to a scenario wherein none of the auth data – Demo, Pv, or Bios – is present).";
				}
	else if($errorno == "902"){
					return "Invalid dob value in the “Pi” element (this corresponds to a scenarios wherein dob attribute is not of the format YYYY or YYYY-MM-DD, or the age is not in valid range).";
				}
	else if($errorno == "910"){
					return "Invalid mv value in the Pi element.";
				}
	else if($errorno == "911"){
					return "Invalid mv value in the Pfa element.";
				}
	else if($errorno == "912"){
					return "Invalid ms value.";
				}
	
	else if($errorno == "913"){
						return "Both Pa and Pfa are present in the authentication request (Pa and Pfa are mutually exclusive).";
					}
	else if($errorno == "930" || $errorno == "931" || $errorno == "932" || $errorno == "933" || $errorno == "934" || $errorno == "935" || $errorno == "936" || $errorno == "937" || $errorno == "938" || $errorno == "939"){
						return "Technical error that are internal to authentication server.";
					}
	else if($errorno == "940"){
						return "Unauthorized ASA channel.";
					}
	else if($errorno == "941"){
						return "Unspecified ASA channel.";
					}
	else if($errorno == "950"){
						return "OTP store related technical error.";
					}
	else if($errorno == "951"){
						return "Biometric lock related technical error.";
					}
	else if($errorno == "980"){
						return "Unsupported option.";
					}
	else if($errorno == "995"){
						return "Aadhaar suspended by competent authority.";
					}
	else if($errorno == "996"){
						return "Aadhaar cancelled (Aadhaar is no in authenticable status).";
					}

	else if($errorno == "997"){
						return "Aadhaar suspended (Aadhaar is not in authenticatable status).";
					}

	else if($errorno == "998"){
						return "Invalid Aadhaar Number.";
					}
		else{
			return "Unknown error.";
			}


}
?>
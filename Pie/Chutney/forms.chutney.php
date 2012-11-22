<?php

/* Forms Chutney provides a generic form builder, it aims to be as complex or simple as you like.
As such there are a number of built in functions you can call to automatically generate
dropdown lists such as Titles, Calendar and TimeZone. But you are also free to generate you 
own dropdowns as you see fit. Be aware that all forms are outputed in a table format. We also 
include relevant class and id info to help you style the forms but we have decided not to use
any HTML5 form inputs as of yet because of weak browser support. */

/* To generate a form call the FormBuilder method and pass the variables $name, $action and $fields. 
$name should be the name/id you wish to give the form, action is where you want the form to post to
and fields is an array of information about the specific input types you wish to create. and it takes 
the following form. */

/* fieldname=>array('title','type',array('name','id','title','value','select'),'value','align') 
Most fields can be called using the first three parameters. e.g. forename=>array('Forename',''); =
<td>Forename</td><td><input type="text" name="forename" id="forename" value="" class="formtext" /></td>
The inner array represents the inner inputs you may have in a drop down menu or checkbox list. */

/* Fields Array

fieldname = VarChar
	title = VarChar
	type = (Title, Calendar, Time, Country, Gender, Timezone, Password, Hidden, Checkbox, Radio, Dropdown, DataList, Textarea, File, Submit)
		name = VarChar
		id = VarChar
		title = VarChar
		value = VarChar
		select = (SELECTED, CHECKED)
	value = VarChar
	align = (H,V) 
	SELECTED, CHECKED

*/

//namespace PorkPie\Chutney;

class Forms
{
	
	// Begins building your form.
	
	public function FormBuilder($name,$action,$fields,$enctype = false,$class = 'default')
	{
		
		$output = '<form id="'.$name.'" name="'.$name.'" method="post" action="'.$action.'" '.($enctype == true ?'enctype="multipart/form-data"':'').' class="'.$class.'">';
		
		// Loops through the fields array to generate the relevant inputs for the form.
		
		foreach ($fields as $key => $obj)
		{
			
			if ($obj[1] == 'Title')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="title">'.$obj[0].':</label>';
				$output .= $this->GenerateTitlesList($obj[3]);
				$output .= '</fieldset>';
			}
			elseif ($obj[1] == 'Calendar')
			{
				
				// This is a very simple callendar generator and requires the use of a validation tool to work affectively. 
				// We suggest you use the jQuery datepicker plugin with a normal text field if you can. 
				
				$output .= '<fieldset id="field'.$key.'"><label>'.$obj[0].':</label>';
				$output .= $this->CalendarFields();
				$output .= '</fieldset>';
			}
			elseif ($obj[1] == 'Time')
			{
				
			}
			elseif ($obj[1] == 'Country')
			{
				
				$output .= '<fieldset id="field'.$key.'"><label for="country">'.$obj[0].':</label>';
				$output .= $this->GetCountriesList($obj[3]);
				$output .= '</fieldset>';
				
			}
			elseif ($obj[1] == 'Gender')
			{
				
				$output .= '<fieldset id="field'.$key.'"><label for="gender">'.$obj[0].':</label>';
				$output .= $this->GetGendersList($obj[3]);
				$output .= '<fieldset>';
				
			}
			elseif ($obj[1] == 'Timezone')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="timezone">'.$obj[0].':</label>';
				$output .= $this->GetTimezoneList($obj[3]);
				$output .= '</fieldset>';
			}
			elseif ($obj[1] == 'Password')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$key.'">'.$obj[0].':</label><input type="password" name="'.$key.'" id="'.$key.'" value="'.$obj[3].'" /></fieldset>';
			}
			elseif ($obj[1] == 'Hidden')
			{
//				$output .= '<fieldset id="field'.$key.'"><input type="hidden" name="'.$key.'" id="'.$key.'" value="'.$obj[3].'" /></fieldset>';
                            $output .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.$obj[3].'" />';
			}
			elseif ($obj[1] == 'Checkbox')
			{
				
				$output .= '<fieldset id="field'.$key.'"><label>'.$obj[0].':</label>';
				
				$output .= '<ul>';
				
				foreach ($obj[2] as $var)
				{
						$output .= '<li><input type="checkbox" name="'.$var[0].'" id="'.$var[1].'" value="'.$var[3].'" '.$var[4].' /> <label for="'.$var[1].'">'.$var[2].'</label></li>';	
				}
				
				$output .= '</ul>';
				
				$output .= '</fieldset>';
				
			}
			elseif ($obj[1] == 'Radio')
			{
				
				$output .= '<fieldset id="field'.$key.'"><label>'.$obj[0].':</label>';
				
				$output .= '<ul>';
				
				foreach ($obj[2] as $var)
				{
					$output .= '<li><input type="radio" name="'.$key.'" id="'.$var[1].'" value="'.$var[3].'" '.$var[4].' /> <label for="'.$var[1].'">'.$var[2].'</label></li>';	
				}
				
				$output .= '</ul>';
				
				$output .= '</fieldset>';
				
			}
			//Updated
			elseif ($obj[1] == 'Dropdown')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$obj[0].'">'.$obj[0].':</label>';
				$output .= '<select name="'.$key.'" id="'.$obj[0].'">';
				
				foreach ($obj[2] as $var)
				{
					$output .= '<option value="'.$var[3].'" '.($var[3] == $obj[5] ? 'SELECTED' : '').'>'.$var[2].'</option>';	
				}
				
				$output .= '</select>';
				$output .= '</fieldset>';
			}
			//New Content
			elseif ($obj[1] == 'Datalist')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$obj[0].'">'.$obj[0].':</label>';
				$output .= '<select name="'.$key.'" id="'.$obj[0].'">';
				
				foreach ($obj[2] as $var)
				{
					$output .= '<option value="'.$var[0].'" '.($var[0] == $obj[5] ? 'SELECTED' : '').'>'.$var[1].'</option>';	
				}
				
				$output .= '</select>';
				$output .= '</fieldset>';
			}
                        elseif ($obj[1] == 'NamelessDatalist')
			{
				$output .= '<fieldset id="field'.$key.'">';
				$output .= '<select name="'.$key.'" id="'.$obj[0].'">';
				
				foreach ($obj[2] as $var)
				{
					$output .= '<option value="'.$var[0].'" '.($var[0] == $obj[5] ? 'SELECTED' : '').'>'.$var[1].'</option>';	
				}
				
				$output .= '</select>';
				$output .= '</fieldset>';
			}
			//New Content
			elseif ($obj[1] == 'File')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$key.'">'.$obj[0].':</label><input type="file" name="'.$key.'" id="'.$key.'" value="'.$obj[3].'" class="formtext" /></fieldset>';
			}
			elseif ($obj[1] == 'Textarea')
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$key.'">'.$obj[0].':</label>';
				$output .= '<textarea name="'.$key.'" id="'.$key.'">'.$obj[3].'</textarea>';
				$output .= '</fieldset>';
			}
			elseif ($obj[1] == 'Submit')
			{
				$output .= '<fieldset id="field'.$key.'"><input type="submit" name="'.$key.'" id="'.$key.'" value="'.$obj[0].'" class="formbutton" /></fieldset>';
			}
			//For Blog Tool Only
			elseif ($obj[1] == 'reCaptcha')
			{
				require_once('reCaptcha/recaptchalib.php');           
				$publickey = "6LcDb8USAAAAAO63v0oTi9zL38yhlN7_00KUTqFs"; // you got this from the signup page           
				$output .= '<fieldset id="field'.$key.'">';				
				$output .= recaptcha_get_html($publickey); 
				$output .= '</fieldset>';
			}
			else
			{
				$output .= '<fieldset id="field'.$key.'"><label for="'.$key.'">'.$obj[0].':</label><input type="text" name="'.$key.'" id="'.$key.'" value="'.$obj[3].'" class="formtext" /></fieldset>'; 	
			}
			
		}
		
		$output .= '</form>';
		
		return $output;
		
	}
	
	public function PayPalForm($amount,$currency,$description,$url)
	{
		
		$output = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">'; 
		
		// Identify your business so that you can collect the payments.
		$output .= '<input type="hidden" name="business" value="REH896CP9BA7N">';

		// Specify a Buy Now button. 
		$output .= '<input type="hidden" name="cmd" value="_xclick">'; 

		// Specify details about the item that buyers will purchase.  
		$output .= '<input type="hidden" name="item_name" value="'.$description.'">'; 
		$output .= '<input type="hidden" name="amount" value="'.$amount.'">';
		$output .= '<input type="hidden" name="currency_code" value="'.$currency.'">';
		
		// Return Url
		$output .= '<input type="hidden" name="return" value="'.$url.'">'; 

		// Display the payment button. 
		$output .= '<input type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">';
		$output .= '<img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" >'; 
		$output .= '</form>';
		
		return $output;
			
	}
	
	// Generates the options for a generic titles drop down.
	
	protected function GenerateTitlesList($selected = null)
	{
		
		$titles = $this->Titles();
		
		$ttlsoptns = '<select id="title" name="title">';
		
		foreach ($titles as $tit)
		{
			
			$ttlsoptns .= '<option value="'.$tit.'" ';
			
			if ($tit == $selected)
			{
				$ttlsoptns .= 'SELECTED';
			}
			
			$ttlsoptns .='>';
			$ttlsoptns .= $tit;
			$ttlsoptns .= '</option>';	
			
		}
		
		$ttlsoptns .= '</select>';
		
		return $ttlsoptns;
		
	}
	
	// An array of generic titles
	
	protected function Titles()
	{
		
		$titles = array("","Mr","Mrs","Miss","Ms","Dr");	
		
		return $titles;
		
	}
	
	// Generates the options for a generic countries drop down.
	
	protected function GetCountriesList($selected)
	{
		
		$countries = $this->Countries();
		
		$cntryoptns = '<select name="country" id="country">';
		
		foreach ($countries as $cou)
		{
			
			$cntryoptns .= '<option value="'.$cou.'" ';
			
			if ($cou == $selected)
			{
				$cntryoptns .= 'SELECTED';
			}
			
			$cntryoptns .= '>';
			$cntryoptns .= $cou;
			$cntryoptns .= '</option>';	
			
		}
		
		$cntryoptns .= '</select>';
		
		return $cntryoptns;
		
	}
	
	// An array of generic countries
	
	protected function Countries()
	{
		
		$countries = array(
			"",
			"Afghanistan",
			"Albania",
			"Algeria",
			"Andorra",
			"Angola",
			"Antigua and Barbuda",
			"Argentina",
			"Armenia",
			"Australia",
			"Austria",
			"Azerbaijan",
			"Bahamas",
			"Bahrain",
			"Bangladesh",
			"Barbados",
			"Belarus",
			"Belgium",
			"Belize",
			"Benin",
			"Bhutan",
			"Bolivia",
			"Bosnia and Herzegovina",
			"Botswana",
			"Brazil",
			"Brunei",
			"Bulgaria",
			"Burkina Faso",
			"Burundi",
			"Cambodia",
			"Cameroon",
			"Canada",
			"Cape Verde",
			"Central African Republic",
			"Chad",
			"Chile",
			"China",
			"Colombi",
			"Comoros",
			"Congo (Brazzaville)",
			"Congo",
			"Costa Rica",
			"Cote d'Ivoire",
			"Croatia",
			"Cuba",
			"Cyprus",
			"Czech Republic",
			"Denmark",
			"Djibouti",
			"Dominica",
			"Dominican Republic",
			"East Timor (Timor Timur)",
			"Ecuador",
			"Egypt",
			"El Salvador",
			"Equatorial Guinea",
			"Eritrea",
			"Estonia",
			"Ethiopia",
			"Fiji",
			"Finland",
			"France",
			"Gabon",
			"Gambia, The",
			"Georgia",
			"Germany",
			"Ghana",
			"Greece",
			"Grenada",
			"Guatemala",
			"Guinea",
			"Guinea-Bissau",
			"Guyana",
			"Haiti",
			"Honduras",
			"Hungary",
			"Iceland",
			"India",
			"Indonesia",
			"Iran",
			"Iraq",
			"Ireland",
			"Israel",
			"Italy",
			"Jamaica",
			"Japan",
			"Jordan",
			"Kazakhstan",
			"Kenya",
			"Kiribati",
			"Korea, North",
			"Korea, South",
			"Kuwait",
			"Kyrgyzstan",
			"Laos",
			"Latvia",
			"Lebanon",
			"Lesotho",
			"Liberia",
			"Libya",
			"Liechtenstein",
			"Lithuania",
			"Luxembourg",
			"Macedonia",
			"Madagascar",
			"Malawi",
			"Malaysia",
			"Maldives",
			"Mali",
			"Malta",
			"Marshall Islands",
			"Mauritania",
			"Mauritius",
			"Mexico",
			"Micronesia",
			"Moldova",
			"Monaco",
			"Mongolia",
			"Morocco",
			"Mozambique",
			"Myanmar",
			"Namibia",
			"Nauru",
			"Nepa",
			"Netherlands",
			"New Zealand",
			"Nicaragua",
			"Niger",
			"Nigeria",
			"Norway",
			"Oman",
			"Pakistan",
			"Palau",
			"Panama",
			"Papua New Guinea",
			"Paraguay",
			"Peru",
			"Philippines",
			"Poland",
			"Portugal",
			"Qatar",
			"Romania",
			"Russia",
			"Rwanda",
			"Saint Kitts and Nevis",
			"Saint Lucia",
			"Saint Vincent",
			"Samoa",
			"San Marino",
			"Sao Tome and Principe",
			"Saudi Arabia",
			"Senegal",
			"Serbia and Montenegro",
			"Seychelles",
			"Sierra Leone",
			"Singapore",
			"Slovakia",
			"Slovenia",
			"Solomon Islands",
			"Somalia",
			"South Africa",
			"Spain",
			"Sri Lanka",
			"Sudan",
			"Suriname",
			"Swaziland",
			"Sweden",
			"Switzerland",
			"Syria",
			"Taiwan",
			"Tajikistan",
			"Tanzania",
			"Thailand",
			"Togo",
			"Tonga",
			"Trinidad and Tobago",
			"Tunisia",
			"Turkey",
			"Turkmenistan",
			"Tuvalu",
			"Uganda",
			"Ukraine",
			"United Arab Emirates",
			"United Kingdom",
			"United States",
			"Uruguay",
			"Uzbekistan",
			"Vanuatu",
			"Vatican City",
			"Venezuela",
			"Vietnam",
			"Yemen",
			"Zambia",
			"Zimbabwe"
			);
		
		return $countries;
		
	}
	
	// Generates the options for a generic genders drop down.
	
	protected function GetGendersList($selected)
	{
		
		$genders = $this->Genders();
		
		$gndrsoptns = '<select name="gender" id="gender">';
		
		foreach ($genders as $gen)
		{
			
			$gndrsoptns .= '<option value="'.$gen.'">';
			
			if ($gen == $selected)
			{
				$gndrsoptns .= 'SELECTED';
			}
			
			$gndrsoptns .= $gen;
			$gndrsoptns .= '</option>';	
			
		}
		
		$gndrsoptns .= '</select>';
		
		return $gndrsoptns;
		
	}
	
	protected function Genders()
	{
	
		$genders = array(
			"",
			"Male",
			"Female"
			);	
			
		return $genders;
		
	}
	
	// Builds the options for a generic drop down callendar 
	
	protected function CalendarFields()
	{
	
		$d = 1;
		$m = 1;
		$y = 1900;
		$year = date('Y',time()) + 5;
	
		$calendar .= '<select id="days" name="days"><option value="0">DD</option>';	
		
		while ($d <= 31)
		{
			$calendar .= '<option value="'.$d.'">'.$d.'</option>';	
			$d++;
		}
		
		$calendar .= '</select> ';
		$calendar .= '<select id="months" name="months"><option value="0">MM</option>';	
		
		while ($m <= 12)
		{
			$calendar .= '<option value="'.$m.'">'.$m.'</option>';	
			$m++;
		}
		
		$calendar .= '</select> ';
		$calendar .= '<select id="years" name="years"><option value="0">YYYY</option>';	
		
		while ($y <= $year)
		{
			$calendar .= '<option value="'.$y.'">'.$y.'</option>';	
			$y++;
		}
		
		$calendar .= '</select>';
		
		return $calendar;
		
	}
	
	// Generates the options for a generic timezone drop down.
	// This should be used in conjunction with the Date Time Pork.
	
	public function GetTimezoneList($selected)
	{
		
		$timezones = $this->TimeZonesList();
		
		$output .= '<select id="timezone" name="timezone">';
		
		foreach ($timezones as $key => $obj)
		{
			
			$output .= '<option value="';
			$output .= $key;
			$output .= '" ';
			if ($key == $selected)
			{
				$output .= 'SELECTED';
			}
			$output .= '>';
			$output .= $obj;
			$output .= '</option>';
			
		}		
		
		$output .= '</select>';
		
		return $output;
		
	}
	
	// An array of generic timezones
	
	public function TimeZonesList()
	{
		
		$zonelist = array(''=>'',
			'Kwajalein' => '(GMT-12:00) International Date Line West',
			'Pacific/Midway' => '(GMT-11:00) Midway Island',
			'Pacific/Samoa' => '(GMT-11:00) Samoa',
			'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
			'America/Anchorage' => '(GMT-09:00) Alaska',
			'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
			'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
			'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
			'America/Chihuahua' => '(GMT-07:00) Chihuahua',
			'America/Mazatlan' => '(GMT-07:00) Mazatlan',
			'America/Phoenix' => '(GMT-07:00) Arizona',
			'America/Regina' => '(GMT-06:00) Saskatchewan',
			'America/Tegucigalpa' => '(GMT-06:00) Central America',
			'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
			'America/Mexico_City' => '(GMT-06:00) Mexico City',
			'America/Monterrey' => '(GMT-06:00) Monterrey',
			'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
			'America/Bogota' => '(GMT-05:00) Bogota',
			'America/Lima' => '(GMT-05:00) Lima',
			'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
			'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
			'America/Caracas' => '(GMT-04:30) Caracas',
			'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
			'America/Manaus' => '(GMT-04:00) Manaus',
			'America/Santiago' => '(GMT-04:00) Santiago',
			'America/La_Paz' => '(GMT-04:00) La Paz',
			'America/St_Johns' => '(GMT-03:30) Newfoundland',
			'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
			'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
			'America/Godthab' => '(GMT-03:00) Greenland',
			'America/Montevideo' => '(GMT-03:00) Montevideo',
			'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
			'Atlantic/Azores' => '(GMT-01:00) Azores',
			'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
			'Europe/Dublin' => '(GMT) Dublin',
			'Europe/Lisbon' => '(GMT) Lisbon',
			'Europe/London' => '(GMT) London',
			'Africa/Monrovia' => '(GMT) Monrovia',
			'Atlantic/Reykjavik' => '(GMT) Reykjavik',
			'Africa/Casablanca' => '(GMT) Casablanca',
			'Europe/Belgrade' => '(GMT+01:00) Belgrade',
			'Europe/Bratislava' => '(GMT+01:00) Bratislava',
			'Europe/Budapest' => '(GMT+01:00) Budapest',
			'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
			'Europe/Prague' => '(GMT+01:00) Prague',
			'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
			'Europe/Skopje' => '(GMT+01:00) Skopje',
			'Europe/Warsaw' => '(GMT+01:00) Warsaw',
			'Europe/Zagreb' => '(GMT+01:00) Zagreb',
			'Europe/Brussels' => '(GMT+01:00) Brussels',
			'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
			'Europe/Madrid' => '(GMT+01:00) Madrid',
			'Europe/Paris' => '(GMT+01:00) Paris',
			'Africa/Algiers' => '(GMT+01:00) West Central Africa',
			'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
			'Europe/Berlin' => '(GMT+01:00) Berlin',
			'Europe/Rome' => '(GMT+01:00) Rome',
			'Europe/Stockholm' => '(GMT+01:00) Stockholm',
			'Europe/Vienna' => '(GMT+01:00) Vienna',
			'Europe/Minsk' => '(GMT+02:00) Minsk',
			'Africa/Cairo' => '(GMT+02:00) Cairo',
			'Europe/Helsinki' => '(GMT+02:00) Helsinki',
			'Europe/Riga' => '(GMT+02:00) Riga',
			'Europe/Sofia' => '(GMT+02:00) Sofia',
			'Europe/Tallinn' => '(GMT+02:00) Tallinn',
			'Europe/Vilnius' => '(GMT+02:00) Vilnius',
			'Europe/Athens' => '(GMT+02:00) Athens',
			'Europe/Bucharest' => '(GMT+02:00) Bucharest',
			'Europe/Istanbul' => '(GMT+02:00) Istanbul',
			'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
			'Asia/Amman' => '(GMT+02:00) Amman',
			'Asia/Beirut' => '(GMT+02:00) Beirut',
			'Africa/Windhoek' => '(GMT+02:00) Windhoek',
			'Africa/Harare' => '(GMT+02:00) Harare',
			'Asia/Kuwait' => '(GMT+03:00) Kuwait',
			'Asia/Riyadh' => '(GMT+03:00) Riyadh',
			'Asia/Baghdad' => '(GMT+03:00) Baghdad',
			'Africa/Nairobi' => '(GMT+03:00) Nairobi',
			'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
			'Europe/Moscow' => '(GMT+03:00) Moscow',
			'Europe/Volgograd' => '(GMT+03:00) Volgograd',
			'Asia/Tehran' => '(GMT+03:30) Tehran',
			'Asia/Muscat' => '(GMT+04:00) Muscat',
			'Asia/Baku' => '(GMT+04:00) Baku',
			'Asia/Yerevan' => '(GMT+04:00) Yerevan',
			'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
			'Asia/Karachi' => '(GMT+05:00) Karachi',
			'Asia/Tashkent' => '(GMT+05:00) Tashkent',
			'Asia/Kolkata' => '(GMT+05:30) Calcutta',
			'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
			'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
			'Asia/Dhaka' => '(GMT+06:00) Dhaka',
			'Asia/Almaty' => '(GMT+06:00) Almaty',
			'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
			'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
			'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
			'Asia/Bangkok' => '(GMT+07:00) Bangkok',
			'Asia/Jakarta' => '(GMT+07:00) Jakarta',
			'Asia/Brunei' => '(GMT+08:00) Beijing',
			'Asia/Chongqing' => '(GMT+08:00) Chongqing',
			'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
			'Asia/Urumqi' => '(GMT+08:00) Urumqi',
			'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
			'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
			'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
			'Asia/Singapore' => '(GMT+08:00) Singapore',
			'Asia/Taipei' => '(GMT+08:00) Taipei',
			'Australia/Perth' => '(GMT+08:00) Perth',
			'Asia/Seoul' => '(GMT+09:00) Seoul',
			'Asia/Tokyo' => '(GMT+09:00) Tokyo',
			'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
			'Australia/Darwin' => '(GMT+09:30) Darwin',
			'Australia/Adelaide' => '(GMT+09:30) Adelaide',
			'Australia/Canberra' => '(GMT+10:00) Canberra',
			'Australia/Melbourne' => '(GMT+10:00) Melbourne',
			'Australia/Sydney' => '(GMT+10:00) Sydney',
			'Australia/Brisbane' => '(GMT+10:00) Brisbane',
			'Australia/Hobart' => '(GMT+10:00) Hobart',
			'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
			'Pacific/Guam' => '(GMT+10:00) Guam',
			'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
			'Asia/Magadan' => '(GMT+11:00) Magadan',
			'Pacific/Fiji' => '(GMT+12:00) Fiji',
			'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
			'Pacific/Auckland' => '(GMT+12:00) Auckland',
			'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa');
		
		return $zonelist;
		
	}
	
}

?>
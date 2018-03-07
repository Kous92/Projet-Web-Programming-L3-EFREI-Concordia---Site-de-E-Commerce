<?php
/*
FNG Credit Card Validator v1.1
Copyright © 2009 Fake Name Generator <http://www.fakenamegenerator.com/>
FNG Credit Card Validator v1.1 by the Fake Name Generator is licensed to you
under a Creative Commons Attribution-Share Alike 3.0 United States License.
For full license details, please visit:
http://www.fakenamegenerator.com/license.php
*/
class fngccvalidator
{
	/**
	 * Validate credit card number and return card type.
	 * Optionally you can validate if it is a specific type.
	 *
	 * @param string $ccnumber
	 * @param string $cardtype
	 * @param string $allowTest
	 * @return mixed
	 */

	public function CreditCard($ccnumber, $cardtype = '', $allowTest = false)
	{
		// Check for test cc number
		if ($allowTest == false && $ccnumber == '4111111111111111')
		{
			return false;
		}
		
		$ccnumber = preg_replace('/[^0-9]/','',$ccnumber); // Strip non-numeric characters
		
		// Checking credit card type (MasterCard, Visa or American Express)
		$creditcard = array('visa' => "/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/", 
			                'mastercard' => "/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
			                'american express' => "/^3[4,7]\d{13}$/");
		
		if (empty($cardtype))
		{
			$match = false;

			foreach ($creditcard as $cardtype => $pattern)
			{
				if (preg_match($pattern,$ccnumber) == 1)
				{
					$match = true;
					break;
				}
			}

			if (!$match)
			{
				return false;
			}
		}
		else if (strcasecmp($cardtype, $creditcard[strtolower(trim($cardtype))]) != 0)
		{
			return false;
		}		
		
		$return = array();
		$return[0] = $this->LuhnCheck($ccnumber); // Checking validity of card with Luhn's algorithm

		if ($return[0] == 1)
		{
			$return[0] = "Valid";
		}
		$return[1] = $ccnumber;
		$return[2] = $cardtype;

		return $return;
	}
	
	/**
	 * Do a modulus 10 (Luhn algorithm) check
	 *
	 * @param string $ccnum
	 * @return boolean
	 */
	public function LuhnCheck($ccnum)
	{
		$checksum = 0;

		for ($i = (2 - (strlen($ccnum) % 2)); $i <= strlen($ccnum); $i += 2)
		{
			$checksum += (int)($ccnum{$i-1});
		}
		
		// Analyze odd digits in even length strings or even digits in odd length strings.
		for ($i = (strlen($ccnum)% 2) + 1; $i < strlen($ccnum); $i += 2)
		{
			$digit = (int)($ccnum{$i-1}) * 2;

			if ($digit < 10)
			{
				$checksum += $digit;
			}
			else
			{
				$checksum += ($digit-9);
			}
		}

		// Valid credit card number
		if (($checksum % 10) == 0)
		{
			return true; 
		}
		else
		{
			return false;
		}
	}
	
}
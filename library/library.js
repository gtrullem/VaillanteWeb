// ----------------------------------------------------------------------------
// Tool Function
// ----------------------------------------------------------------------------
function next(field, nextfield, limit)
{
	if(field.value.length == limit)
		document.getElementById(nextfield).focus();
}

function strtr(str, from, to)
{
	var fr = '', i = 0, j = 0, lenStr = 0, lenFrom = 0, tmpStrictForIn = false, fromTypeStr = '', toTypeStr = '', istr = '';
	var tmpFrom = [];
	var tmpTo = [];
	var ret = '';
	var match = false;
 
	// Received replace_pairs?
	// Convert to normal from->to chars
	if (typeof from === 'object') {
		tmpStrictForIn = this.ini_set('phpjs.strictForIn', false); // Not thread-safe; temporarily set to true
		from = this.krsort(from);
		this.ini_set('phpjs.strictForIn', tmpStrictForIn);
 
		for (fr in from) {
			if (from.hasOwnProperty(fr)) {
				tmpFrom.push(fr);
				tmpTo.push(from[fr]);
			}
		}
 
		from = tmpFrom;
		to = tmpTo;
	}
	
	// Walk through subject and replace chars when needed
	lenStr  = str.length;
	lenFrom = from.length;
	fromTypeStr = typeof from === 'string';
	toTypeStr = typeof to === 'string';
 
	for (i = 0; i < lenStr; i++) {
		match = false;
		if (fromTypeStr) {
			istr = str.charAt(i);
			for (j = 0; j < lenFrom; j++) {
				if (istr == from.charAt(j)) {
					match = true;
					break;
				}
			}
		} else {
			for (j = 0; j < lenFrom; j++) {
				if (str.substr(i, from[j].length) == from[j]) {
					match = true;
					// Fast forward
					i = (i + from[j].length)-1;
					break;
				}
			}
		}
		if (match) {
			ret += toTypeStr ? to.charAt(j) : to[j];
		} else {
			ret += str.charAt(i);
		}
	}
 
	return ret;
}

function trim(str)
{
	var	str = str.replace(/^\s\s*/, ''), ws = /\s/, i = str.length;
	while (ws.test(str.charAt(--i)));
	return str.slice(0, i + 1);
}

function toTitleCase(str)
{
	return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

String.prototype.toTitleCase = function () {
	return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

// ----------------------------------------------------------------------------
// Date Function
// ----------------------------------------------------------------------------

function checkDate(theDate)
{
	var reg = new RegExp("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$");
	if(!reg.test(theDate))
		return false;
	
	var splittedDate = (theDate).split("/");
	var aDate = new Date(splittedDate[2], (splittedDate[1]-1), splittedDate[0]); // eval(splittedDate[1]-1)
	
	if((aDate.getDate() != splittedDate[0]) || (aDate.getMonth() != (splittedDate[1]-1) || (aDate.getFullYear() != splittedDate[2])))
		return false;
	
	var d = new Date();
	var curr_year = d.getFullYear();
	if(splittedDate[2] < (curr_year - 65))
		return false;

	return true;
}

function checkDateUs(theDate)
{
	var reg = new RegExp("^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$");
	if(!reg.test(theDate))
		return false;
	
	var splittedDate = (theDate).split("-");
	var aDate = new Date(splittedDate[0], (splittedDate[1]-1), splittedDate[2]); // eval(splittedDate[1]-1)
	
	if((aDate.getDate() != splittedDate[2]) || (aDate.getMonth() != (splittedDate[1]-1) || (aDate.getFullYear() != splittedDate[0])))
		return false;
	
	var d = new Date();
	var curr_year = d.getFullYear();
	if(splittedDate[0] < (curr_year - 65))
		return false;

	return true;
}

function compareDate(date1, date2)
{
	var d1 = date1.split("/");
	var d2 = date2.split("/");
	d1 = new Date(d1[2], (d1[1]-1), d1[0]);
	d2 = new Date(d2[2], (d2[1]-1), d2[0]);
	var diff = d1.getTime() - d2.getTime();
	return diff;
}

function compareDateUs(date1, date2)
{
	var d1 = date1.split("-");
	var d2 = date2.split("-");
	d1 = new Date(d1[0], (d1[1]-1), d1[2]);
	d2 = new Date(d2[0], (d2[1]-1), d2[2]);
	var diff = d1.getTime() - d2.getTime();
	return diff;
}

// ----------------------------------------------------------------------------
// Check Function
// ----------------------------------------------------------------------------
function checkEmail(email)
{
	// var reg = new RegExp("^([a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$");
	// [a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b
//	var reg = new RegExp("^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$");
// ^[a-zA-Z0-9\-_]+[a-zA-Z0-9\.\-_]*@[a-zA-Z0-9\-_]+\.[a-zA-Z\.\-_]{1,}[a-zA-Z\-_]+
	var reg = new RegExp("^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$");
	if (!reg.test(email))	{
		return false;
	}
	return true;
}

function checkGsm(gsm)
{
	var reg = new RegExp("^04[6-9]{1}[0-9]{7}$");
	if(!reg.test(gsm)) {
		alert('Numéro de GSM incorrect.');
		return false;
	}
	return true;
}

function checkPhone(phone)
{
	var reg = new RegExp("^0[0-9]{8}$");
	if(!reg.test(phone)) {
		alert('Numéro de téléphone incorrect.');
		return false;
	}
	return true;
}

function checkPostal(postal)
{
	var reg = new RegExp("^[1-9]{1}[0-9]{3}$");
	if(!reg.test(postal)) {
		alert('Veuillez indiquer un code postal correct.');
		return false;
	}
	return true;
}

function checkNISS(niss, gender)
{
	var reg = new RegExp("^[0-9]{11}$");
	if(!reg.test(niss)) {
		alert('Le format du NISS est incorrect.');
		return false;
	}
		
	if((97 - (Math.round(niss.substring(0,9))%97)) != (niss%100)) {
		if((97 - (2000000000+(Math.round(niss.substring(0,9)))%97)) != (niss%100)) {
			alert('Veuillez vérifiez le numéro NISS. Il ne semble pas correct.');
			return false;
		}
	}

	if((((Math.floor(niss.substring(8,9)) % 2 ) == 0) && (gender == "M")) || (((Math.floor(niss.substring(8,9)) % 2 ) == 1) && (gender == "F"))) {
		alert('Le Numéro d\'identification national ne correspond pas avec le genre choisi.');
		return false;
	}

	return true;
}

function checkIBAN(iban)
{
	const ibanValidationModulo = 97;

	iban = iban.toUpperCase();
	iban = iban.replace(new RegExp(" ", "g"), "");

	var reg = new RegExp("^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$");
	if(!reg.test(iban)) {
		alert('Format du Code IBAN incorrect.')
		return false;
	}

	// Transfert les quatre premiers caractères en fin de chaine.
	modifiedIban = iban.substring(4, iban.length)+iban.substr(0,4);

	// Convertion des caractères alphabétiques en valeur numérique
	numericIbanString = "";
	for (var index = 0; index < modifiedIban.length; index ++) {
		currentChar = modifiedIban.charAt(index);
		currentCharCode = modifiedIban.charCodeAt(index);

		// si le caractère est un digit => recopie
		if ((currentCharCode > 47) && (currentCharCode <  58))
			numericIbanString = numericIbanString + currentChar;
		// si le caractère est une lettre => convertion
		else if ((currentCharCode > 64) && (currentCharCode < 91)) {
			value = currentCharCode-65+10;
			numericIbanString = numericIbanString + value;
		}
		// sinon, le code iban est invalide (caractère invalide).
		else {
			alert('Le code IBAN est invalide (caractère invalide).');
			return false;
		}
	}

	var previousModulo = 0;
	for (var index = 0; index < numericIbanString.length; index += 5) {
		subpart = previousModulo+""+numericIbanString.substr(index, 5);
		previousModulo = subpart % ibanValidationModulo;
	}

	if(previousModulo != 1) {
		alert('Le code IBAN est invalide.');
		return false;
	}

	return true;
}

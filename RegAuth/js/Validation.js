
function Validation(elem){
	var type = elem.type;
	var value = elem.value;
	var fieldName;
	var reg;
	var minLength;
	var maxLength ;

	switch(type){
		case 'text': 
					reg  = /^[a-zA-Z0-9_]{3,12}$/;
					minLength = 3;
					maxLength = 12;
					fieldName = "name";
					break;
		case 'password':
					reg = /^[a-zA-Z0-9]{6,15}$/;
					minLength = 6;
					maxLength = 15;
					fieldName = "password";
					break;
		case 'email':
					//reg = /[a-zA-Z0-9]{2,10}@[a-zA-Z0-9]{3,10}.(com|net|ru)/;  
					//browser also checks field of this type,so this block is opionall
					//minLength = 9;
					//maxLength = 25;
					fieldName = "email";
					return true;
					break;
	}
	if(value.length < minLength){
		alert("Sorry,you can't send empty fields.Please check " + fieldName + " field");
		document.getElementById("submit_button").disabled = true;
		return false;
	}
	if(value.Length > maxLength){
		alert("Sorry,this is very long " + fieldName);
		document.getElementById("submit_button").disabled = true;
		return false;
	}
	if(!reg.test(value)){
		alert("Sorry,Something wrong with your " + fieldName + " field");
		document.getElementById("submit_button").disabled = true;
		return false;
	} else {
		document.getElementById("submit_button").disabled = false;
		return true;
	}
}

function FinalCheck(){
	var input_array = document.getElementsByTagName("input");
	var validForm = true;
	for(var i=0;i<input_array.length;i++){
		if(!(input_array[i].type == "submit")){
			if(!Validation(input_array[i])) validForm = false; 
		}
	}
	return validForm;
}
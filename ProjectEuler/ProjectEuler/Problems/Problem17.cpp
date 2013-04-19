#include "Problem17.h"


Problem17::Problem17(void)
{
	setName("Problem 17");
	target = 1000;
}


Problem17::~Problem17(void)
{
}

int Problem17::getNumCharsDigit(int digit){
	int result;
	switch(digit){
		case 10:
		case 6:
		case 2:
		case 1:
			result=3;
			break;
		case 9:
		case 5:
		case 4:
			result = 4;
			break;
		case 60:
		case 50:
		case 40:
		case 8:
		case 7:
		case 3:
			result = 5;
			break;
		case 90:
		case 80:
		case 30:
		case 20:
		case 12:
		case 11:
			result = 6;
			break;
		case 70:
		case 16:
		case 15:
			result = 7;
			break;
		case 19:
		case 18:
		case 14:
		case 13:
			result = 8;
			break;
		case 17:
			result = 9;
			break;
		default:
			result = 0;
			break;
	}
	return result;
}

int Problem17::getNumChars(int i){
	int result = 0;
	if(i > 9){
		int k = i % 100;
		if(k > 0){
			if(k <= 20){
				result += getNumCharsDigit(k);
			}else{
				int j = k % 10;
				result += getNumCharsDigit(j);
				k -= j;
				result += getNumCharsDigit(k);
			}
		}
		if(i > 99){
			k = i / 100;
			if(k == 10){
				result += getNumCharsDigit(k / 10);
				result += 8; //thousand
			}else{
				result += getNumCharsDigit(k);
				result += 7; //hundred;
				if(i % 100 != 0){
					result += 3; //And.
				}
			}
		}

	}else{
		result += getNumCharsDigit(i);
	}
	return result;
}

__int64 Problem17::run(void){
	__int64 result = 0;
	for(int i =1;i<=target;i++){
		result += getNumChars(i);
	}
	return result;
}

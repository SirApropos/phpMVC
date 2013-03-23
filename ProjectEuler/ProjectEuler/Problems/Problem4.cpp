#include "Problem4.h"

Problem4::Problem4(void)
{
	setName("Problem 4");
	max = 999;
}

bool isPalindrome(std::string str){
	int length = str.length();
	int max = (length % 2 == 0) ? length/2 : length/2 + 1;
	bool result = true;
	for(int i=0;result && i<=max;i++){
		if(str[i] != str[length - i - 1]){
			result = false;
		}
	}
	return result;
}

int Problem4::run(void){
	int result =  0;
	for(int a = max;a*max > result;a--){		
		for(int b = max;b>=a && b*a > result;b--){
			if(isPalindrome(std::to_string(b * a))){
				result = a*b;
			}
		}
	}
	return result;
}

